<?php

namespace Botble\Ecommerce\Http\Controllers\Settings;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\StoreLocator;

class StoreLocatorSettingController extends BaseController
{
    public function index()
    {
        $this->pageTitle(trans('plugins/ecommerce::setting.store_locator.name'));

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly([
                'vendor/core/plugins/ecommerce/js/store-locator.js',
            ]);

        $storeLocators = StoreLocator::query()->get();

        return view('plugins/ecommerce::settings.store-locator', compact('storeLocators'));
    }
}
