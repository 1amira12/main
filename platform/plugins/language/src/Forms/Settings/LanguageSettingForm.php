<?php

namespace Botble\Language\Forms\Settings;

use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\FormAbstract;
use Botble\Language\Facades\Language;
use Botble\Language\Http\Requests\Settings\LanguageSettingRequest;
use Botble\Setting\Models\Setting;
use Illuminate\Support\Facades\Blade;

class LanguageSettingForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Setting::class)
            ->setUrl(route('languages.settings'))
            ->setMethod('POST')
            ->setFormOption('class', 'language-settings-form')
            ->contentOnly()
            ->setValidatorClass(LanguageSettingRequest::class)
            ->add(
                'language_hide_default',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/language::language.language_hide_default'))
                    ->value(setting('language_hide_default', true))
                    ->toArray()
            )
            ->add(
                'language_display',
                RadioField::class,
                RadioFieldOption::make()
                ->label(trans('plugins/language::language.language_display'))
                ->choices([
                    'all' => trans('plugins/language::language.language_display_all'),
                    'flag' => trans('plugins/language::language.language_display_flag_only'),
                    'name' => trans('plugins/language::language.language_display_name_only'),
                ])
                ->selected(setting('language_display', 'all'))
                ->toArray()
            )
            ->add(
                'language_switcher_display',
                'customRadio',
                RadioFieldOption::make()
                    ->label(trans('plugins/language::language.switcher_display'))
                    ->choices([
                        'dropdown' => trans('plugins/language::language.language_switcher_display_dropdown'),
                        'list' => trans('plugins/language::language.language_switcher_display_list'),
                    ])
                    ->selected(setting('language_switcher_display', 'dropdown'))
                    ->toArray()
            );

        if ($languageActives = Language::getActiveLanguage()) {
            $choices = [];
            foreach ($languageActives as $language) {
                if (! $language->lang_is_default) {
                    $choices[$language->lang_id] = $language->lang_name;
                }
            }

            if ($choices) {
                $this->add('hide_languages', HtmlField::class, [
                    'html' => Blade::render(
                        sprintf(
                            '<x-core::form.label for="language_hide_languages"> %s
                            </x-core::form>',
                            trans('plugins/language::language.hide_languages')
                        )
                    ),
                ])
                ->add(
                    'language_hide_languages[]',
                    MultiCheckListField::class,
                    MultiChecklistFieldOption::make()
                    ->label(false)
                    ->choices($choices)
                    ->selected(json_decode(setting('language_hide_languages', '[]'), true))
                    ->toArray()
                );
            }
        }

        $this->add('hide_languages_helper_display_hidden', HtmlField::class, [
            'html' => Blade::render(
                sprintf(
                    '<x-core::alert type="info"> %s </x-core::alert>',
                    trans_choice('plugins/language::language.hide_languages_helper_display_hidden', count(json_decode(setting('language_hide_languages', '[]'), true)), ['language' => Language::getHiddenLanguageText()])
                )
            ),
        ])
        ->add(
            'language_show_default_item_if_current_version_not_existed',
            OnOffCheckboxField::class,
            OnOffFieldOption::make()
                ->label(trans('plugins/language::language.language_show_default_item_if_current_version_not_existed'))
                ->value(setting('language_show_default_item_if_current_version_not_existed', true))
                ->toArray()
        )
        ->add(
            'language_auto_detect_user_language',
            OnOffCheckboxField::class,
            OnOffFieldOption::make()
                ->label(trans('plugins/language::language.language_auto_detect_user_language'))
                ->value(setting('language_auto_detect_user_language', true))
                ->toArray()
        )
        ->add('button_action', HtmlField::class, [
            'html' => Blade::render(sprintf(
                '<x-core::button color="primary" icon="ti ti-device-floppy" type="submit">%s</x-core::button>',
                trans('core/setting::setting.save_settings')
            )),
        ]);
    }
}
