@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Team</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Team</h6>
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
                ajax: "{{ route('indexTeam') }}",
                columnDefs: [{
                    targets: "_all",
                    orderable: false
                }, {
                    width: "2%",
                    targets: [0, 2]
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
                    title: 'Team Create',
                    label: 'Batal',
                    message: `
                        <form action="{{ route('createTeam') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                        <div class="modal-body">
                        <div class="form-group mb-3">
                        <label for="">Team Name</label>
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
                    url: "{{ route('editTeam') }}",
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'Team Edit',
                            label: 'Batal',
                            message: `
                                <form action="{{ route('updateTeam') }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                <div class="modal-body">
                                <input type="hidden" name="id" value="` + data.team.id + `" />
                                <div class="form-group mb-3">
                                <label for="">Team Name</label>
                                <input name="name" type="text" required class="form-control" value="` + data.team
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
                    url: "{{ route('showTeam') }}",
                    type: 'GET',
                    dataType: "JSON",
                    data: {
                        "id": id,
                    },
                    success: function(data) {
                        table.draw();
                        console.log('Success:', data);
                        alertify.alert().set({
                            title: 'Team Detail',
                            transition: 'zoom',
                            message: '<table class="table table-bordered">' +
                                '<tr>' +
                                '<th style="width:10%">Name</th>' +
                                '<td>' + data.team.name + '</td>' +
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
                alertify.confirm('Hapus Team', 'Anda yakin akan menghapus Team <b>' + name + '</b>',
                    function() {

                        $.ajax({
                            url: "{{ route('deleteTeam') }}",
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
