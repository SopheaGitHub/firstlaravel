<form action="<?php echo $data->delete; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
  	<div class="table-responsive">
	    <table class="table table-bordered table-hover">
	      <thead>
	        <tr>
	          <td class="text-left"><?php echo $data->column_name; ?></td>
	          <td class="text-right"><?php echo $data->column_action; ?></td>
	        </tr>
	      </thead>
	      <tbody>
	        <?php if ($data->extensions) { ?>
	        <?php foreach ($data->extensions as $extension) { ?>
	        <tr>
	          <td><?php echo $extension['name']; ?></td>
	          <td class="text-right"><?php if (!$extension['installed']) { ?>
	            <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $data->button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
	            <?php } else { ?>
	            <a onclick="confirm('<?php echo $data->text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $data->button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
	            <?php } ?>
	            <?php if ($extension['installed']) { ?>
	            <a href="<?php echo $extension['create']; ?>" data-toggle="tooltip" title="<?php echo $data->button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
	            <?php } else { ?>
	            <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
	            <?php } ?></td>
	        </tr>
	        <?php foreach ($extension['module'] as $module) { ?>
	        <tr>
	          <td class="text-left"><?php echo $module['name']; ?></td>
	          <td class="text-right"><a onclick="confirm('<?php echo $data->text_confirm; ?>') ? location.href='<?php echo $module['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $data->button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $data->button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
	        </tr>
	        <?php } ?>
	        <?php } ?>
	        <?php } else { ?>
	        <tr>
	          <td colspan="4">There is no data!</td>
	        </tr>
	        <?php } ?>
	      </tbody>
	    </table>
  	</div>
</form>