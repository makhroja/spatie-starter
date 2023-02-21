@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Role</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Role</h6>
                    <button type="button" class="btn btn-icon btn-outline-primary create">
                        <i data-feather="plus-square"></i>
                    </button>
                    <hr>
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript">
        $(function() {

            // datatable
            let table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                scrollY: true,
                scrollX: true,
                // select: true,
                // select: 'single',
                ajax: "{{ route('indexRole') }}",
                columnDefs: [{
                    targets: "_all",
                    orderable: false
                }, {
                    width: "2%",
                    targets: [0, 1, 3, 4]
                }, {
                    //buat wrap text
                    render: function(data, type, full, meta) {
                        return "<div class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: [1]
                }, ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'team_id',
                        name: 'team_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'guard_name',
                        name: 'guard_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            // for create
            $('.create').on('click', function() {
                let url = "{{ route('createRole', '') }}";

                window.open(url, '_self');
            })

            // for edit
            table.on('click', '.edit', function() {
                let id = $(this).data("id");
                let url = "{{ route('editRole', '') }}/" + id + "";

                window.open(url, '_self');
            });

            // for read
            table.on('click', '.show', function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "{{ route('showRole') }}",
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'Role Detail',
                            startMaximized: false,
                            transition: 'zoom',
                            message: '<table class="table table-bordered">' +
                                '<tr>' +
                                '<th style="width:10%">Team</th>' +
                                '<td>' + data.role.team_id + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Name</th>' +
                                '<td>' + data.role.name + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Guard</th>' +
                                '<td>' + data.role.guard_name + '</td>' +
                                '</tr>' +
                                '</table>'
                        }).show();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alertify.alert().set({
                            'title': 'Error',
                            'message': data.responseJSON.message
                        }).show();
                    }
                });
            });

            // for delete
            table.on('click', '.delete', function() {
                let name = $(this).data("name");
                let id = $(this).data("id");
                alertify.confirm('Hapus Role', 'Anda yakin akan menghapus role <b>' + name + '</b>',
                    function() {

                        $.ajax({
                            url: "{{ route('deleteRole') }}",
                            type: 'DELETE',
                            dataType: "JSON",
                            data: {
                                "id": id,
                                "_token": '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                table.draw();
                                console.log('Success:', data);
                                alertify.alert().set({
                                    'title': 'Success',
                                    'message': data.success
                                }).show();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                alertify.alert().set({
                                    'title': 'Error',
                                    'message': data.responseJSON.message
                                }).show();
                            }
                        });
                    },
                    function() {
                        alertify.error('Cancel')
                    });
            });

            /*
             *End Document Ready
             */
        });
    </script>
@endpush
