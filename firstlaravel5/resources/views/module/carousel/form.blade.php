@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-module-carousel" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-module-carousel" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo ((isset($data->name))? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-banner"><?php echo $data->entry_banner; ?></label>
            <div class="col-sm-10">
              <select name="banner_id" id="input-banner" class="form-control">
                <?php foreach ($data->banners as $banner) { ?>
                <?php if ($banner['banner_id'] == $data->banner_id) { ?>
                <option value="<?php echo $banner['banner_id']; ?>" selected="selected"><?php echo $banner['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $banner['banner_id']; ?>"><?php echo $banner['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $data->entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo ((isset($data->width))? $data->width:''); ?>" placeholder="<?php echo $data->entry_width; ?>" id="input-width" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $data->entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo ((isset($data->height))? $data->height:''); ?>" placeholder="<?php echo $data->entry_height; ?>" id="input-height" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option <?php echo (($key == $data->module_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
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
<script type="text/javascript"><!--
$(document).ready(function() {
  requestSubmitForm('submit-module-carousel', 'form-module-carousel', "<?php echo $data->action; ?>");
});
</script>
@endsection