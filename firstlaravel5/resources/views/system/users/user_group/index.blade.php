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
	loadingUserGroupList();

	$(document).on('click','#render-user-group > .pagination > li > a',function(e){
        e.preventDefault();
        var value = $(this).attr('href');
        url = parse_url(value);
        if(url.query != ''){
          var query = url.query;
          var url = "<?php echo $data->actionlist; ?>?"+query;
          $.ajax({
            type: 'GET',
            url: url,
            beforeSend:function() {
        		console.log('beforeSend');
          		$('#display-table').html('Loading ...').show();
        	},
        	complete:function() {
          		console.log('complete');
        	},
        	success:function(html) {
          		$('#display-table').html(html).show();
        	},
        	error:function(err) {
          		$('#display-table').html('<div class="alert alert-danger" id="error"><button type="button" class="close" data-dismiss="alert">&times;</button><b><i class="fa fa-times"></i> Something wrong, Please alert to developer.</b></div>').show();
        	}
          });
          return false;
        }
        return false;
    });	
});

function loadingUserGroupList () {
      	$.ajax({
        	type: "GET",
        	url: "<?php echo $data->actionlist; ?>",
        	beforeSend:function() {
        		console.log('beforeSend');
          		$('#display-table').html('Loading ...').show();
        	},
        	complete:function() {
          		console.log('complete');
        	},
        	success:function(html) {
          		$('#display-table').html(html).show();
        	},
        	error:function(err) {
          		$('#display-table').html('<div class="alert alert-danger" id="error"><button type="button" class="close" data-dismiss="alert">&times;</button><b><i class="fa fa-times"></i> Something wrong, Please alert to developer.</b></div>').show();
        	}
    	});
	}
</script>
@endsection