@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-currency" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-currency" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-title"><?php echo $data->entry_title; ?></label>
            <div class="col-sm-6">
              <input type="text" name="title" value="<?php echo (($data->title)? $data->title:''); ?>" placeholder="<?php echo $data->entry_title; ?>" id="input-title" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-code"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo $data->title_code; ?>"><?php echo $data->entry_code; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="code" value="<?php echo (($data->code)? $data->code:''); ?>" placeholder="<?php echo $data->entry_code; ?>" id="input-code" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-symbol-left"><?php echo $data->entry_symbol_left; ?></label>
            <div class="col-sm-6">
              <input type="text" name="symbol_left" value="<?php echo (($data->symbol_left)? $data->symbol_left:''); ?>" placeholder="<?php echo $data->entry_symbol_left; ?>" id="input-symbol-left" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-symbol-right"><?php echo $data->entry_symbol_right; ?></label>
            <div class="col-sm-6">
              <input type="text" name="symbol_right" value="<?php echo (($data->symbol_right)? $data->symbol_right:''); ?>" placeholder="<?php echo $data->entry_symbol_right; ?>" id="input-symbol-right" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-decimal-place"><?php echo $data->entry_decimal_places; ?></label>
            <div class="col-sm-6">
              <input type="text" name="decimal_place" value="<?php echo (($data->decimal_place)? $data->decimal_place:''); ?>" placeholder="<?php echo $data->entry_decimal_places; ?>" id="input-decimal-place" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-value"><span data-toggle="tooltip" title="<?php echo $data->title_value; ?>"><?php echo $data->entry_value; ?></span></label>
            <div class="col-sm-6">
              <input type="text" name="value" value="<?php echo (($data->value)? $data->value:''); ?>" placeholder="<?php echo $data->entry_value; ?>" id="input-value" class="form-control" />
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-6">
              <select name="status" id="input-status" class="form-control" />
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option <?php echo (($key == $data->currency_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
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
  requestSubmitForm('submit-currency', 'form-currency', "<?php echo $data->action; ?>");
});
</script>
@endsection