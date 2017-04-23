@extends('templates.oc_template')
@section('content')
<div class="container-fluid">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-list"></i> Setting List</h3>
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
  paginateListAction('render-website', "<?php echo $data->actionlist; ?>");
  
  $(document).on('click', '.order', function() {
    var url = $(this).data('sort');
    loadingList("<?php echo $data->actionlist; ?>"+url);
    return false;
  });

});
</script>
@endsection