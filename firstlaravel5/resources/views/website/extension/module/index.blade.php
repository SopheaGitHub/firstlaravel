@extends('templates.oc_template')
@section('message')
  <?php
    if(Session::get('success')) { ?>
      <div class="alert alert-success" id="success"><i class="fa fa-check-circle"></i> <?php echo Session::get('success'); ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>
  <?php
    if(Session::get('error')) { ?>
      <div class="alert alert-danger" id="error"><i class="fa fa-times"></i> <?php echo Session::get('error'); ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>
@endsection
@section('content')
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-list"></i> Module List</h3>
    </div>
    <div class="panel-body" id="display-table">
      
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  loadingList("<?php echo $data->actionlist; ?>");
  paginateListAction('render-module', "<?php echo $data->actionlist; ?>");
  
  $(document).on('click', '.order', function() {
    var url = $(this).data('sort');
    loadingList("<?php echo $data->actionlist; ?>"+url);
    return false;
  });

});
</script>
@endsection