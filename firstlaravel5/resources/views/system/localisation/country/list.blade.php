<form action="#" method="post" enctype="multipart/form-data" id="form-user">
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
                <?php if ($data->sort == 'iso_code_2') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_iso_code_2; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_iso_code_2; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_iso_code_2; ?>" class="order"><?php echo $data->column_iso_code_2; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'iso_code_3') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_iso_code_3; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_iso_code_3; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_iso_code_3; ?>" class="order"><?php echo $data->column_iso_code_3; ?></a>
                <?php } ?>
              </td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->countries) > 0) {
                foreach ($data->countries as $country) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $country->country_id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $country->name; ?></td>
                    <td class="text-left"><?php echo $country->iso_code_2; ?></td>
                    <td class="text-left"><?php echo $country->iso_code_3; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_country; ?>/<?php echo $country->country_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-country"><?php echo $data->countries->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->countries->currentPage() * $data->countries->perPage()) - $data->countries->perPage() + 1;
        $stop = $data->countries->currentPage() * $data->countries->perPage();
        if($stop > $data->countries->total()){
          $stop = ( $start + $data->countries->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->countries->total(); ?> &nbsp;&nbsp; (<?php echo $data->countries->currentPage(); ?> Pages)
  </div>
</div>