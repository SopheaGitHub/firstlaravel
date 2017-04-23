<form action="#" method="post" enctype="multipart/form-data" id="form-website">
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $data->column_name; ?></td>
              <td class="text-left"><?php echo $data->column_url; ?></td>
              <td class="text-right"><?php echo $data->column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php
              if(count($data->websites) > 0) {
                foreach ($data->websites as $website) { ?>
                  <tr>
                    <td class="text-left"><?php echo $website->name; ?></td>
                    <td class="text-left"><?php echo $website->url; ?></td>
                    <td class="text-right">
                      <a href="<?php echo $data->edit_website; ?>/<?php echo $website->website_id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
    <div class="col-sm-6 text-left" id="render-website"><?php echo $data->websites->render(); ?></div>
    <div class="col-sm-6 text-right">
      <?php
        $start = ($data->websites->currentPage() * $data->websites->perPage()) - $data->websites->perPage() + 1;
        $stop = $data->websites->currentPage() * $data->websites->perPage();
        if($stop > $data->websites->total()){
          $stop = ( $start + $data->websites->count()) - 1;
        }
        if($stop == 0){
          $start = 0;
        }
      ?>
      Showing <?php echo $start; ?> to <?php echo $stop; ?> of <?php echo $data->websites->total(); ?> &nbsp;&nbsp; (<?php echo $data->websites->currentPage(); ?> Pages)
  </div>
</div>