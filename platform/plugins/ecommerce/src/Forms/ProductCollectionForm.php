<?php

namespace Botble\Ecommerce\Forms;

use Botble\Base\Facades\Assets;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Http\Requests\ProductCollectionRequest;
use Botble\Ecommerce\Models\ProductCollection;

class ProductCollectionForm extends FormAbstract
{
    public function setup(): void
    {
        Assets::addStylesDirectly('vendor/core/plugins/ecommerce/css/ecommerce.css')
            ->addScriptsDirectly('vendor/core/plugins/ecommerce/js/edit-product-collection.js');

        $this
            ->setupModel(new ProductCollection())
            ->setValidatorClass(ProductCollectionRequest::class)
            ->add('name', 'text', [
                'label' => trans('core/base::forms.name'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 250,
                ],
                'help_block' => [
                    'text' => $this->getModel()->id ? trans(
                        'plugins/ecommerce::product-collections.slug_help_block',
                        ['slug' => $this->getModel()->slug]
                    ) : null,
                    'tag' => 'small',
                    'attr' => [
                        'class' => 'form-hint',
                    ],
                ],
            ])
            ->add('slug', 'text', [
                'label' => trans('core/base::forms.slug'),
                'required' => true,
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('description', 'textarea', [
                'label' => trans('core/base::forms.description'),
                'attr' => [
                    'rows' => 4,
                    'placeholder' => trans('plugins/ecommerce::products.form.description'),
                    'data-counter' => 400,
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make()->toArray())
            ->add('is_featured', 'onOff', [
                'label' => trans('core/base::forms.is_featured'),
                'default_value' => false,
            ])
            ->add('image', 'mediaImage')
            ->setBreakFieldPoint('status');

        if ($productCollectionId = $this->getModel()->id) {
            $this
                ->addMetaBoxes([
                    'collection-products' => [
                        'title' => trans('plugins/ecommerce::products.name'),
                        'content' =>
                            Html::tag('div', '', [
                                'class' => 'wrap-collection-products',
                                'data-target' => route('product-collections.get-product-collection', $productCollectionId),
                            ]),
                        'priority' => 9999,
                    ],
            ]);
        }
    }
}
