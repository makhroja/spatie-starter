@extends('layout.master')

@push('plugin-styles')
@endpush

@section('content')
    <nav class="page-breadcrumb">

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Role</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body">
            <div class="form-check form-check-inline">
                <label class="form-check-label" for="checkPermissionAll">All Permission Checked
                    <input id="checkPermissionAll" value="1"
                        {{ App\User::roleHasPermissions($role, $all_permissions) ? 'checked' : '' }} type="checkbox"
                        class="form-check-input">
                </label>
            </div>
            @php $i = 1; @endphp
            <form action="{{ url('admin/role') }}/{{ $role->id }}" method="POST">
                <input type="hidden" name="role_id" value="{{ $role->id }}">
                @method('PATCH')
                @csrf
                <div class="row mt-2">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="">Team Id</label>
                                    <select name="team_id" id="team_id">
                                        <option value="">Pilih Team</option>
                                        @foreach ($teams as $key => $team)
                                            <option value="{{ $key }}"
                                                {{ old('team_id', optional($role)->team_id) == $key ? 'selected' : '' }}>
                                                {{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="">Role Name</label>
                                    <input value="{{ old('name', optional($role)->name) }}" name="name" type="text"
                                        required class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="nav nav-tabs nav-tabs-vertical" id="v-tab" role="tablist"
                                            aria-orientation="vertical">
                                            @foreach ($permission_groups as $group)
                                                <a class="nav-link" id="v-{{ $group->name }}-tab" data-toggle="pill"
                                                    href="#v-{{ $group->name }}" role="tab"
                                                    aria-controls="v-{{ $group->name }}"
                                                    aria-selected="true">{{ Str::ucfirst($group->name) }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="tab-content tab-content-vertical border p-3" id="v-tabContent">
                                            @foreach ($permission_groups as $group)
                                                @php
                                                    $permissions = App\User::getpermissionsByGroupName($group->name);
                                                    $j = 1;
                                                @endphp
                                                <div class="tab-pane fade" id="v-{{ $group->name }}" role="tabpanel"
                                                    aria-labelledby="v-{{ $group->name }}-tab">
                                                    <h5 class="mb-1">
                                                        <div class="form-check form-check-inline">
                                                            <label
                                                                class="form-check-label">{{ Str::ucfirst($group->name) }}
                                                                <input id="{{ $i }}Management"
                                                                    value="{{ $group->name }}"
                                                                    {{ App\User::roleHasPermissions($role, $permissions) ? 'checked' : '' }}
                                                                    onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)"
                                                                    type="checkbox" class="form-check-input permission">
                                                            </label>
                                                        </div>
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-3 role-{{ $i }}-management-checkbox">
                                                            @foreach ($permissions as $permission)
                                                                <label class="ml-3">
                                                                    <div class="form-check form-check-inline">
                                                                        <label
                                                                            class="form-check-label">{{ $permission->name }}
                                                                            <input name="permission[]"
                                                                                value="{{ $permission->id }}"
                                                                                onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})"
                                                                                name="permissions[]"
                                                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                                                id="checkPermission{{ $permission->id }}"
                                                                                value="{{ $permission->name }}"
                                                                                type="checkbox"
                                                                                class="form-check-input permission">
                                                                        </label>
                                                                    </div>
                                                                </label>
                                                                @php  $j++; @endphp
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @php  $i++; @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
    <script>
        /**
         * Check all the permissions
         */
        $("#checkPermissionAll").click(function() {
            if ($(this).is(':checked')) {
                // check all the checkbox
                $('input[type=checkbox]').prop('checked', true);
            } else {
                // un check all the checkbox
                $('input[type=checkbox]').prop('checked', false);
            }
        });

        function checkPermissionByGroup(className, checkThis) {
            const groupIdName = $("#" + checkThis.id);
            const classCheckBox = $('.' + className + ' input');

            if (groupIdName.is(':checked')) {
                classCheckBox.prop('checked', true);
            } else {
                classCheckBox.prop('checked', false);
            }
            implementAllChecked();
        }

        function checkSinglePermission(groupClassName, groupID, countTotalPermission) {
            const classCheckbox = $('.' + groupClassName + ' input');
            const groupIDCheckBox = $("#" + groupID);

            // if there is any occurance where something is not selected then make selected = false
            if ($('.' + groupClassName + ' input:checked').length == countTotalPermission) {
                groupIDCheckBox.prop('checked', true);
            } else {
                groupIDCheckBox.prop('checked', false);
            }
            implementAllChecked();
        }

        function implementAllChecked() {
            const countPermissions = {{ count($all_permissions) }};
            const countPermissionGroups = {{ count($permission_groups) }};

            //  console.log((countPermissions + countPermissionGroups));
            //  console.log($('input[type="checkbox"]:checked').length);

            if ($('input[type="checkbox"]:checked').length >= (countPermissions + countPermissionGroups)) {
                $("#checkPermissionAll").prop('checked', true);
            } else {
                $("#checkPermissionAll").prop('checked', false);
            }
        }
    </script>
@endpush
