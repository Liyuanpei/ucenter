@extends('admin.base')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
    <div class="panel-heading">用户总库
        <div class="pull-right">
            <a href="/admin/user"><i class="fa fa-user"></i> 用 户 </a> /
            用户总库
        </div>
    </div>
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <br />
            <div class="input-group custom-search-form">
                <input type="text" id="search" class="form-control search" placeholder="搜索">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/app') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <table class="table table-striped table-bordered table-hover" id="user_all" class="display" cellspacing="0" width="100%" border='0px'>
                    <thead>
                        <tr>
                            <td style="width:15px"><input class="checkbox" type="checkbox" name="id" id='checkAll'></td>
                            <td>用户名</td>
                            <td>邮箱</td>
                            <td>手机</td>
                            <td>创建时间</td>
                            <td>更新时间</td>
                            <td>状态</td>
                            <td>操作</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </form>
        </div>
        <!-- /.dataTable_wrapper -->
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel -->
</div>
</div>

@include('admin.partials.modal.role')

<script>
function choose_role(user_id) {
    $.getJSON('/admin/user/role/' + user_id, function(data) {
        if (data.code === 0) {
            data = data.data;
            var html;
            for (var i = 0; i < data.length; i++) {
                html += '<tr>';
                if (data[i].checked) {
                    html += '<td><input class="checkbox" type="checkbox" name="id" checked="checked" value=' + data[i].id + '></input></td>';
                } else {
                    html += '<td><input class="checkbox" type="checkbox" name="id" value="' + data[i].id + '"></input></td>';
                }
                html += '<td>' + data[i].title + '</td>';
                html += '<td>' + data[i].name + '</td>';
                html += '<td>' + data[i].description + '</td>';
                html += '<td>' + data[i].updated_at + '</td>';
            }
            var nTr = $("#role_index_tbody").html(html);
        }
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%' // optional
        });
        $('input').on('ifChecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
        $('input').on('ifUnchecked', function(event){
            selectOrUnselectRole(user_id, $(this).val())
        });
    });
    $("#choose_role_modal").modal('show');
}


var datatable_id = 'user_all';
var columnDefs_targets = [0, 6, 7];
var order = [5, 'desc'];
var ajax_url = '/admin/user/alllists';
var delete_url = '/admin/user/delete';
var columns = [{
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        $(nTd).html("<input type='checkbox' id='" + sData + "' class='checkbox' name='ids' value='" + sData + "'>");
                    }
                },
                {"data": "username"},
                {"data": "email"},
                {"data": "phone"},
                {"data": "created_at"},
                {"data": "updated_at"},
                {
                    "data": "status",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (sData === 1) {
                            $(nTd).html('<span class="text-success">已接入</span>');
                        } else {
                            $(nTd).html('<span class="text-danger">未接入</span>');
                        }
                    }
                },
                {
                    "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (oData.status === 1) {
                            data = "<button type='button' onclick='return choose_role(" + sData + ");' class='btn btn-outline btn-danger btn-xs'>取消接入</button>";
                        } else {
                            data = "<button type='button' onclick='return choose_role(" + sData + ");' class='btn btn-outline btn-primary btn-xs'>接入应用</button>";
                        }
                        $(nTd).html(data);
                    }
                }];
</script>
@endsection
