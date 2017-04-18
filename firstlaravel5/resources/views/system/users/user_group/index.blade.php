@extends('templates.oc_template')
@section('button_pull_right')
<a href="<?php echo $data->add_user_group; ?>" data-toggle="tooltip" title="Add New" class="btn btn-primary"><i class="fa fa-plus"></i></a>
<button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure?') ? $('#form-user-group').submit() : false;"><i class="fa fa-trash-o"></i></button>
@endsection
@section('content')
<div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">
    	<h3 class="panel-title"><i class="fa fa-list"></i> User Group List</h3>
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
  paginateListAction('render-user-group', "<?php echo $data->actionlist; ?>");
  
  $(document).on('click', '.order', function() {
    var sort = $(this).data('sort');
    var order = $(this).data('order');
    $('#orderby').val('?sort='+sort+'&order='+order);
    // var getDatas = $('#orderby').val();
    loadingList("<?php echo $data->actionlist; ?>");
    return false;
  });

});
</script>
@endsection