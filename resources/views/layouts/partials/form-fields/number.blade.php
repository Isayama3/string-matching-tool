<div class="form-group {{ $errors->has($name) ? 'is-invalid' : '' }}">
    <label class="mb-1" for="{{ __('admin_panel.'.$name) }}">{{ __('admin_panel.'.$label) }}</label>
    <input type="number" class="form-control" id="{{$name}}"  name={{$name}} placeholder="{{$label}}" spellcheck="false" data-ms-editor="true" {{$required == 'true' ? 'required' : ''}} value="{{ $value == null ? old($name) : $value }}">
    <span class="help-block"><strong id="{{$name}}_error">{{ $errors->first($name) }}</strong></span>

</div>