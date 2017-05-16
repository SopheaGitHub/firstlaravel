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
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
                  <input type="text" name="config_meta_title" value="<?php echo (($data->config_meta_title)? $data->config_meta_title:''); ?>" placeholder="<?php echo $data->entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $data->entry_meta_tag_description; ?></label>
                <div class="col-sm-10">
                  <textarea name="config_meta_description" rows="5" placeholder="<?php echo $data->entry_meta_tag_description; ?>" id="input-meta-description" class="form-control"><?php echo (($data->config_meta_description)? $data->config_meta_description:''); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $data->entry_meta_tag_keywords; ?></label>
                <div class="col-sm-10">
                  <textarea name="config_meta_keyword" rows="5" placeholder="<?php echo $data->entry_meta_tag_keywords; ?>" id="input-meta-keyword" class="form-control"><?php echo (($data->config_meta_keyword)? $data->config_meta_keyword:''); ?></textarea>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-store">
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_website_name; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_name" value="<?php echo (($data->config_name)? $data->config_name:''); ?>" placeholder="<?php echo $data->entry_website_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-owner"><?php echo $data->entry_website_owner; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_owner" value="<?php echo (($data->config_owner)? $data->config_owner:''); ?>" placeholder="<?php echo $data->entry_website_owner; ?>" id="input-owner" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-address"><?php echo $data->entry_address; ?></label>
                  <div class="col-sm-10">
                    <textarea name="config_address" placeholder="<?php echo $data->entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo (($data->config_address)? $data->config_address:''); ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_geocode; ?>"><?php echo $data->entry_geocode; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_geocode" value="<?php echo (($data->config_geocode)? $data->config_geocode:''); ?>" placeholder="<?php echo $data->entry_geocode; ?>" id="input-geocode" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-email"><?php echo $data->entry_email; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_email" value="<?php echo (($data->config_email)? $data->config_email:''); ?>" placeholder="<?php echo $data->entry_email; ?>" id="input-email" class="form-control" />
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-telephone"><?php echo $data->entry_telephone; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_telephone" value="<?php echo (($data->config_telephone)? $data->config_telephone:''); ?>" placeholder="<?php echo $data->entry_telephone; ?>" id="input-telephone" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-fax"><?php echo $data->entry_fax; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="config_fax" value="<?php echo (($data->config_fax)? $data->config_fax:''); ?>" placeholder="<?php echo $data->entry_fax; ?>" id="input-fax" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-image"><?php echo $data->entry_image; ?></label>
                  <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $data->thumb; ?>" alt="" title="" data-placeholder="<?php echo $data->placeholder;?>" /></a>
                    <input type="hidden" name="config_image" value="<?php echo (($data->config_image)? $data->config_image:''); ?>" id="input-image" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_opening_times; ?>"><?php echo $data->entry_opening_times; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="config_open" rows="5" placeholder="<?php echo $data->entry_opening_times; ?>" id="input-open" class="form-control"><?php echo (($data->config_open)? $data->config_open:''); ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $data->title_comment; ?>"><?php echo $data->entry_comment; ?></span></label>
                  <div class="col-sm-10">
                    <textarea name="config_comment" rows="5" placeholder="<?php echo $data->entry_comment; ?>" id="input-comment" class="form-control"><?php echo (($data->config_comment)? $data->config_comment:''); ?></textarea>
                  </div>
                </div>
                
            </div>
            <div class="tab-pane" id="tab-local">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-country"><?php echo $data->entry_country; ?></label>
                <div class="col-sm-10">
                  <select name="config_country_id" id="input-country" class="form-control">
                    <?php
                      foreach ($data->countries as $country_id => $country_name) { ?>
                        <option <?php echo (($data->config_country_id)? (($data->config_country_id==$country_id)? 'selected="selected"':''):''); ?> value="<?php echo $country_id; ?>"><?php echo $country_name; ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-zone"><?php echo $data->entry_region_state; ?></label>
                <div class="col-sm-10">
                  <select name="config_zone_id" id="input-zone" class="form-control">
                    
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-language"><?php echo $data->entry_language; ?></label>
                <div class="col-sm-10">
                  <select name="config_language" id="input-language" class="form-control">
                    <?php
                      foreach ($data->languages as $language_code => $languages_name) { ?>
                        <option <?php echo (($data->config_language)? (($data->config_language==$language_code)? 'selected="selected"':''):''); ?> value="<?php echo $language_code; ?>"><?php echo $languages_name; ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $data->entry_administration_language; ?></label>
                <div class="col-sm-10">
                  <select name="config_admin_language" id="input-admin-language" class="form-control">
                    <?php
                      foreach ($data->languages as $language_code => $languages_name) { ?>
                        <option <?php echo (($data->config_admin_language)? (($data->config_admin_language==$language_code)? 'selected="selected"':''):''); ?> value="<?php echo $language_code; ?>"><?php echo $languages_name; ?></option>
                    <?php }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $data->title_currency; ?>"><?php echo $data->entry_currency; ?></span></label>
                <div class="col-sm-10">
                  <select name="config_currency" id="input-currency" class="form-control">
                    <?php
                      foreach ($data->currencies as $currency_code => $currency_title) { ?>
                        <option <?php echo (($data->config_currency)? (($data->config_currency==$currency_code)? 'selected="selected"':''):''); ?> value="<?php echo $currency_code; ?>"><?php echo $currency_title; ?></option>
                    <?php }
                    ?>
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
                    <input type="text" name="config_limit_admin" value="<?php echo (($data->config_limit_admin)? $data->config_limit_admin:''); ?>" placeholder="<?php echo $data->entry_default_lists_per_page_admin; ?>" id="input-admin-limit" class="form-control" />
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-logo"><?php echo $data->entry_website_logo; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $data->logo; ?>" alt="" title="" data-placeholder="<?php echo $data->placeholder; ?>" /></a>
                  <input type="hidden" name="config_logo" value="<?php echo (($data->config_logo)? $data->config_logo:''); ?>" id="input-logo" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-icon"><span data-toggle="tooltip" title="<?php echo $data->title_website_icon; ?>"><?php echo $data->entry_website_icon; ?></span></label>
                <div class="col-sm-10"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $data->icon; ?>" alt="" title="" data-placeholder="<?php echo $data->placeholder; ?>" /></a>
                  <input type="hidden" name="config_icon" value="<?php echo (($data->config_icon)? $data->config_icon:''); ?>" id="input-icon" />
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
$('select[name=\'config_country_id\']').on('change', function() {
  $.ajax({
    url: '<?php echo url("/"); ?>/settings/country/' + this.value,
    dataType: 'json',
    beforeSend: function() {
      $('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
    },
    complete: function() {
      $('.fa-spin').remove();
    },
    success: function(json) {
      html = '<option value=""><?php echo $data->text_select; ?></option>';

      if (json['zone'] && json['zone'] != '') {
        for (i = 0; i < json['zone'].length; i++) {
                html += '<option value="' + json['zone'][i]['zone_id'] + '"';

          if (json['zone'][i]['zone_id'] == '<?php echo $data->config_zone_id; ?>') {
                  html += ' selected="selected"';
          }

          html += '>' + json['zone'][i]['name'] + '</option>';
        }
      } else {
        html += '<option value="0" selected="selected"><?php echo $data->text_none; ?></option>';
      }
      $('select[name=\'config_zone_id\']').html(html);
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

$('select[name=\'config_country_id\']').trigger('change');
//--></script>
<script type="text/javascript">
$(document).ready(function() {
  requestSubmitForm('submit-setting', 'form-setting', "<?php echo $data->action; ?>");
});
</script>
@endsection