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
                <?php if ($data->sort == 'status') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_status; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_status; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_status; ?>" class="order"><?php echo $data->column_status; ?></a>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php if ($data->sort == 'created_at') { ?>
                  <a href="#" data-sort="<?php echo $data->sort_created_at; ?>" class="order <?php echo strtolower($data->order); ?>"><?php echo $data->column_date_added; ?></a>
                <?php } else { ?>
                  <a href="#" data-sort="<?php echo $data->sort_created_at; ?>" class="order"><?php echo $data->column_date_added; ?></a>
                <?php } ?>
              </td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->users) > 0) {
                foreach ($data->users as $user) { ?>
                  <tr>
                    <td class="text-center">
                      <input type="checkbox" name="selected[]" value="<?php echo $user->id; ?>" />
                    </td>
                    <td class="text-left"><?php echo $user->name; ?></td>
                    <td class="text-left"><?php echo $user->status; ?></td>
                    <td class="text-left"><?php echo $user->created_at; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_user; ?>/<?php echo $user->id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a href="<?php echo $data->change_password_user; ?>/<?php echo $user->id; ?>" data-toggle="tooltip" title="Change Password" class="btn btn-primary"><i class="fa fa-exchange"></i></a>
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
    <div class="col-sm-6 text-left" id="render-user"><?php echo $data->users->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->users->currentPage() * $data->users->perPage()) - $data->users->perPage() + 1;
        $stop = $data->users->currentPage() * $data->users->perPage();
        if($stop > $data->users->total()){
          $stop = ( $start + $data->users->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->users->total(); ?> &nbsp;&nbsp; (<?php echo $data->users->currentPage(); ?> Pages)
  </div>
</div>