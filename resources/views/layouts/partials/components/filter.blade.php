<section class="section">
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filter"
                        aria-expanded="true" aria-controls="filter">
                        <i class="bi bi-arrow-down"></i>
                        {{ __('admin_panel.search') }}
                    </button>
                    <a href="{{ route($create_route) }}">
                        <button href class="btn btn-primary float-end" type="button">
                            <i class="bi bi-plus-lg"></i>
                            {{ __('admin_panel.add_new') }}
                        </button>
                    </a>
                </div>
                <div class="card-content">
                    <div class="collapse" id="filter" style="">
                        <div class="card-body">
                            <form class="form">
                                <div class="row">
                                    @foreach ($filter as $key => $value)
                                        {{-- @dd($columns,$filter,$key) --}}
                                        @if ($columns[$key]['input-type'] == 'text')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::text("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'email')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::email("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'number')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::number("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'password')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::password("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'select')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::select("filter[$key]", $key, $columns[$key]['options']) }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'checkBox')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::checkBox("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'radio')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::radio("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'textarea')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::textarea("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'fileWithPreview')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::fileWithPreview("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'multiFileUpload')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::multiFileUpload("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'dateTime')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::dateTime("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @elseif ($columns[$key]['input-type'] == 'dateRange')
                                            <div class="col-md-3 col-12">
                                                {{ \Helper\Field::dateTime("filter[$key]", $key, required: 'false') }}
                                            </div>
                                        @endif
                                    @endforeach
                                    {{-- <div class="col-md-3 col-12">
                                        {{ \Helper\Field::dateRange("filter[$key]", $key, required: 'false') }}
                                    </div> --}}
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit"
                                            class="btn btn-primary me-1 mb-1">{{ __('admin_panel.submit') }}</button>
                                        <button type="reset"
                                            class="btn btn-light-secondary me-1 mb-1">{{ __('admin_panel.reset') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
