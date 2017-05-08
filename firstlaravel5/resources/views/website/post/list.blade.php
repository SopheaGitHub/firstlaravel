<form action="#" method="post" enctype="multipart/form-data" id="form-user-group">
  	<div class="table-responsive">
    	<table class="table table-bordered table-hover">
      		<thead>
        		<tr>
        			<td style="width: 1px;" class="text-center">
                <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
              </td>
        			<td class="text-left">
                <?php if ($data->sort == 'title') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_title; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_title; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_title; ?>" class="order"><?php echo $data->column_title; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'author_name') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_author_name; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_author_name; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_author_name; ?>" class="order"><?php echo $data->column_author_name; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'status') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_status; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_status; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_status; ?>" class="order"><?php echo $data->column_status; ?></a>
                <?php } ?>
              </td>
        			<td class="text-right"><?php echo $data->column_action; ?></td>
       			</tr>
      		</thead>
      		<tbody>
      			<?php
      				if(count($data->posts) > 0) {
      					foreach ($data->posts as $post) { ?>
      						<tr>
              			<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $post->post_id; ?>" /></td>
              			<td class="text-left"><?php echo $post->title; ?></td>
                    <td class="text-left"><?php echo $post->author_name; ?></td>
                    <td class="text-left"><?php echo $post->status; ?></td>
              			<td class="text-right"><a href="<?php echo $data->edit_post; ?>/<?php echo $post->post_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
  	<div class="col-sm-6 text-left" id="render-post"><?php echo $data->posts->render(); ?></div>
  	<div class="col-sm-6 text-right">
  		<?php
        $start = ($data->posts->currentPage() * $data->posts->perPage()) - $data->posts->perPage() + 1;
        $stop = $data->posts->currentPage() * $data->posts->perPage();
        if($stop > $data->posts->total()){
          $stop = ( $start + $data->posts->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->posts->total(); ?> &nbsp;&nbsp; (<?php echo $data->posts->currentPage(); ?> Pages)
	</div>
</div>