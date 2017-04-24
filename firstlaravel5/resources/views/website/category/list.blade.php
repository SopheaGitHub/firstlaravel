<form action="#" method="post" enctype="multipart/form-data" id="form-user-group">
  	<div class="table-responsive">
    	<table class="table table-bordered table-hover">
      		<thead>
        		<tr>
        			<td style="width: 1px;" class="text-center">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'cd.name') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_name; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_name; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_name; ?>" class="order"><?php echo $data->column_name; ?></a>
                <?php } ?>
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'c.sort_order') { ?>
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
      				if(count($data->categories) > 0) {
      					foreach ($data->categories as $category) { ?>
      						<tr>
              			<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $category->category_id; ?>" /></td>
              			<td class="text-left"><?php echo $category->name; ?></td>
              			<td class="text-left"><?php echo $category->sort_order; ?></td>
              			<td class="text-right"><a href="<?php echo $data->edit_category; ?>/<?php echo $category->category_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
  	<div class="col-sm-6 text-left" id="render-category"><?php echo $data->categories->render(); ?></div>
  	<div class="col-sm-6 text-right">
  		<?php
        $start = ($data->categories->currentPage() * $data->categories->perPage()) - $data->categories->perPage() + 1;
        $stop = $data->categories->currentPage() * $data->categories->perPage();
        if($stop > $data->categories->total()){
          $stop = ( $start + $data->categories->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->categories->total(); ?> &nbsp;&nbsp; (<?php echo $data->categories->currentPage(); ?> Pages)
	</div>
</div>