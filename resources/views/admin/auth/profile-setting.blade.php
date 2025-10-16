@extends('admin.layout.layout')
@section('mian-content')
    <style>
        .iti {
            display: block !Important;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <div class="position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg profile-setting-img">
                    <img src="{{ asset($profile->cover_image ?: 'assets/admin/images/profile-bg.jpg') }}"
                        id="coverImagePreview" class="profile-wid-img" alt="">
                    <div class="overlay-content">
                        <div class="text-end p-3">
                            <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                                <!-- Hidden file input -->
                                <input id="cover-img-file-input" type="file" class="profile-img-file-input d-none">
                                <!-- Custom button for choosing cover -->
                                {{-- <label for="cover-img-file-input" class="btn btn-light">
                                    <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                                </label> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3">
                    <div class="card mt-n5">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                    <img src="{{ asset($profile->profile_image ?: 'assets/admin/images/users/avatar-1.jpg') }}"
                                        id="profileImagePreview"
                                        class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow"
                                        alt="user-profile-image">
                                    <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                        <!-- Hidden file input for profile image -->
                                        <input id="profile-img-file-input" type="file"
                                            class="profile-img-file-input d-none">
                                        <!-- Custom button for choosing profile image -->
                                        <label for="profile-img-file-input" class="avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-body shadow">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <h5 class="fs-16 mb-1">{{ $profile->name }}</h5>
                                <p class="text-muted mb-0">{{ $profile->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12">
                <div class="card mt-xxl-n5">
                    <div class="card-header">
                        <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                    <i class="far fa-user"></i> Change Password
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">

                            <div class="tab-pane active" id="changePassword" role="tabpanel">
                                <form id="changePasswordForm" class="needs-validation"
                                    action="{{ route('admin.change.password') }}" method="POST" novalidate>
                                    @csrf
                                    <div class="row g-2">

                                        <div class="col-lg-4">
                                            <div>
                                                <label for="newpasswordInput" class="form-label">New Password*</label>
                                                <input type="password" class="form-control" id="newpasswordInput"
                                                    name="password" placeholder="Enter new password" required>
                                                <div class="invalid-feedback">
                                                    Please provide new password
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div>
                                                <label for="confirmpasswordInput" class="form-label">Confirm
                                                    Password*</label>
                                                <input type="password" class="form-control" id="confirmpasswordInput"
                                                    name="password_confirmation" placeholder="Confirm password" required>
                                                <div class="invalid-feedback">
                                                    Please confirm password
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">Change Password</button>
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
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on("change", ".image-input", function() {
                    let input = this;
                    let preview = $(this).closest(".image-description-row").find(".preview-img");
                    let removeBtn = $(this).closest(".image-description-row").find(".remove-field");

                    if (input.files && input.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            preview.attr("src", e.target.result).show();
                            removeBtn.show();
                        };
                        reader.readAsDataURL(input.files[0]);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#changePasswordForm').on('submit', function(e) {
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
                            console.log(xhr);
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

                function uploadImage(imageFile, type) {
                    let formData = new FormData();
                    formData.append(type, imageFile);
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: "{{ route('admin.profile.update.images') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            successToast(response.message);
                        },
                        error: function(xhr) {
                            errorToast('An error occurred while updating the ' + type + '.');
                        }
                    });
                }

                $('#profile-img-file-input').on('change', function() {
                    let profileImage = this.files[0];
                    if (profileImage) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#profileImagePreview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(profileImage);
                        uploadImage(profileImage, 'profile_image');
                    }
                });

                $('#cover-img-file-input').on('change', function() {
                    let coverImage = this.files[0];
                    if (coverImage) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#coverImagePreview').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(coverImage);
                        uploadImage(coverImage, 'cover_image');
                    }
                });

            });
        </script>
    @endpush
