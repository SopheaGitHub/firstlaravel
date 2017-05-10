@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-banner" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $data->entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php
                  foreach ($data->status as $key => $status) { ?>
                    <option <?php echo (($key == $data->banner_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
                  <?php }
                ?>
              </select>
            </div>
          </div>
          <table id="images" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $data->entry_title; ?></td>
                <td class="text-left"><?php echo $data->entry_link; ?></td>
                <td class="text-left"><?php echo $data->entry_image; ?></td>
                <td class="text-right"><?php echo $data->entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $image_row = 0; ?>
              <?php foreach ($data->banner_images as $banner_image) { ?>
              <tr id="image-row<?php echo $image_row; ?>">
                <td class="text-left">
                  <?php foreach ($data->languages as $language) { ?>
                    <div class="input-group pull-left"><span class="input-group-addon"><img src="<?php echo url('/images/flags/'.$language->image); ?>" title="<?php echo $language->name; ?>" /> </span>
                      <input type="text" name="banner_image[<?php echo $image_row; ?>][banner_image_description][<?php echo $language->language_id; ?>][title]" value="<?php echo isset($banner_image['banner_image_description'][$language->language_id]) ? $banner_image['banner_image_description'][$language->language_id]['title'] : ''; ?>" placeholder="<?php echo $data->entry_title; ?>" class="form-control" />
                    </div>
                  <?php } ?>
                </td>
                <td class="text-left" style="width: 30%;"><input type="text" name="banner_image[<?php echo $image_row; ?>][link]" value="<?php echo $banner_image['link']; ?>" placeholder="<?php echo $data->entry_link; ?>" class="form-control" /></td>
                <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $banner_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $data->placeholder; ?>" /></a>
                  <input type="hidden" name="banner_image[<?php echo $image_row; ?>][image]" value="<?php echo $banner_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
                </td>
                <td class="text-right" style="width: 10%;"><input type="text" name="banner_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $banner_image['sort_order']; ?>" placeholder="<?php echo $data->entry_sort_order; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>, .tooltip').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $image_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $data->button_banner_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>

      </div>
    </div>
  </div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  requestSubmitForm('submit-banner', 'form-banner', "<?php echo $data->action; ?>");
});

var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left">';
  <?php foreach ($data->languages as $language) { ?>
  html += '    <div class="input-group">';
  html += '      <span class="input-group-addon"><img src="<?php echo url("/images/flags/".$language->image); ?>" title="<?php echo $language->name; ?>" /></span><input type="text" name="banner_image[' + image_row + '][banner_image_description][<?php echo $language->language_id; ?>][title]" value="" placeholder="<?php echo $data->entry_title; ?>" class="form-control" />';
    html += '    </div>';
  <?php } ?>
  html += '  </td>';  
  html += '  <td class="text-left" style="width: 30%;"><input type="text" name="banner_image[' + image_row + '][link]" value="" placeholder="<?php echo $data->entry_link; ?>" class="form-control" /></td>'; 
  html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $data->placeholder; ?>" alt="" title="" data-placeholder="<?php echo $data->placeholder; ?>" /></a><input type="hidden" name="banner_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right" style="width: 10%;"><input type="text" name="banner_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $data->entry_sort_order; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';
  
  $('#images tbody').append(html);
  
  image_row++;
}
</script>
@endsection