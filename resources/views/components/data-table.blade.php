<!-- resources/views/components/data-table.blade.php -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header  border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">{{ $title ?? 'Basic Datatables' }}</h5>
                    <div class="d-flex flex-shrink-0 gap-1">
                        @if ($id == 'shortUrlTable')
                            <select id="dateFilter" class="form-select w-auto">
                                <option value="">All Time</option>
                                <option value="last_month">Last Month</option>
                                <option value="last_week">Last Week</option>
                                <option value="today">Today</option>
                            </select>
                            <span id="clientTable_info" class="info"></span>
                        @endif
                        @if ($id == 'shortUrlTable' && auth()->user()->role == 'SuperAdmin')
                        @else
                            <button type="button" class="btn btn-success add-btn create-btn" data-bs-toggle="modal"
                                data-bs-target="#{{ $modal ?? '' }}"><i
                                    class="ri-add-line align-bottom me-1"></i>{{ $id == 'shortUrlTable' ? 'Create' : 'Invite' }}</button>
                        @endif
                        @if ($id == 'shortUrlTable')
                            <button class="btn btn-soft-primary" id="downloadUrls"><i
                                    class="ri-download-2-fill"></i></button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="{{ $id ?? 'example' }}"
                    class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            @foreach ($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
