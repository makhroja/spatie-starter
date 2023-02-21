@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">User</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">User Management</h6>
                    <button type="button" class="btn btn-icon btn-outline-primary create">
                        <i data-feather="plus-square"></i>
                    </button>
                    <hr>
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created_at</th>
                                    <th>Verified_at</th>
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
                ajax: "{{ route('user.index') }}",
                columnDefs: [{
                    targets: "_all",
                    orderable: false
                }, {
                    width: "2%",
                    targets: [0, 3, 4, 5]
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'email_verified_at',
                        name: 'email_verified_at'
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
                $("#createUser").trigger("reset");
                alertify.alert().set({
                    title: 'User Create',
                    resizable: true,
                    label: 'Batal',
                    message: `
                        @include('backend.user._partials.createUser')
                        `
                }).resizeTo('60%', '75%').show();
            })

            // for show
            table.on('click', '.show', function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "{{ url('admin/user') }}/" + id,
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        created_at = new Date(data.user.created_at);
                        verified_at = new Date(data.user.email_verified_at);
                        var date_created_at = created_at.format("d mmmm yyyy");
                        var date_verified_at = verified_at.format("d mmmm yyyy");

                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'User Detail',
                            transition: 'zoom',
                            message: '<table class="table table-bordered">' +
                                '<tr>' +
                                '<th style="width:10%">Name</th>' +
                                '<td>' + data.user.name + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Email</th>' +
                                '<td>' + data.user.email + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Created_at</th>' +
                                '<td>' + date_created_at + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Verifed_at</th>' +
                                '<td>' + date_verified_at + '</td>' +
                                '</tr>' +
                                '</table>'
                        }).resizeTo('60%', '75%').show();
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

            // for edit
            table.on('click', '.edit', function() {
                let id = $(this).data("id");
                $.ajax({
                    url: "{{ url('admin/user') }}/" + id + "/edit",
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'Permission Edit',
                            resizable: true,
                            transition: 'zoom',
                            label: 'Batal',
                            message: `
                                @include('backend.user._partials.editUser')
                                `
                        }).resizeTo('60%', '75%').show();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        alertify.alert().set({
                            title: 'Error',
                            message: data.responseJSON.message
                        }).show();
                    }
                });
            });


            // for delete
            table.on('click', '.delete', function() {
                let name = $(this).data("name");
                let id = $(this).data("id");
                alertify.confirm('Hapus User', 'Anda yakin akan menghapus User <b>' + name +
                    '</b>',
                    function() {

                        $.ajax({
                            url: "{{ url('admin/user') }}/" + id,
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
