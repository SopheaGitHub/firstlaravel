@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-information" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        
        <form action="#" method="post" enctype="multipart/form-data" id="form-information" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
            <li><a href="#tab-data" data-toggle="tab">Data</a></li>
            <li><a href="#tab-design" data-toggle="tab">Design</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($data->languages as $language) { ?>
                <li><a href="#language<?php echo $language->language_id; ?>" data-toggle="tab"><img src="<?php echo url('/images/flags/'.$language->image); ?>" title="<?php echo $language->name; ?>" /> <?php echo $language->name; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($data->languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language->language_id; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language->language_id; ?>"><?php echo $data->entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="information_description[<?php echo $language->language_id; ?>][title]" value="<?php echo $data->information_description[$language->language_id]['title']; ?>" placeholder="<?php echo $data->entry_title; ?>" id="input-title<?php echo $language->language_id; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language->language_id; ?>"><?php echo $data->entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="information_description[<?php echo $language->language_id; ?>][description]" placeholder="<?php echo $data->entry_description; ?>" id="input-description<?php echo $language->language_id; ?>" class="text_summernote form-control"><?php echo $data->information_description[$language->language_id]['description']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="information_description[<?php echo $language->language_id; ?>][meta_title]" value="<?php echo $data->information_description[$language->language_id]['meta_title']; ?>" placeholder="<?php echo $data->entry_meta_title; ?>" id="input-meta-title<?php echo $language->language_id; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="information_description[<?php echo $language->language_id; ?>][meta_description]" rows="5" placeholder="<?php echo $data->entry_meta_description; ?>" id="input-meta-description<?php echo $language->language_id; ?>" class="form-control"><?php echo $data->information_description[$language->language_id]['meta_description']; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="information_description[<?php echo $language->language_id; ?>][meta_keyword]" rows="5" placeholder="<?php echo $data->entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language->language_id; ?>" class="form-control"><?php echo $data->information_description[$language->language_id]['meta_description']; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>

            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $data->title_keyword; ?>"><?php echo $data->entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo ((isset($data->keyword))? $data->keyword:''); ?>" placeholder="<?php echo $data->entry_keyword; ?>" id="input-keyword" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-bottom"><span data-toggle="tooltip" title="<?php echo $data->title_bottom; ?>"><?php echo $data->entry_bottom; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($data->bottom=='1') { ?>
                      <input type="checkbox" name="bottom" value="1" checked="checked" id="input-bottom" />
                      <?php } else { ?>
                      <input type="checkbox" name="bottom" value="1" id="input-bottom" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $data->entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php
                      foreach ($data->status as $key => $status) { ?>
                        <option <?php echo (($key == $data->information_status)? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $status; ?></option>
                      <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $data->entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo ((isset($data->sort_order))? $data->sort_order:''); ?>" placeholder="<?php echo $data->entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>


            <div class="tab-pane" id="tab-design">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $data->entry_layout; ?></label>
                <div class="col-sm-10">
                  <select name="information_layout[0]" class="form-control">
                    <option value="0"></option>
                    <?php
                      foreach ($data->layouts as $layout_id => $layout_name) { ?>
                        <option <?php echo ((count($data->information_layout) > 0)? (($layout_id == $data->information_layout[0]->layout_id)? 'selected="selected"':''):''); ?> value="<?php echo $layout_id; ?>"><?php echo $layout_name; ?></option>
                    <?php  }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            
          </div>
        </form>

      </div>
    </div>
  </div>
@endsection
@section('script')
<script type="text/javascript"><!--
<?php foreach ($data->languages as $language) { ?>
$('#input-description<?php echo $language->language_id; ?>').summernote({
  height: 300
});
<?php } ?>
//--></script>
<script type="text/javascript">
$('#language a:first').tab('show');
</script>
<script type="text/javascript">
$(document).on('mouseover', '#submit-information', function(e) {
  <?php foreach ($data->languages as $language) { ?>
    $('#input-description<?php echo $language->language_id; ?>').val($('#input-description<?php echo $language->language_id; ?>').code());
  <?php } ?>
});
$(document).ready(function() {
  requestSubmitForm('submit-information', 'form-information', "<?php echo $data->action; ?>");
});
</script>
@endsection