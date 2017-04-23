@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-geo-zone" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-geo-zone" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-6">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-4 control-label" for="input-description"><?php echo $data->entry_description; ?></label>
            <div class="col-sm-6">
              <input type="text" name="description" value="<?php echo (($data->description)? $data->description:''); ?>" placeholder="<?php echo $data->entry_description; ?>" id="input-description" class="form-control" />
            </div>
          </div>

          <table id="zone-to-geo-zone" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left">Country</td>
                <td class="text-left">Zone</td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $zone_to_geo_zone_row = 0; ?>
              <?php foreach ($data->zone_to_geo_zones as $zone_to_geo_zone) { ?>
              <tr id="zone_to_geo_zone_<?php echo $zone_to_geo_zone_row; ?>">
                <td class="text-left">
                  <select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('<?php echo $data->load_zone_action; ?>/' + this.value + '/0');" class="form-control">
                    <?php foreach ($data->countries as $country_id => $country_name) { ?>
                      <?php  if ($country_id == $zone_to_geo_zone->country_id) { ?>
                        <option value="<?php echo $country_id; ?>" selected="selected"><?php echo $country_name; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $country_id; ?>"><?php echo $country_name; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>" class="form-control">
                  </select></td>
                <td class="text-left"><button type="button" onclick="$('#zone_to_geo_zone_<?php echo $zone_to_geo_zone_row; ?>').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $zone_to_geo_zone_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"><input type="hidden" name="zone_to_geo_zone_row" id="zone_to_geo_zone_row" value="<?php echo ((count($data->zone_to_geo_zones) > 0)? count($data->zone_to_geo_zones):'0' ) ?>" /></td>
                <td class="text-left"><button type="button" onclick="addGeoZone();" data-toggle="tooltip" title="Add Geo Zone" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
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
  <?php
    $zone_to_geo_zone_row=0;
    foreach ($data->zone_to_geo_zones as $zone_to_geo_zone) { ?>
      $('#zone<?php echo $zone_to_geo_zone_row; ?>').load('<?php echo $data->load_zone_action; ?>/<?php echo $zone_to_geo_zone->country_id; ?>/<?php echo $zone_to_geo_zone->zone_id; ?>');
  <?php $zone_to_geo_zone_row++; }
  ?>
</script>

<script type="text/javascript">
$(document).ready(function() {
  requestSubmitForm('submit-geo-zone', 'form-geo-zone', "<?php echo $data->action; ?>");
});

var zone_to_geo_zone_row = $('#zone_to_geo_zone_row').val();
function addGeoZone() {
  var requestAction = "<?php echo $data->load_zone_action; ?>";
  var html = '';
  html += '<tr id="zone_to_geo_zone_'+zone_to_geo_zone_row+'">';
    html += '<td>';
      html += '<select name="zone_to_geo_zone['+zone_to_geo_zone_row+'][country_id]" onchange="$(\'#zone' + zone_to_geo_zone_row + '\').load(\''+requestAction+'/\' + this.value + \'/0\');" id="country'+zone_to_geo_zone_row+'" class="form-control">';
      <?php
        if(count($data->countries) > 0) {
          foreach ($data->countries as $country_id => $country_name) { ?>
            html += '<option value="<?php echo $country_id; ?>"><?php echo addslashes($country_name); ?></option>';
          <?php  }
        }
      ?>
      html += '</select>';
    html += '</td>';
    html += '<td>';
      html += '<select name="zone_to_geo_zone['+zone_to_geo_zone_row+'][zone_id]" id="zone'+zone_to_geo_zone_row+'" class="form-control">';
      html += '</select>'
    html += '</td>';
    html += '<td class="text-left"><button type="button" onclick="$(\'#zone_to_geo_zone_'+zone_to_geo_zone_row+'\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>'
  $('#zone-to-geo-zone tbody').append(html);
  // loadZone(requestAction, $('#country'+zone_to_geo_zone_row).val(), 'zone'+zone_to_geo_zone_row);
  $('#zone' + zone_to_geo_zone_row).load(requestAction+ '/' + $('#country' + zone_to_geo_zone_row).val()+'/0');
  zone_to_geo_zone_row++;
}

</script>
@endsection