<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}" id="{{ __('admin_panel.'.$name) }}_wrap">
    <label for="{{ __('admin_panel.'.$name) }}">{{ __('admin_panel.'.$label) }}</label>
    <div class="">
        {!! Form::select($name . '[]', $options, $selected, [
            'data-placeholder' => $placeholder,
            'class' => 'form-control ' . $plugin,
            'multiple' => 'multiple',
            'id' => $name,
        ]) !!}
    </div>
    <span class="help-block"><strong id="{{$name}}_error">{{ $errors->first($name) }}</strong></span>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ __('admin_panel.'.$name) }}').select2();
        });
    </script>
@endpush
