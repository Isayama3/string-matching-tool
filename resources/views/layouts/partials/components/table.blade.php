<section class="section">
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="table-responsive py-1 px-3">
                        <table class="table table-responsive table-hover mb-0 ">
                            <thead>
                                <tr>
                                    @foreach ($columns as $key => $value)
                                        <th>{{ __('admin_panel.' . $key) }}</th>
                                    @endforeach
                                    <th>{{ __('admin_panel.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $record)
                                    <tr id="removable{{ $record->id }}">
                                        @foreach ($columns as $k => $v)
                                            <td>{{ $record->$k }}</td>
                                        @endforeach
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route($edit_route, $record->id) }}">
                                                    <button href class="btn btn-success float-start" type="button">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                </a>
                                                <!-- Start modal -->
                                                <button type="button" class="btn btn-primary float-start mx-3"
                                                    data-bs-toggle="modal" data-bs-target="#inlineForm">
                                                    <i class="bi bi-envelope"></i>
                                                </button>
                                                <div class="modal fade text-left" id="inlineForm" tabindex="-1"
                                                    aria-labelledby="myModalLabel33" style="display: none;"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                                        role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel33">Login Form
                                                                </h4>
                                                                <button type="button" class="close"
                                                                    data-bs-dismiss="modal" aria-label="Close">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-x">
                                                                        <line x1="18" y1="6"
                                                                            x2="6" y2="18"></line>
                                                                        <line x1="6" y1="6"
                                                                            x2="18" y2="18"></line>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('user.firebase.msg', $record->device_key) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <label
                                                                        for="message">{{ __('admin_panel.message') }}
                                                                    </label>
                                                                    <div class="form-group">
                                                                        <input id="message" type="text"
                                                                            placeholder="{{ __('admin_panel.message') }}"
                                                                            class="form-control" name="message" required>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-light-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="bx bx-x d-block d-sm-none"></i>
                                                                        <span
                                                                            class="d-none d-sm-block">{{ __('admin_panel.close') }}</span>
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary ms-1"
                                                                        data-bs-dismiss="modal">
                                                                        <i class="bx bx-check d-block d-sm-none"></i>
                                                                        <span
                                                                            class="d-none d-sm-block">{{ __('admin_panel.send') }}</span>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End modal -->
                                                <button id="{{ $record->id }}" data-token="{{ csrf_token() }}"
                                                    data-route="{{ route($destroy_route, $record->id) }}"
                                                    type="button" class="destroy btn btn-danger float-start">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4">
                    {{ $records->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</section>
