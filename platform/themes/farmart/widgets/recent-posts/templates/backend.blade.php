<div class="mb-3">
    <label class="form-label" for="widget-name">{{ __('Name') }}</label>
    <input
        class="form-control"
        name="name"
        type="text"
        value="{{ $config['name'] }}"
    >
</div>
<div class="mb-3">
    <label class="form-label" for="number_display">{{ __('Number posts to display') }}</label>
    <input
        class="form-control"
        name="number_display"
        type="number"
        value="{{ $config['number_display'] }}"
    >
</div>
