@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-language" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="https://demo.opencart.com/admin/index.php?route=localisation/language/add&amp;token=285421a03b1f8bb52daaf925a9e958cf" method="post" enctype="multipart/form-data" id="form-language" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-6">
              <input type="text" name="name" value="" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-code"><span data-toggle="tooltip" title="<?php echo $data->title_code; ?>"><?php echo $data->entry_code; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="code" value="" placeholder="<?php echo $data->entry_code; ?>" id="input-code" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label required" for="input-locale"><span data-toggle="tooltip" title="<?php echo $data->title_locale; ?>"><?php echo $data->entry_locale; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="locale" value="" placeholder="<?php echo $data->entry_locale; ?>" id="input-locale" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-image"><span data-toggle="tooltip" title="<?php echo $data->title_image; ?>"><?php echo $data->entry_image; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="image" value="" placeholder="<?php echo $data->entry_image; ?>" id="input-image" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-directory"><span data-toggle="tooltip" title="<?php echo $data->title_directory; ?>"><?php echo $data->entry_directory; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="directory" value="" placeholder="<?php echo $data->entry_directory; ?>" id="input-directory" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-filename"><span data-toggle="tooltip" title="<?php echo $data->title_filename; ?>"><?php echo $data->entry_filename; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="filename" value="" placeholder="<?php echo $data->entry_filename; ?>" id="input-filename" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-6">
              <select name="status" id="input-status" class="form-control" />
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $status; ?></option>
                  <?php }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-sort-order"><?php echo $data->entry_sort_order; ?></label>
            <div class="col-sm-6">
              <input type="text" name="sort_order" value="" placeholder="<?php echo $data->entry_sort_order; ?>" id="input-sort-order" class="form-control" />
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
  requestSubmitForm('submit-language', 'form-language', "<?php echo $data->action; ?>");
});
</script>
@endsection