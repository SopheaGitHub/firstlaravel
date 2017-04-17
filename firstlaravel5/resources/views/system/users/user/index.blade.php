@extends('templates.oc_template')
@section('button_pull_right')
<a href="<?php echo $data->add_user; ?>" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i></a>
<button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure?') ? $('#form-user-group').submit() : false;"><i class="fa fa-trash-o"></i></button>
@endsection
@section('content')
  <div class="container-fluid">
            <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> User List</h3>
      </div>
      <div class="panel-body">
        <form action="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user/delete&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt" method="post" enctype="multipart/form-data" id="form-user">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">                    <a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt&amp;sort=username&amp;order=DESC" class="asc">Username</a>
                    </td>
                  <td class="text-left">                    <a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt&amp;sort=status&amp;order=DESC">Status</a>
                    </td>
                  <td class="text-left">                    <a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt&amp;sort=date_added&amp;order=DESC">Date Added</a>
                    </td>
                  <td class="text-right">Action</td>
                </tr>
              </thead>
              <tbody>
                                                <tr>
                  <td class="text-center">                    <input type="checkbox" name="selected[]" value="2" />
                    </td>
                  <td class="text-left">abc</td>
                  <td class="text-left">Enabled</td>
                  <td class="text-left">17/12/2016</td>
                  <td class="text-right"><a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user/edit&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt&amp;user_id=2" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                                <tr>
                  <td class="text-center">                    <input type="checkbox" name="selected[]" value="1" />
                    </td>
                  <td class="text-left">admin</td>
                  <td class="text-left">Enabled</td>
                  <td class="text-left">01/12/2016</td>
                  <td class="text-right"><a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=user/user/edit&amp;token=KSg9thB2RKJJmlWcdKufIcXK6eva2BTt&amp;user_id=1" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                                              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"></div>
          <div class="col-sm-6 text-right">Showing 1 to 2 of 2 (1 Pages)</div>
        </div>
      </div>
    </div>
  </div>
@endsection