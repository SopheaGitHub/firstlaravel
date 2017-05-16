<form action="<?php echo $data->action_delete; ?>" method="post" enctype="multipart/form-data" id="form-geo-zone">
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
                <?php if ($data->sort == 'description') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_description; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_description; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_description; ?>" class="order"><?php echo $data->column_description; ?></a>
                <?php } ?>
              </td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->geo_zones) > 0) {
                foreach ($data->geo_zones as $geo_zone) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $geo_zone->geo_zone_id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $geo_zone->name; ?></td>
                    <td class="text-left"><?php echo $geo_zone->description; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_geo_zone; ?>/<?php echo $geo_zone->geo_zone_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-geo_zone"><?php echo $data->geo_zones->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->geo_zones->currentPage() * $data->geo_zones->perPage()) - $data->geo_zones->perPage() + 1;
        $stop = $data->geo_zones->currentPage() * $data->geo_zones->perPage();
        if($stop > $data->geo_zones->total()){
          $stop = ( $start + $data->geo_zones->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->geo_zones->total(); ?> &nbsp;&nbsp; (<?php echo $data->geo_zones->currentPage(); ?> Pages)
  </div>
</div>