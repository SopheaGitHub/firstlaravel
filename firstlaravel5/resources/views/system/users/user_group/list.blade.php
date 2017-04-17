<form action="#" method="post" enctype="multipart/form-data" id="form-user-group">
  	<div class="table-responsive">
    	<table class="table table-bordered table-hover">
      		<thead>
        		<tr>
          			<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
          			<td class="text-left"><a href="#" class="asc">User Group Name</a></td>
          			<td class="text-left">System</td>
          			<td class="text-right">Action</td>
       			</tr>
      		</thead>
      		<tbody>
      			<?php
      				if($data->user_groups) {
      					foreach ($data->user_groups as $user_group) { ?>
      						<tr>
	                  			<td class="text-center">
	                  				<?php
	                  					if($user_group->default!='1') {?>
	                  						<input type="checkbox" name="selected[]" value="<?php echo $user_group->user_group_id; ?>" />
	                  					<?php } else { ?>
	                  						<input type="checkbox" name="" value="" disabled="disabled" />
	                  					<?php }
	                  				?>
	                  			</td>
	                  			<td class="text-left"><?php echo $user_group->name; ?></td>
	                  			<td class="text-left"><?php echo (($user_group->default==1)? 'Default':''); ?></td>
	                  			<td class="text-right">
	                  				<?php
	                  					if($user_group->default!='1') {?>
	                  						<a href="<?php echo $data->edit_user_group; ?>/<?php echo $user_group->user_group_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
	                  					<?php } else { ?>
	                  						<button type="button" class="btn btn-primary disabled"><i class="fa fa-pencil"></i></button>
	                  					<?php }
	                  				?>
	                  			</td>
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
  	<div class="col-sm-6 text-left" id="render-user-group"><?php echo $data->user_groups->render(); ?></div>
  	<div class="col-sm-6 text-right">
		<?php
            $start = ($data->user_groups->currentPage() * $data->user_groups->perPage()) - $data->user_groups->perPage() + 1;
            $stop = $data->user_groups->currentPage() * $data->user_groups->perPage();
            if($stop > $data->user_groups->total()){
                $stop = ( $start + $data->user_groups->count()) - 1;
            }
            if($stop == 0){
                $start = 0;
            }
        ?>
        Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->user_groups->total(); ?> &nbsp;&nbsp; (<?php echo $data->user_groups->currentPage(); ?> Pages)
	</div>
</div>