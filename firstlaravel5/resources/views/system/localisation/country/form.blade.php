@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-country" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-country" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-6">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-iso-code-2"><?php echo $data->entry_iso_code_2; ?></label>
            <div class="col-sm-6">
              <input type="text" name="iso_code_2" value="<?php echo (($data->iso_code_2)? $data->iso_code_2:''); ?>" placeholder="<?php echo $data->entry_iso_code_2; ?>" id="input-iso-code-2" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-iso-code-3"><?php echo $data->entry_iso_code_3; ?></label>
            <div class="col-sm-6">
              <input type="text" name="iso_code_3" value="<?php echo (($data->iso_code_3)? $data->iso_code_3:''); ?>" placeholder="<?php echo $data->entry_iso_code_3; ?>" id="input-iso-code-3" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label" for="input-address-format"><span data-toggle="tooltip" data-html="true" title="<?php echo $data->title_address_format; ?>"><?php echo $data->entry_address_format; ?></span></label>
            <div class="col-sm-6">
              <textarea name="address_format" rows="5" placeholder="<?php echo $data->entry_address_format; ?>" id="input-address-format" class="form-control"><?php echo (($data->address_format)? $data->address_format:''); ?></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_postcode_required; ?></label>
            <div class="col-sm-6">
              <label class="radio-inline">
                <input type="radio" name="postcode_required" value="1" <?php echo (($data->postcode_required=='1')? 'checked="checked"':''); ?> />
                Yes                              
              </label>
              <label class="radio-inline">
                <input type="radio" name="postcode_required" value="0" <?php echo (($data->postcode_required=='0')? 'checked="checked"':''); ?> />
                No                             
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-6">
              <select name="status" id="input-status" class="form-control">
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option <?php echo (($key == $data->country_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
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
  requestSubmitForm('submit-country', 'form-country', "<?php echo $data->action; ?>");
});
</script>
@endsection