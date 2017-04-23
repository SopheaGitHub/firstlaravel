@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-setting" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $data->tap_general; ?></a></li>
            <li><a href="#tab-store" data-toggle="tab"><?php echo $data->tap_website; ?></a></li>
            <li><a href="#tab-local" data-toggle="tab"><?php echo $data->tap_localisation; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $data->tap_option; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $data->tap_image; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $data->entry_meta_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="config_meta_title" value="" placeholder="<?php echo $data->entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $data->entry_meta_tag_description; ?></label>
                <div class="col-sm-10">
                  <textarea name="config_meta_description" rows="5" placeholder="<?php echo $data->entry_meta_tag_description; ?>" id="input-meta-description" class="form-control"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $data->entry_meta_tag_keywords; ?></label>
                <div class="col-sm-10">
                  <textarea name="config_meta_keyword" rows="5" placeholder="<?php echo $data->entry_meta_tag_keywords; ?>" id="input-meta-keyword" class="form-control"></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-store">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_website_name; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_name" value="" placeholder="<?php echo $data->entry_website_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-owner"><?php echo $data->entry_website_owner; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_owner" value="" placeholder="<?php echo $data->entry_website_owner; ?>" id="input-owner" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-address"><?php echo $data->entry_address; ?></label>
                  <div class="col-sm-10">
                    <textarea name="config_address" placeholder="<?php echo $data->entry_address; ?>" rows="5" id="input-address" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_geocode; ?>"><?php echo $data->entry_geocode; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_geocode" value="" placeholder="<?php echo $data->entry_geocode; ?>" id="input-geocode" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-email"><?php echo $data->entry_email; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_email" value="" placeholder="<?php echo $data->entry_email; ?>" id="input-email" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-telephone"><?php echo $data->entry_telephone; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_telephone" value="" placeholder="<?php echo $data->entry_telephone; ?>" id="input-telephone" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-fax"><?php echo $data->entry_fax; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_fax" value="" placeholder="<?php echo $data->entry_fax; ?>" id="input-fax" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-image"><?php echo $data->entry_image; ?></label>
                  <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="http://localhost/projects/koktepweb/upload/image/cache/no_image-100x100.png" alt="" title="" data-placeholder="http://localhost/projects/koktepweb/upload/image/cache/no_image-100x100.png" /></a>
                    <input type="hidden" name="config_image" value="" id="input-image" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_opening_times; ?>"><?php echo $data->entry_opening_times; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="config_open" rows="5" placeholder="<?php echo $data->entry_opening_times; ?>" id="input-open" class="form-control"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_comment; ?>"><?php echo $data->entry_comment; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="config_comment" rows="5" placeholder="<?php echo $data->entry_comment; ?>" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
                
            </div>
            <div class="tab-pane" id="tab-local">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-country"><?php echo $data->entry_country; ?></label>
                <div class="col-sm-10">
                  <select name="config_country_id" id="input-country" class="form-control">
                    <option value="244">Aaland Islands</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-zone"><?php echo $data->entry_region_state; ?></label>
                <div class="col-sm-10">
                  <select name="config_zone_id" id="input-zone" class="form-control">
                    <option></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-language"><?php echo $data->entry_language; ?></label>
                <div class="col-sm-10">
                  <select name="config_language" id="input-language" class="form-control">
                    <option value="kh" selected="selected">Khmer</option>
                    <option value="en">English</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $data->entry_administration_language; ?></label>
                <div class="col-sm-10">
                  <select name="config_admin_language" id="input-admin-language" class="form-control">
                    <option value="kh">Khmer</option>
                    <option value="en" selected="selected">English</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $data->title_currency; ?>"><?php echo $data->entry_currency; ?></span></label>
                <div class="col-sm-10">
                  <select name="config_currency" id="input-currency" class="form-control">
                    <option value="EUR">Euro</option>
                    <option value="GBP">Pound Sterling</option>
                    <option value="USD" selected="selected">US Dollar</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-option">
              <fieldset>
                <legend><?php echo $data->fieldset_list; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $data->title_default_lists_per_page_admin; ?>"><?php echo $data->entry_default_lists_per_page_admin; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_limit_admin" value="10" placeholder="<?php echo $data->entry_default_lists_per_page_admin; ?>" id="input-admin-limit" class="form-control" />
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-logo"><?php echo $data->entry_website_logo; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="http://localhost/projects/koktepweb/upload/image/cache/catalog/flamingos_logo-100x100.png" alt="" title="" data-placeholder="http://localhost/projects/koktepweb/upload/image/cache/no_image-100x100.png" /></a>
                  <input type="hidden" name="config_logo" value="catalog/flamingos_logo.png" id="input-logo" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-icon"><span data-toggle="tooltip" title="<?php echo $data->title_website_icon; ?>"><?php echo $data->entry_website_icon; ?></span></label>
                <div class="col-sm-10"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="http://localhost/projects/koktepweb/upload/image/cache/catalog/flamingos_logo-100x100.png" alt="" title="" data-placeholder="http://localhost/projects/koktepweb/upload/image/cache/no_image-100x100.png" /></a>
                  <input type="hidden" name="config_icon" value="catalog/flamingos_logo.png" id="input-icon" />
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
<script type="text/javascript">
$(document).ready(function() {
  requestSubmitForm('submit-setting', 'form-setting', "<?php echo $data->action; ?>");
});
</script>
@endsection