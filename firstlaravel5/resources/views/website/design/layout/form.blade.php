@extends('templates.oc_template')
@section('button_pull_right')
<button type="button" id="submit-layout" data-toggle="tooltip" title="Save" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="#" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $data->entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo (($data->name)? $data->name:''); ?>" placeholder="<?php echo $data->entry_name; ?>" id="input-name" class="form-control" />
            </div>
          </div>
          <table id="route" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $data->entry_website; ?></td>
                <td class="text-left"><?php echo $data->entry_route; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $route_row = 0; ?>
              <?php foreach ($data->layout_routes as $layout_route) { ?>
              <tr id="route-row<?php echo $route_row; ?>">
                <td class="text-left"><select name="layout_route[<?php echo $route_row; ?>][website_id]" class="form-control">
                    <?php foreach ($data->websites as $website) { ?>
                    <?php if ($website['website_id'] == $layout_route->website_id) { ?>
                    <option value="<?php echo $website['website_id']; ?>" selected="selected"><?php echo $website['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $website['website_id']; ?>"><?php echo $website['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><input type="text" name="layout_route[<?php echo $route_row; ?>][route]" value="<?php echo $layout_route->route; ?>" placeholder="<?php echo $data->entry_route; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#route-row<?php echo $route_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $route_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick="addRoute();" data-toggle="tooltip" title="<?php echo $data->button_route_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
          <table id="module" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $data->entry_module; ?></td>
                <td class="text-left"><?php echo $data->entry_position; ?></td>
                <td class="text-right"><?php echo $data->entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $module_row = 0; ?>
              <?php foreach ($data->layout_modules as $layout_module) { ?>
              <tr id="module-row<?php echo $module_row; ?>">
                <td class="text-left"><select name="layout_module[<?php echo $module_row; ?>][code]" class="form-control">
                    <?php foreach ($data->extensions as $extension) { ?>
                    <?php if (!$extension['module']) { ?>
                    <?php if ($extension['code'] == $layout_module->code) { ?>
                    <option value="<?php echo $extension['code']; ?>" selected="selected"><?php echo $extension['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $extension['code']; ?>"><?php echo $extension['name']; ?></option>
                    <?php } ?>
                    <?php } else { ?>
                    <optgroup label="<?php echo $extension['name']; ?>">
                    <?php foreach ($extension['module'] as $module) { ?>
                    <?php if ($module['code'] == $layout_module->code) { ?>
                    <option value="<?php echo $module['code']; ?>" selected="selected"><?php echo $module['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $module['code']; ?>"><?php echo $module['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                    </optgroup>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left">
                  <select name="layout_module[<?php echo $module_row; ?>][position]" class="form-control">
                    <?php if ($layout_module->position == 'content_top') { ?>
                    <option value="content_top" selected="selected"><?php echo $data->text_content_top; ?></option>
                    <?php } else { ?>
                    <option value="content_top"><?php echo $data->text_content_top; ?></option>
                    <?php } ?>
                    <?php if ($layout_module->position == 'content_bottom') { ?>
                    <option value="content_bottom" selected="selected"><?php echo $data->text_content_bottom; ?></option>
                    <?php } else { ?>
                    <option value="content_bottom"><?php echo $data->text_content_bottom; ?></option>
                    <?php } ?>
                    <?php if ($layout_module->position == 'column_left') { ?>
                    <option value="column_left" selected="selected"><?php echo $data->text_column_left; ?></option>
                    <?php } else { ?>
                    <option value="column_left"><?php echo $data->text_column_left; ?></option>
                    <?php } ?>
                    <?php if ($layout_module->position == 'column_right') { ?>
                    <option value="column_right" selected="selected"><?php echo $data->text_column_right; ?></option>
                    <?php } else { ?>
                    <option value="column_right"><?php echo $data->text_column_right; ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td class="text-right"><input type="text" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module->sort_order; ?>" placeholder="<?php echo $data->entry_sort_order; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $module_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="text-left"><button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo $data->button_module_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
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
  requestSubmitForm('submit-layout', 'form-layout', "<?php echo $data->action; ?>");
});

var route_row = <?php echo $route_row; ?>;

function addRoute() {
  html  = '<tr id="route-row' + route_row + '">';
  html += '  <td class="text-left"><select name="layout_route[' + route_row + '][website_id]" class="form-control">';
  <?php foreach ($data->websites as $website) { ?>
  html += '<option value="<?php echo $website['website_id']; ?>"><?php echo addslashes($website['name']); ?></option>';
  <?php } ?>   
  html += '  </select></td>';
  html += '  <td class="text-left"><input type="text" name="layout_route[' + route_row + '][route]" value="" placeholder="<?php echo $data->entry_route; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#route-row' + route_row + '\').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';
  
  $('#route tbody').append(html);
  
  route_row++;
}

var module_row = <?php echo $module_row; ?>;

function addModule() {
  html  = '<tr id="module-row' + module_row + '">';
  html += '  <td class="text-left"><select name="layout_module[' + module_row + '][code]" class="form-control">';
  <?php foreach ($data->extensions as $extension) { ?>    
  <?php if (!$extension['module']) { ?>
  html += '    <option value="<?php echo $extension['code']; ?>"><?php echo addslashes($extension['name']); ?></option>';
  <?php } else { ?>
  html += '    <optgroup label="<?php echo addslashes($extension['name']); ?>">';
  <?php foreach ($extension['module'] as $module) { ?>
  html += '      <option value="<?php echo $module['code']; ?>"><?php echo addslashes($module['name']); ?></option>';
  <?php } ?>
  html += '    </optgroup>';
  <?php } ?>
  <?php } ?>
    html += '  </select></td>'; 
  html += '  <td class="text-left"><select name="layout_module[' + module_row + '][position]" class="form-control">';
    html += '    <option value="content_top"><?php echo $data->text_content_top; ?></option>';
    html += '    <option value="content_bottom"><?php echo $data->text_content_bottom; ?></option>';
    html += '    <option value="column_left"><?php echo $data->text_column_left; ?></option>';
    html += '    <option value="column_right"><?php echo $data->text_column_right; ?></option>';
    html += '  </select></td>';
  html += '  <td class="text-left"><input type="text" name="layout_module[' + module_row + '][sort_order]" value="" placeholder="<?php echo $data->entry_sort_order; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + module_row + '\').remove();" data-toggle="tooltip" title="<?php echo $data->button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';
  
  $('#module tbody').append(html);
  
  module_row++;
}

</script>
@endsection