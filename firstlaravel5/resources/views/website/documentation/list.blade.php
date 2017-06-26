<form action="<?php echo $data->action_delete; ?>" method="post" enctype="multipart/form-data" id="form-documentation">
  	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" />
    <div class="table-responsive">
    	<table class="table table-bordered table-hover">
      		<thead>
        		<tr>
        			<td style="width: 1px;" class="text-center">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'name') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_name; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_name; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_name; ?>" class="order"><?php echo $data->column_name; ?></a>
                <?php } ?>
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'sort_order') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_sort_order; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_sort_order; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_sort_order; ?>" class="order"><?php echo $data->column_sort_order; ?></a>
                <?php } ?>
              </td>
        			<td class="text-right"><?php echo $data->column_action; ?></td>
       			</tr>
      		</thead>
      		<tbody>
      			<?php
      				if(count($data->documentation) > 0) {
      					foreach ($data->documentation as $documentation) { ?>
      						<tr>
              			<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $documentation->documentation_id; ?>" /></td>
              			<td class="text-left"><?php echo $documentation->name; ?></td>
              			<td class="text-left"><?php echo $documentation->sort_order; ?></td>
              			<td class="text-right"><a href="<?php echo $data->edit_documentation; ?>/<?php echo $documentation->documentation_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
            		  </tr>
      					<?php } 
      				} else { ?>
  						<tr>
  							<td colspan="4">There is no data!</td>
  						</tr>
  					<?php }
      			?>
            </tbody>
    	</table>
  	</div>
</form>
<div class="row">
  	<div class="col-sm-6 text-left" id="render-documentation"><?php echo $data->documentation->render(); ?></div>
  	<div class="col-sm-6 text-right">
  		<?php
        $start = ($data->documentation->currentPage() * $data->documentation->perPage()) - $data->documentation->perPage() + 1;
        $stop = $data->documentation->currentPage() * $data->documentation->perPage();
        if($stop > $data->documentation->total()){
          $stop = ( $start + $data->documentation->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->documentation->total(); ?> &nbsp;&nbsp; (<?php echo $data->documentation->currentPage(); ?> Pages)
	</div>
</div>