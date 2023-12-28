@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
    @endif
@endif

@if ($showLabel && $options['label'] !== false && $options['label_show'])
    {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
@endif

@if ($showField)
    <div>
        @foreach (Arr::get($options, 'choices', []) as $key => $item)
            <x-core::form.checkbox
                :id="sprintf('%s-item-%s', $name, $key)"
                :name="$name"
                :value="$key"
                :label="$item"
                :checked="in_array($key, Arr::get($options, 'value', []) ?: Arr::get($options, 'selected', []))"
                :inline="Arr::get($options, 'inline', false)"
            />
        @endforeach

        @include('core/base::forms.partials.help-block')
    </div>
@endif

@include('core/base::forms.partials.errors')

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif
