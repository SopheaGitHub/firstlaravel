<form action="#" method="post" enctype="multipart/form-data" id="form-user">
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
                <?php if ($data->sort == 'status') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_code; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_code; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_code; ?>" class="order"><?php echo $data->column_code; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'value') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_value; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_value; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_value; ?>" class="order"><?php echo $data->column_value; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'updated_at') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_updated_at; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_updated_at; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_updated_at; ?>" class="order"><?php echo $data->column_updated_at; ?></a>
                <?php } ?>
              </td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->currencies) > 0) {
                foreach ($data->currencies as $currency) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $currency->currency_id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $currency->title; ?></td>
                    <td class="text-left"><?php echo $currency->code; ?></td>
                    <td class="text-left"><?php echo $currency->value; ?></td>
                    <td class="text-left"><?php echo $currency->updated_at; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_currency; ?>/<?php echo $currency->currency_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-currency"><?php echo $data->currencies->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->currencies->currentPage() * $data->currencies->perPage()) - $data->currencies->perPage() + 1;
        $stop = $data->currencies->currentPage() * $data->currencies->perPage();
        if($stop > $data->currencies->total()){
          $stop = ( $start + $data->currencies->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->currencies->total(); ?> &nbsp;&nbsp; (<?php echo $data->currencies->currentPage(); ?> Pages)
  </div>
</div>