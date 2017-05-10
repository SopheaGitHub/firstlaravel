@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-module-banner" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-module-banner" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name">Module Name</label>
            <div class="col-sm-10">
              <input type="text" name="name" value="" placeholder="Module Name" id="input-name" class="form-control">
                          </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-banner">Banner</label>
            <div class="col-sm-10">
              <select name="banner_id" id="input-banner" class="form-control">
                                                <option value="9">Drawing</option>
                                                                <option value="7">Home Page Slideshow</option>
                                                                <option value="6">HP Products</option>
                                                                <option value="8">Manufacturers</option>
                                                                <option value="10">Painting</option>
                                                                <option value="11">Photography</option>
                                              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width">Width</label>
            <div class="col-sm-10">
              <input type="text" name="width" value="" placeholder="Width" id="input-width" class="form-control">
                          </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height">Height</label>
            <div class="col-sm-10">
              <input type="text" name="height" value="" placeholder="Height" id="input-height" class="form-control">
                          </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status">Status</label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                                <option value="1">Enabled</option>
                <option value="0" selected="selected">Disabled</option>
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
  requestSubmitForm('submit-category', 'form-module-banner', "<?php echo $data->action; ?>");
});
</script>
@endsection