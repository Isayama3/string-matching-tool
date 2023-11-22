@push('css')
    <link rel="stylesheet" href={{ asset('dashboard/extensions/flatpickr/flatpickr.min.css') }}>
@endpush

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}" id="{{ __('admin_panel.' . $name) }}_wrap">
    <label class="mb-1" for="{{ __('admin_panel.' . $name) }}">{{ __('admin_panel.' . $label) }}</label>
    <input name="{{$name}}" type="text"
        class="form-control mb-3 flatpickr-no-config flatpickr-input" placeholder="{{ $label }}"
        readonly="readonly">
    <span class="help-block"><strong id="{{ $name }}_error">{{ $errors->first($name) }}</strong></span>

</div>

@push('scripts')
    <script src={{ asset('dashboard/extensions/flatpickr/flatpickr.min.js') }}></script>
    <script src={{ asset('dashboard/static/js/pages/date-picker.js') }}></script>
@endpush
