@extends('admin.layout.layout')
@section('mian-content')
    <style>
        .iti {
            display: block !Important;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link" data-bs-toggle="tab" href="#mailDetails" role="tab">
                                    <i class="far fa-user"></i> Mail Setting
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="mailDetails" role="tabpanel">
                                <form id="mailDetailsForm" class="needs-validation"
                                    action="{{ route('admin.update.mail') }}" method="POST" novalidate>
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $mail->id }}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpDriver" class="form-label">Driver</label>
                                                <input type="text" class="form-control" id="smtpDriver"
                                                    name="driver" value="{{ $mail->driver }}" placeholder="Enter Driver name" required>
                                                <div class="invalid-feedback">
                                                    Please provide driver name
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpHost" class="form-label">Host</label>
                                                <input type="text" class="form-control" id="smtpHost"
                                                    name="host" placeholder="Enter Host name"
                                                    value="{{ $mail->host }}" required>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpPort" class="form-label">Port</label>
                                                <input type="text" class="form-control" id="smtpPort" name="port"
                                                    placeholder="Enter port"
                                                    value="{{ $mail->port }}" required>
                                                <div class="invalid-feedback">
                                                    Please provide port
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpFromAddress" class="form-label">From Email</label>
                                                <input type="text" class="form-control" id="smtpFromAddress" name="from_address"
                                                    placeholder="Enter email"
                                                    value="{{ $mail->from_address }}" required>
                                                <div class="invalid-feedback">
                                                    Please provide email
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpFromName " class="form-label">From Name</label>
                                                <input type="text" class="form-control" id="smtpFromName"
                                                    name="from_name" placeholder="Enter your name"
                                                    value="{{ $mail->from_name }}">
                                                <div class="invalid-feedback">
                                                    Please provide name
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpEncryptionType" class="form-label">Encryption Type</label>
                                                <input type="text" class="form-control" id="smtpEncryptionType"
                                                    name="encryption" placeholder="Enter Encryption type"
                                                    value="{{ $mail->encryption }}">
                                                <div class="invalid-feedback">Please provide encryption</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpUsername" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="smtpUsername" name="username"
                                                    placeholder="Enter username"
                                                    value="{{ $mail->username }}">
                                                <div class="invalid-feedback">
                                                    Please provide username
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="smtpPassword" class="form-label">Password</label>
                                                <input type="text" class="form-control" id="smtpPassword"
                                                    name="password" placeholder="Enter your password"
                                                    value="{{ $mail->password }}">
                                                <div class="invalid-feedback">
                                                    Please provide password
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-soft-success">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/intlTelInput.min.js') }}"></script>
    <script>
        const inputs = document.querySelectorAll("#phoneNumber");
        inputs.forEach(input => {
            window.intlTelInput(input, {
                loadUtils: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/utils.js",
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#mailDetailsForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    success: function(response) {
                        successToast(response.message);
                    },
                    error: function(xhr) {
                        $('.invalid-feedback').remove();
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, (key, value) => {
                                const inputField = $('[name="' + key + '"]');
                                inputField.after('<div class="invalid-feedback">' +
                                    value[0] + '</div>');
                                inputField.addClass('is-invalid');
                            });
                        } else {
                            errorToast(xhr.responseJSON.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
