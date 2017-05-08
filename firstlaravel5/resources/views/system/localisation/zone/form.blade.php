@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-zone" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-zone" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-6">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-code"><?php echo $data->entry_code; ?></label>
            <div class="col-sm-6">
              <input type="text" name="code" value="<?php echo (($data->code)? $data->code:''); ?>" placeholder="<?php echo $data->entry_code; ?>" id="input-code" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-country"><?php echo $data->entry_country; ?></label>
            <div class="col-sm-6">
              <select name="country_id" id="input-country" class="form-control">
                <?php
                  foreach ($data->countries as $key => $country) { ?>
                    <option <?php echo (($key == $data->country_id)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $country; ?></option>
                <?php  }
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
                    <option <?php echo (($key == $data->zone_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
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
  requestSubmitForm('submit-zone', 'form-zone', "<?php echo $data->action; ?>");
});
</script>
@endsection