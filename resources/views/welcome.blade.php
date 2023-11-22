@extends('layouts.master')
@section('content')


    <div class="container">
        <div class="card mt-5">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title">Upload Excel File!</h4>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end ">
                        <form id="sample-excel-file-button" method="get" action="{{ route('sample-data-excel-file') }}">
                            <button type="button" class="btn btn-success me-1 mb-1" onclick="submitForm()">Sample Excel
                                File!</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!empty(session('errors')))
                    <div class="alert alert-danger">
                        @foreach (session('errors')->all() as $key => $errors)
                            <h6>{{ $key }} At rows : </h6>
                            <ul>

                                @foreach ($errors as $error)
                                    <li> {{ $error }}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
                <form action="{{ route('import-data-excel-file') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="d-flex justify-content-center filepond--root basic-filepond filepond--hopper"
                            data-style-button-remove-item-position="left" data-style-button-process-item-position="right"
                            data-style-load-indicator-position="right" data-style-progress-indicator-position="right"
                            data-style-button-remove-item-align="false" style="height: 76px;">
                            <input name="data_excel_file" class="filepond--browser" type="file"
                                id="filepond--browser-b51my4par" name="filepond"
                                aria-controls="filepond--assistant-b51my4par"
                                aria-labelledby="filepond--drop-label-b51my4par" accept="">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@push('scripts')
    <script>
        function submitForm() {
            $('#sample-excel-file-button').submit();
        }
    </script>
@endpush
