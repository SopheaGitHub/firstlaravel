<form action="#" method="post" enctype="multipart/form-data" id="form-user-group">
  	<div class="table-responsive">
    	<table class="table table-bordered table-hover">
      		<thead>
        		<tr>
        			<td style="width: 1px;" class="text-center">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'id.title') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_title; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_title; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_title; ?>" class="order"><?php echo $data->column_title; ?></a>
                <?php } ?>
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'i.sort_order') { ?>
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
      				if(count($data->all_information) > 0) {
      					foreach ($data->all_information as $information) { ?>
      						<tr>
              			<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $information->information_id; ?>" /></td>
              			<td class="text-left"><?php echo $information->title; ?></td>
              			<td class="text-left"><?php echo $information->sort_order; ?></td>
              			<td class="text-right"><a href="<?php echo $data->edit_information; ?>/<?php echo $information->information_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
  	<div class="col-sm-6 text-left" id="render-information"><?php echo $data->all_information->render(); ?></div>
  	<div class="col-sm-6 text-right">
  		<?php
        $start = ($data->all_information->currentPage() * $data->all_information->perPage()) - $data->all_information->perPage() + 1;
        $stop = $data->all_information->currentPage() * $data->all_information->perPage();
        if($stop > $data->all_information->total()){
          $stop = ( $start + $data->all_information->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->all_information->total(); ?> &nbsp;&nbsp; (<?php echo $data->all_information->currentPage(); ?> Pages)
	</div>
</div>