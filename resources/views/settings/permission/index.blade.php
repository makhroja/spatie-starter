@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permission</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Permission</h6>
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
                ajax: "{{ route('permission.index') }}",
                columnDefs: [{
                    targets: "_all",
                    orderable: false
                }, {
                    width: "2%",
                    targets: [0, 2, 3]
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
                alertify.alert().set({
                    title: 'Permission Create',
                    label: 'Batal',
                    message: `
                        <form action="{{ route('permission.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                        <div class="modal-body">
                        <div class="form-group mb-3">
                        <label for="">Permission Name</label>
                        <input name="name" type="text" required class="form-control">
                        </div>
                        </div>
                        <div class="modal-footer bg-white">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                        </div>
                        </form>
                        `
                }).show();
            })

            // for edit
            table.on('click', '.edit', function() {
                let id = $(this).data("id");
                $.ajax({
                    url: "{{ url('admin/permission') }}/" + id,
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
                            label: 'Batal',
                            message: `
                                <form action="{{ url('admin/permission') }}/` + id + `" method="POST">
                                @method('PATCH')
                                @csrf
                                <div class="modal-content">
                                <div class="modal-body">
                                <input type="hidden" name="permission_id" value="` + data.permission.id + `" />
                                <div class="form-group mb-3">
                                <label for="">Permission Name</label>
                                <input name="name" type="text" required class="form-control" value="` + data.permission
                                .name + `">
                                </div>
                                </div>
                                <div class="modal-footer bg-white">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                </div>
                                </form>
                                `
                        }).show();
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

            // for read
            table.on('click', '.show', function() {
                let id = $(this).data("id");

                $.ajax({
                    url: "{{ url('admin/permission') }}/" + id,
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'Permission Detail',
                            transition: 'zoom',
                            message: '<table class="table table-bordered">' +
                                '<tr>' +
                                '<th style="width:10%">Name</th>' +
                                '<td>' + data.permission.name + '</td>' +
                                '</tr>' +
                                '<tr>' +
                                '<th style="width:10%">Guard</th>' +
                                '<td>' + data.permission.guard_name + '</td>' +
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
                alertify.confirm('Hapus Permission', 'Anda yakin akan menghapus Permission <b>' + name +
                    '</b>',
                    function() {

                        $.ajax({
                            url: "{{ url('admin/permission') }}/" + id,
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
