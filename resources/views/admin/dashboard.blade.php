@extends('admin.layout.layout')
@section('mian-content')
    <div class="page-content">
        <div class="container-fluid">


            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a>
							</li> --}}
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col">

                    <div class="h-100">
                        <div class="row mb-3 pb-1">
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <h4 class="fs-16 mb-1">{{ $greeting }},
                                            {{ Auth::user()->name }}!</h4>
                                        <p class="text-muted mb-0">Here's what's happening with your business
                                            today.</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            {{-- SuperAdmin counter --}}
                            @if (auth()->user()->role === 'SuperAdmin')
                                <x-counter-card title="Total Clients" :count="$clients" icon="bx-store-alt"
                                    bgColor="bg-primary" />
                            @endif
                            {{-- Url counter --}}
                            <x-counter-card title="Total URLs" :count="$urls" icon="bx-link" bgColor="bg-info" />
                            {{-- User counter --}}
                            @if (auth()->user()->role === 'Admin')
                                <x-counter-card title="Total Users" :count="$users" icon="bx-user" bgColor="bg-warning" />
                            @endif
                        </div>

                    </div>

                </div>
            </div>



            {{-- SuperAdmin client Table --}}
            @if (auth()->user()->role === 'SuperAdmin')
                <x-data-table id="clientTable" modal="clientModal" title="Clients" :columns="['SR No.', 'Name', 'Users', 'Total Generated URL', 'Total URL Hits']" />
            @endif


            {{-- Url DataTable --}}
            <x-data-table id="shortUrlTable" modal="shortUrlModel" title="Short URLs" :columns="['SR No.', 'Short URL', 'Original URL', 'Hits', 'Clients', 'Created At']" />

            {{-- Admin User Table --}}
            @if (in_array(auth()->user()->role, ['Admin']))
                <x-data-table id="userTable" modal="userInvitationModal" title="Users" :columns="['SR No.', 'Name', 'Email', 'Role', 'Total Urls', 'Total Hits']" />
            @endif
            {{-- Short URL Modal --}}
            @if (in_array(auth()->user()->role, ['Admin', 'Member']))
                <div class="modal fade" id="shortUrlModel" tabindex="-1" aria-labelledby="Label" aria-hidden="true"
                    data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Short URL</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="shortUrlForm" class="needs-validation" method="post"
                                action="{{ route('admin.shorturls.store') }}" novalidate>
                                @csrf
                                <input type="hidden" id="shortUrlId" name="id" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Original URL</label>
                                        <input type="url" name="original_url" id="originalUrl" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="shortUrlForm" class="btn btn-success"
                                            id="add-btn">Generate</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Invitation Modal (Admin) --}}
            @if (auth()->user()->role === 'Admin')
                <div class="modal fade" id="userInvitationModal" tabindex="-1" aria-labelledby="Label" aria-hidden="true"
                    data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Invite User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="invitationForm" class="needs-validation" method="post"
                                action="{{ route('admin.invitations.store') }}" novalidate>
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" name="name" id="inviteName" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" name="email" id="inviteEmail" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <select name="role" id="inviteRole" class="form-control select2" required>
                                            <option value="Member" selected>Member</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="client_id" id="clientIdInvite"
                                        value="{{ auth()->user()->company_id }}">
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="invitationForm" class="btn btn-success"
                                            id="add-btn">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            {{-- client Modal (SuperAdmin) --}}
            @if (auth()->user()->role === 'SuperAdmin')
                <div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="Label" aria-hidden="true"
                    data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Invite Client</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="clientForm" class="needs-validation" method="post"
                                action="{{ route('admin.companies.store') }}" novalidate>
                                @csrf
                                <input type="hidden" id="clientId" value="">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="text" id="clientName" name="name" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" id="clientEmail" name="email" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" form="clientForm" class="btn btn-success"
                                            id="add-btn">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/admin/js/custom/dashboardManager.js') }}"></script>
@endpush
