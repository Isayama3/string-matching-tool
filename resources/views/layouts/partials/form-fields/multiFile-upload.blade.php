@push('css')
    <link rel="stylesheet" href={{ asset('dashboard/extensions/filepond/filepond.css') }}>
    <link rel="stylesheet"
        href={{ asset('dashboard/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}>
    <link rel="stylesheet" href={{ asset('dashboard/extensions/toastify-js/src/toastify.css') }}>
@endpush

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}" id="{{ __('admin_panel.'.$name) }}_wrap">
    <label class="mb-1" for="{{ __('admin_panel.'.$name) }}">{{ __('admin_panel.'.$label) }}</label>
    <div class="filepond--root multiple-files-filepond filepond--hopper" data-style-button-remove-item-position="left"
        data-style-button-process-item-position="right" data-style-load-indicator-position="right"
        data-style-progress-indicator-position="right" data-style-button-remove-item-align="false"
        style="height: 76px;">
        <input class="filepond--browser" type="file" id="filepond--browser-puga3t33s" name="{{ __('admin_panel.'.$name) }}"
            aria-controls="filepond--assistant-puga3t33s" aria-labelledby="filepond--drop-label-puga3t33s"
            accept="" multiple="">
        <div class="filepond--list-scroller" style="transform: translate3d(0px, 60px, 0px);">
            <ul class="filepond--list" role="list"></ul>
        </div>
        <div class="filepond--panel filepond--panel-root" data-scalable="true">
            <div class="filepond--panel-top filepond--panel-root"></div>
            <div class="filepond--panel-center filepond--panel-root"
                style="transform: translate3d(0px, 8px, 0px) scale3d(1, 0.6, 1);"></div>
            <div class="filepond--panel-bottom filepond--panel-root" style="transform: translate3d(0px, 68px, 0px);">
            </div>
        </div><span class="filepond--assistant" id="filepond--assistant-puga3t33s" role="status" aria-live="polite"
            aria-relevant="additions"></span>
        <fieldset class="filepond--data"></fieldset>
        <div class="filepond--drip"></div>
    </div>
    <span class="help-block"><strong id="{{$name}}_error">{{ $errors->first($name) }}</strong></span>
</div>

@push('scripts')
    <script
        src={{ asset('dashboard/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}>
    </script>
    <script
        src={{ asset('dashboard/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}>
    </script>
    <script src={{ asset('dashboard/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}></script>
    <script
        src={{ asset('dashboard/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}>
    </script>
    <script src={{ asset('dashboard/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}>
    </script>
    <script src={{ asset('dashboard/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}>
    </script>
    <script src={{ asset('dashboard/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}>
    </script>
    <script src={{ asset('dashboard/extensions/filepond/filepond.js') }}></script>
    <script src={{ asset('dashboard/extensions/toastify-js/src/toastify.js') }}></script>
    <script src={{ asset('dashboard/static/js/pages/filepond.js') }}></script>
@endpush
