@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-usergroup" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

        <?php 
          if (count($errors) > 0) { ?>
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br><br>
              <ul>
                <?php
                  foreach ($errors->all() as $error) { ?>
                    <li><?php echo $error; ?></li>
                  <?php }
                ?>
              </ul>
            </div>
          <?php }
        ?>

        <p id="message"></p>
        <form action="#" method="post" enctype="multipart/form-data" id="form-user-group" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $data->entry_access; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 300px; overflow: auto;">
                <?php foreach ($data->permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $data->access)) { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked="checked" />
                    <?php echo $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" />
                    <?php echo $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $data->text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $data->text_unselect_all; ?></a></div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $(document).on('click', '#submit-usergroup', function(e) {
    e.preventDefault();
    var postDatas = new FormData($("form#form-user-group")[0]);
    $.ajax({
      url: "<?php echo $data->action; ?>",
      type: "POST",
      data: postDatas,
      dataType: "json",
      async: false,
      beforeSend: function() {
        console.log('beforeSend');
        $('#message').html('Loading ...').show();
      },
      complete: function() {
        console.log('completed');
      },
      success: function(data) {
        var msg = '';
        // if vaildate error
        if(data.error==1) {
          msg += '<div class="alert alert-warning" id="warning">';
          msg += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
          msg += '<b><i class="fa fa-times"></i> '+data.msg+' :</b><br />';
          if(data.validatormsg) {
            $.each(data.validatormsg, function(index, value) {
              msg += '- '+value+'<br />';
            });
          }
          msg += '</div>';
        }

        // if success
        if(data.success==1) {
          msg += '<div class="alert alert-success" id="success">';
          msg += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
          msg += '<b><i class="fa fa-check-circle"></i> '+data.msg+'</b><br />';
          msg += '</div>';
          $("form#form-user-group")[0].reset();
        }

        $('#message').html(msg).show();
      },
      error: function(error) {
        $('#message').html('<div class="alert alert-danger" id="error"><button type="button" class="close" data-dismiss="alert">&times;</button><b><i class="fa fa-times"></i> Something wrong, Please alert to developer.</b></div>').show();
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });
});
</script>
@endsection