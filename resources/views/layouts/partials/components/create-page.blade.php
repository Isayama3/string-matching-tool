<section class="section">
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card p-3">
                {{-- <div class="card-header">
                    <h4 class="card-title">Hoverable rows</h4>
                </div> --}}
                <div class="card-content">
                    <form class="form" method="POST" action="{{ route($store_route) }}">
                        @csrf
                        @foreach ($fields as $field => $options)
                            @if ($options['input-type'] == 'text')
                                {{ \Helper\Field::text($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'email')
                                {{ \Helper\Field::email($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'number')
                                {{ \Helper\Field::number($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'password')
                                {{ \Helper\Field::password($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'select')
                                {{ \Helper\Field::select($field, __('admin_panel.' . $field), $options['options']) }}
                            @elseif ($options['input-type'] == 'checkBox')
                                {{ \Helper\Field::checkBox($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'radio')
                                {{ \Helper\Field::radio($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'textarea')
                                {{ \Helper\Field::textarea($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'fileWithPreview')
                                {{ \Helper\Field::fileWithPreview($field, __('admin_panel.' . $field) ,$options['system']) }}
                            @elseif ($options['input-type'] == 'multiFileUpload')
                                {{ \Helper\Field::multiFileUpload($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'dateTime')
                                {{ \Helper\Field::dateTime($field, __('admin_panel.' . $field)) }}
                            @elseif ($options['input-type'] == 'dateRange')
                                {{ \Helper\Field::dateRange($field, __('admin_panel.' . $field)) }}
                            @endif
                        @endforeach
                        @foreach ($custom_fields as $custom_field)
                            @dd($custom_field)
                        @endforeach
                        <br />
                        <div class="col-12 d-flex justify-content-start">
                            <button type="submit"
                                class="btn btn-primary me-1 mb-1">{{ __('admin_panel.submit') }}</button>
                            <button type="reset"
                                class="btn btn-light-secondary me-1 mb-1">{{ __('admin_panel.reset') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
