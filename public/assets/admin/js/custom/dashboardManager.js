class DashboardManager {
    constructor() {
        this.role = $('meta[name="userRole"]').attr('content');
        this.shortUrlTable = null;
        this.clientTable = null;
        this.init();
    }

    init() {
        $(document).ready(() => {
            this.setupModalReset();
            this.setupShortUrlFormSubmission();
            this.setupClientFormSubmission();
            this.setupInvitationFormSubmission();
            this.initializeShortUrlTable();
            if (this.role === 'SuperAdmin') {
                this.initializeClientTable();
            }

            if (this.role === 'Admin') {
                this.initializeUserTable();
            }
            this.setupDateFilter();
            this.setupDownloadButton();
            this.setupRoleBasedUI();
        });
    }

    setupModalReset() {
        $(document).on('hidden.bs.modal', '.modal', function () {
            const form = $(this).find('form');
            form[0].reset();
            form.find('#shortUrlId, #clientId').val('');
            $('#originalUrl').prop('required', true);
            $(this).find('.invalid-feedback').remove();
            $(this).find('input, select').removeClass('is-invalid');
            form.removeClass('was-validated');
        });
    }

    setupClientFormSubmission() {
        $('#clientForm').on('submit', (e) => {
            e.preventDefault();
            const form = e.target;

            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                $('.invalid-feedback').remove();
                $('input').removeClass('is-invalid');

                const formData = new FormData(form);
                const url = $(form).attr('action');
                const method = $(form).find('input[name="_method"]').val() || 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        if (response.status === 'success') {
                            $('#clientModal').modal('hide');
                            form.reset();
                            $(form).removeClass('was-validated');
                            this.clientTable.ajax.reload();
                            successToast(response.message);
                        }
                    },
                    error: (response) => {

                        if (response.status === 500) {
                            errorToast(response.responseJSON.message);
                            this.clientTable.ajax.reload();
                            $('#clientModal').modal('hide');
                            form.reset();
                            $(form).removeClass('was-validated');
                            return;
                        }
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;
                            $.each(errors, (key, value) => {
                                const inputField = $('[name="' + key + '"]');
                                inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                                inputField.addClass('is-invalid');
                            });
                        }
                    }
                });
            }
        });
    }

    setupInvitationFormSubmission() {
        $('#invitationForm').on('submit', (e) => {
            e.preventDefault();
            const form = e.target;

            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                $('.invalid-feedback').remove();
                $('input, select').removeClass('is-invalid');

                const formData = new FormData(form);
                const url = $(form).attr('action');
                const method = $(form).find('input[name="_method"]').val() || 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        if (response.status === 'success') {
                            $('#userInvitationModal').modal('hide');
                            form.reset();
                            $(form).removeClass('was-validated');
                            this.userTable.ajax.reload();
                            successToast(response.message);
                        }
                    },
                    error: (response) => {

                        if (response.status === 500) {
                            errorToast(response.responseJSON.message);
                            this.userTable.ajax.reload();
                            $('#userInvitationModal').modal('hide');
                            form.reset();
                            $(form).removeClass('was-validated');
                            return;
                        }
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;
                            $.each(errors, (key, value) => {
                                const inputField = $('[name="' + key + '"]');
                                inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                                inputField.addClass('is-invalid');
                            });
                        }
                    }
                });
            }
        });
    }

    setupShortUrlFormSubmission() {
        $('#shortUrlForm').on('submit', (e) => {
            e.preventDefault();
            const form = e.target;

            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
            } else {
                $('.invalid-feedback').remove();
                $('input, select').removeClass('is-invalid');

                const formData = new FormData(form);
                const url = $(form).attr('action');
                const method = $(form).find('input[name="_method"]').val() || 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        if (response.status === 'success') {
                            $('#shortUrlModel').modal('hide');
                            form.reset();
                            $(form).removeClass('was-validated');
                            this.shortUrlTable.ajax.reload();
                            successToast(response.message);
                        }
                    },
                    error: (response) => {
                        if (response.status === 422) {
                            const errors = response.responseJSON.errors;
                            $.each(errors, (key, value) => {
                                const inputField = $('[name="' + key + '"]');
                                inputField.after('<div class="invalid-feedback">' + value[0] + '</div>');
                                inputField.addClass('is-invalid');
                            });
                        }
                    }
                });
            }
        });
    }



    initializeClientTable() {
        this.clientTable = $('#clientTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pageLength: 10,
            ajax: "get-companies",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'users_count', name: 'users_count' },
                { data: 'total_generated_urls', name: 'total_generated_urls' },
                { data: 'total_url_hits', name: 'total_url_hits' },
            ],
            order: [[0, 'asc']]
        });
    }

    initializeUserTable() {
        this.userTable = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "get-users",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'total_generated_urls', name: 'total_generated_urls' },
                { data: 'total_hits', name: 'total_hits' },
            ],
            order: [[0, 'asc']]
        });
    }

    setupDateFilter() {
        $('#dateFilter').on('change', () => {
            this.shortUrlTable.ajax.reload();
        });
    }

    setupDownloadButton() {
        $('#downloadUrls').on('click', () => {
            const dateFilter = $('#dateFilter').val();
            window.location.href = `/download-urls?date_filter=${dateFilter}`;
        });
    }

    initializeShortUrlTable() {
        this.shortUrlTable = $('#shortUrlTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            pageLength: 10,
            ajax: {
                url: "get-short-urls",
                data: function (d) {
                    d.date_filter = $('#dateFilter').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'short_code', name: 'short_code' },
                { data: 'original_url', name: 'original_url' },
                { data: 'clicks', name: 'clicks' },
                { data: 'company_id', name: 'company_id' },
                { data: 'created_at', name: 'created_at' },
                // { data: 'tools', name: 'tools', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']]
        });
    }
    setupRoleBasedUI() {
        if (this.role !== 'SuperAdmin') {
            $('#clientModal, #clientTable').remove();
            $('[data-bs-target="#clientModal"]').prop('disabled', true).removeAttr('data-bs-toggle data-bs-target');
        }
        if (this.role !== 'Admin') {
            $('#userInvitationModal').remove();
            $('[data-bs-target="#userInvitationModal"]').prop('disabled', true).removeAttr('data-bs-toggle data-bs-target');
        }
    }
}

new DashboardManager();