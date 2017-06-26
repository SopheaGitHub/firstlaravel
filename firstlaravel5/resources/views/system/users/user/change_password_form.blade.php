@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-user" data-toggle="tooltip" title="Change" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
            <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $data->title_password; ?>"><?php echo $data->entry_new_password; ?></span></label>
            <div class="col-sm-6">
              <input type="password" placeholder="<?php echo $data->entry_new_password; ?>" class="form-control" name="new_password">
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label"><?php echo $data->entry_confirm_new_password; ?></label>
            <div class="col-sm-6">
              <input type="password" placeholder="<?php echo $data->entry_confirm_new_password; ?>" class="form-control" name="new_password_confirmation">
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