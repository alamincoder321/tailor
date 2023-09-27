@extends('layouts.master')

@section("title", "User Access Create")
@section("bread_crum", "User Access Create")
@section("content")
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary
                            d-flex justify-content-between py-1">
                <h4 class="card-title m-0 pt-2">User Permission</h4>
                <a href="{{ route('user.create') }}" class="btn btn-info shadow-none px-3">
                    Users List
                </a>
            </div>
            <div class="card-body">

                @if (session('message'))
                <div class="alert alert-{{ session('type') }}">{{ session('message') }}</div>
                @endif

                <form action="{{ route('user.permissionStore') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center">User Name: <?= $user->name ?> | Role:
                                <?= $user->role->name ?></label>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                    <table class="table table-bordered">
                        <thead class="bg-dark">
                            <tr>
                                <th class="text-white" style="font-weight: 900;">Group Name</th>
                                <th class="text-white" style="font-weight: 900;">Permission Name</th>
                            </tr>
                        </thead>
                        <tr class="border-bottom:1px solid gray;">
                            <td colspan="2">
                                <div class="from-group" style="display: flex;align-items: center;gap: 5px;">
                                    <input style="width:15px;height:15px;" type="checkbox" onchange="selectAll(event)" id="all" {{\App\Models\User::checkAll($access) ? 'checked' : ''}} >
                                    <label for="all">All</label>
                                </div>
                            </td>
                        </tr>
                        @foreach ($groups as $group)
                        <tr>
                            <td class="group">
                                <div class="form-group" style="display: flex;align-items: center;gap: 5px;">
                                    <input style="width:15px;height:15px;" type="checkbox" id="role-{{ $group->group_name }}" value="{{ $group->group_name }}" onclick="selectGroup({{$group}})" {{\App\Models\User::checkGroupName($group->group_name, $access) ? 'checked' : ''}} />
                                    <label for="role-{{ $group->group_name }}">{{ $group->group_name }}</label>
                                </div>
                            </td>
                            <td class="role-{{ $group->group_name }}">
                                @foreach ($group->permissionArr as $item)
                                <div class="form-group" style="display: flex;align-items: center;gap: 8px;">
                                    <input style="width:15px;height:15px;" type="checkbox" name="permissions[]" onclick="singlePermission({{$group}})" id="{{ $item->permission }}" value="{{ $item->id }}" {{ in_array($item->permission, $userAccess) ? 'checked' : '' }} />
                                    <label for="{{ $item->permission }}">{{ $item->permission }}</label><br>
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-success shadow-none text-white">Save Permissions</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push("js")
<script>
    function selectAll(event) {
        if (event.target.checked) {
            $('input[type="checkbox"]').prop('checked', true);
        } else {
            $('input[type="checkbox"]').prop('checked', false);
        }
    }

    function selectGroup(group) {
        const checkbox = $('.role-'+group.group_name + ' input');
        if ($('#role-'+group.group_name).is(':checked')) {
            checkbox.prop('checked', true);
        } else {
            checkbox.prop('checked', false);
        }

        singlePermission(group)
    }

    function singlePermission(group){
        var totalCheck = $(".role-"+group.group_name+" input:checkbox:checked").length
        var totalUnCheck = $(".role-"+group.group_name+" input:checkbox").length
        
        if (totalCheck == totalUnCheck) {
            $("#role-"+group.group_name).prop("checked", true);
        }else{
            $("#role-"+group.group_name).prop("checked", false);
        }

        var totalgroupcheck = $(".group input:checkbox:checked").length
        var totalgroupuncheck = $(".group input:checkbox").length

        if(totalgroupcheck >= totalgroupuncheck){
            $("#all").prop("checked", true);
        }else{
            $("#all").prop("checked", false);
        }
    }
</script>
@endpush