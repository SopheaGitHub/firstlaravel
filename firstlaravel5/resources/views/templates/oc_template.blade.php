<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo ((isset($data->title))? $data->title:''); ?></title>
<base href="http://localhost/projects/firstlaravel5/public/" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<link type="text/css" href="<?php echo asset('/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo asset('/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" media="all">
<link type="text/css" href="<?php echo asset('/css/stylesheet.css'); ?>" rel="stylesheet" media="screen" />
<style type="text/css">
	.form-group + .form-group {
		border-top: none;
	}
</style>
</head>
<body>
<div id="container">
  	@include('templates.oc_header')
  	@include('templates.oc_menu')
  	<div id="content">
	  	<div class="page-header">
	    	<div class="container-fluid">
	      		<div class="pull-right">
	      			@yield('button_pull_right')
	      		</div>
	      		<h1 style="color: #F4CB1C; font-size: 24px;"><?php echo ((isset($data->title))? $data->title:''); ?></h1>
	      		<ul class="breadcrumb">
	      			<?php
	            		if(isset($data->breadcrumbs) && count($data->breadcrumbs) > 0) {
	            			foreach ($data->breadcrumbs as $breadcrumb) { ?>
	            				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	            			<?php }
	            		}else {
	            			echo 'Please setup breadcrumb for this page!';
	            		}
	            	?>
	            </ul>
	    	</div>
	  	</div>
	  	@yield('content')
	</div>
  	@include('templates.oc_footer')
</div>
<script src="<?php echo asset('/jquery/jquery.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo asset('/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo asset('/common.js'); ?>" type="text/javascript"></script>
@yield('script')
</body>
</html>