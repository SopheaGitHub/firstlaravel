@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-category" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        
        <form action="#" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
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
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language->language_id; ?>"><?php echo $data->entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language->language_id; ?>][title]" value="" placeholder="<?php echo $data->entry_name; ?>" id="input-title<?php echo $language->language_id; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language->language_id; ?>"><?php echo $data->entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language->language_id; ?>][description]" placeholder="<?php echo $data->entry_description; ?>" id="input-description<?php echo $language->language_id; ?>" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="category_description[<?php echo $language->language_id; ?>][meta_title]" value="" placeholder="<?php echo $data->entry_meta_title; ?>" id="input-meta-title<?php echo $language->language_id; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language->language_id; ?>][meta_description]" rows="5" placeholder="<?php echo $data->entry_meta_description; ?>" id="input-meta-description<?php echo $language->language_id; ?>" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language->language_id; ?>"><?php echo $data->entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="category_description[<?php echo $language->language_id; ?>][meta_keyword]" rows="5" placeholder="<?php echo $data->entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language->language_id; ?>" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>

            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parent">Parent</label>
                <div class="col-sm-10">
                  <input name="path" value="" placeholder="Parent" id="input-parent" class="form-control" autocomplete="off" type="text"><ul class="dropdown-menu" style="top: 35px; left: 15px; display: none;"><li data-value="0"><a href="#"> --- None --- </a></li><li data-value="35"><a href="#">Components&nbsp;&nbsp;&gt;&nbsp;&nbsp;Monitors&nbsp;&nbsp;&gt;&nbsp;&nbsp;test 1</a></li><li data-value="36"><a href="#">Components&nbsp;&nbsp;&gt;&nbsp;&nbsp;Monitors&nbsp;&nbsp;&gt;&nbsp;&nbsp;test 2</a></li><li data-value="38"><a href="#">MP3 Players&nbsp;&nbsp;&gt;&nbsp;&nbsp;test 4</a></li><li data-value="37"><a href="#">MP3 Players&nbsp;&nbsp;&gt;&nbsp;&nbsp;test 5</a></li><li data-value="39"><a href="#">MP3 Players&nbsp;&nbsp;&gt;&nbsp;&nbsp;test 6</a></li></ul>
                  <input name="parent_id" value="0" type="hidden">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="help_keyword"><?php echo $data->entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="" placeholder="<?php echo $data->entry_keyword; ?>" id="input-keyword" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Image</label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="https://demo.opencart.com/image/cache/no_image-100x100.png" alt="" title="" data-placeholder="https://demo.opencart.com/image/cache/no_image-100x100.png"></a>
                  <input name="image" value="" id="input-image" type="hidden">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="" data-original-title="Display in the top menu bar. Only works for the top parent categories.">Top</span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                    <input name="top" value="1" id="input-top" type="checkbox">
                    &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-column"><span data-toggle="tooltip" title="" data-original-title="Number of columns to use for the bottom 3 categories. Only works for the top parent categories.">Columns</span></label>
                <div class="col-sm-10">
                  <input name="column" value="1" placeholder="Columns" id="input-column" class="form-control" type="text">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $data->entry_status; ?></label>
                <div class="col-sm-10">
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
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $data->entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="" placeholder="<?php echo $data->entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>

            <div class="tab-pane" id="tab-design">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $data->entry_layout; ?></label>
                <div class="col-sm-10">
                  <select name="category_layout[0]" class="form-control">
                    <option value=""></option>
                    <option value="6">Account</option>
                    <option value="10">Affiliate</option>
                    <option value="3">Category</option>
                    <option value="7">Checkout</option>
                    <option value="12">Compare</option>
                    <option value="8">Contact</option>
                    <option value="4">Default</option>
                    <option value="1">Home</option>
                    <option value="11">Information</option>
                    <option value="5">Manufacturer</option>
                    <option value="2">Product</option>
                    <option value="13">Search</option>
                    <option value="9">Sitemap</option>
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
$(document).ready(function() {
  requestSubmitForm('submit-category', 'form-category', "<?php echo $data->action; ?>");
});
</script>
@endsection