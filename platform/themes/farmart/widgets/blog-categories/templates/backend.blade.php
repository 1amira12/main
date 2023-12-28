<div class="mb-3">
    <label class="form-label" for="widget-name">{{ trans('core/base::forms.name') }}</label>
    <input
        class="form-control"
        name="name"
        type="text"
        value="{{ $config['name'] }}"
    >
</div>

<div class="mb-3">
    <label class="form-label" for="number_display">{{ __('Number categories to display') }}</label>
    <input
        class="form-control"
        name="number_display"
        type="number"
        value="{{ $config['number_display'] }}"
    >
</div>
