<form action="<?php echo $data->action_delete; ?>" method="post" enctype="multipart/form-data" id="form-zone">
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
                <?php if ($data->sort == 'code') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_code; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_code; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_code; ?>" class="order"><?php echo $data->column_code; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'country_id') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_country_id; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_country_id; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_country_id; ?>" class="order"><?php echo $data->column_country_id; ?></a>
                <?php } ?>
              </td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->zones) > 0) {
                foreach ($data->zones as $zone) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $zone->zone_id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $zone->name; ?></td>
                    <td class="text-left"><?php echo $zone->code; ?></td>
                    <td class="text-left"><?php echo $zone->country_name; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_zone; ?>/<?php echo $zone->zone_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-zone"><?php echo $data->zones->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->zones->currentPage() * $data->zones->perPage()) - $data->zones->perPage() + 1;
        $stop = $data->zones->currentPage() * $data->zones->perPage();
        if($stop > $data->zones->total()){
          $stop = ( $start + $data->zones->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->zones->total(); ?> &nbsp;&nbsp; (<?php echo $data->zones->currentPage(); ?> Pages)
  </div>
</div>