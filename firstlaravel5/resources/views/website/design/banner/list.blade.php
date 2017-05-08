<form action="#" method="post" enctype="multipart/form-data" id="form-banner">
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
              if(count($data->banners) > 0) {
                foreach ($data->banners as $banner) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $banner->id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $banner->name; ?></td>
                    <td class="text-left"><?php echo $banner->status; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_banner; ?>/<?php echo $banner->id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-banner"><?php echo $data->banners->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->banners->currentPage() * $data->banners->perPage()) - $data->banners->perPage() + 1;
        $stop = $data->banners->currentPage() * $data->banners->perPage();
        if($stop > $data->banners->total()){
          $stop = ( $start + $data->banners->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->banners->total(); ?> &nbsp;&nbsp; (<?php echo $data->banners->currentPage(); ?> Pages)
  </div>
</div>