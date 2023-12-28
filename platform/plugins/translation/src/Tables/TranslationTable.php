<?php

namespace Botble\Translation\Tables;

use Botble\Base\Facades\Html;
use Botble\Base\Supports\Language;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Columns\Column;
use Botble\Translation\Models\Translation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class TranslationTable extends TableAbstract
{
    protected string $locale = 'en';

    public function setup(): void
    {
        parent::setup();

        $this->hasOperations = false;
        $this->setView($this->simpleTableView());
        $this->pageLength = 100;

        $this
            ->model(Translation::class)
            ->queryUsing(function (Builder $query) {
                $query
                    ->where('locale', $this->locale)
                    ->when(
                        $this->request()->input('group'),
                        fn (Builder $query, $group) => $query->where('group', $group)
                    );
            })
            ->onAjax(fn () => $this->toJson(
                $this->table
                    ->eloquent($this->query())
                    ->editColumn('key', function ($item) {
                        return $this->formatKeyAndValue(
                            trans(str($item->group)->replaceLast('/', '::')->append(".$item->key")->toString())
                        );
                    })
                    ->editColumn('value', function ($item) {
                        return Html::link('#edit', $this->formatKeyAndValue($item->value), [
                            'class' => sprintf('editable status-%s locale-%s', $item->status, $this->locale),
                            'data-locale' => $this->locale,
                            'data-name' => sprintf('%s|%s', $this->locale, $item->key),
                            'data-type' => 'textarea',
                            'data-pk' => $item ? $item->id : 0,
                            'data-title' => trans('plugins/translation::translation.edit_title'),
                            'data-url' => route('translations.group.edit', ['group' => $item->group]),
                        ]);
                    })
            ));
    }

    public function columns(): array
    {
        return [
            Column::make('key')
                ->title(Arr::get(Language::getAvailableLocales(), 'en.name', 'en'))
                ->alignStart(),
            Column::make('value')
                ->title(Arr::get(Language::getAvailableLocales(), "{$this->locale}.name", $this->locale))
                ->alignStart(),
        ];
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    protected function formatKeyAndValue(string|null $value): string|null
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    public function isSimpleTable(): bool
    {
        return false;
    }
}
