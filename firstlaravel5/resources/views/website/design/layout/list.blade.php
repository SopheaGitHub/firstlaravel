<form action="<?php echo $data->action_delete; ?>" method="post" enctype="multipart/form-data" id="form-layout">
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
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->layouts) > 0) {
                foreach ($data->layouts as $layout) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $layout->layout_id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $layout->name; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_layout; ?>/<?php echo $layout->layout_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-layout"><?php echo $data->layouts->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->layouts->currentPage() * $data->layouts->perPage()) - $data->layouts->perPage() + 1;
        $stop = $data->layouts->currentPage() * $data->layouts->perPage();
        if($stop > $data->layouts->total()){
          $stop = ( $start + $data->layouts->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->layouts->total(); ?> &nbsp;&nbsp; (<?php echo $data->layouts->currentPage(); ?> Pages)
  </div>
</div>