@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-user" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
<a href="<?php echo $data->go_back; ?>" data-toggle="tooltip" title="Go Back" class="btn btn-danger"><i class="fa fa-backward" aria-hidden="true"></i>
</a>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $data->titlelist; ?></h3>
      </div>
      <div class="panel-body">
        <p id="message"></p>
        <form action="#" method="POST" enctype="multipart/form-data" id="form-user" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="form-group required">
            <label class="col-sm-4 control-label"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" name="name" value="<?php echo (($data->name)? $data->name:''); ?>">
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label"><?php echo $data->entry_email_address; ?></label>
            <div class="col-sm-6">
              <input type="email" class="form-control" name="email" value="<?php echo (($data->email)? $data->email:''); ?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_user_group_name; ?></label>
            <div class="col-sm-6">
              <select name="user_group_id" id="input-user-group-id" class="form-control">
                <?php
                  foreach ($data->usergroups as $key => $usergroup) { ?>
                    <option <?php echo (($key==$data->user_group_id)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $usergroup; ?></option>
                  <?php }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-6">
              <select name="status" id="input-status" class="form-control">
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option <?php echo (($key==$data->user_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
                  <?php }
                ?>
              </select>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  requestSubmitForm('submit-user', 'form-user', "<?php echo $data->action; ?>");
});
</script>
@endsection