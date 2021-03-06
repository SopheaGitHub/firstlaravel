<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="UTF-8" />
<title><?php echo ((isset($data->web_title))? $data->web_title:''); ?></title>
<base href="http://localhost/projects/firstlaravel/firstlaravel5/public/" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="<?php echo asset('/javascript/jquery/jquery-2.1.1.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('/javascript/bootstrap/js/bootstrap.min.js'); ?>"></script>
<link href="<?php echo asset('/stylesheet/bootstrap.css'); ?>" type="text/css" rel="stylesheet" />
<link type="text/css" href="<?php echo asset('/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" media="all">
<link href="<?php echo asset('/javascript/summernote/summernote.css'); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php echo asset('/javascript/summernote/summernote.js'); ?>"></script>
<script src="<?php echo asset('/common.js'); ?>" type="text/javascript"></script>
<link type="text/css" href="<?php echo asset('/stylesheet/stylesheet.css'); ?>" rel="stylesheet" media="screen" />
<style type="text/css">
	.form-group + .form-group {
		border-top: none;
	}
	textarea > .hidden{
	  	display:none;
	}
	#block-loader {
	  /*position: absolute;*/
	  z-index: 1000000;
	  width: 100%;
	  height: 100%;
	  background: #010100;
	  top: 0;
	  left: 0;
	  bottom: 0;
	  right: 0;
	  position: fixed;
	  background-color: rgba(0,0,0,0.2);
	}
	.myloader {
	  border: 16px solid #f3f3f3;
	  border-radius: 50%;
	  border-top: 16px solid #1e91cf;
	  width: 100px;
	  height: 100px;
	  margin: 15% auto;
	  opacity: 10;
	  -webkit-animation: spin 2s linear infinite;
	  animation: spin 2s linear infinite;
	  
	}

	@-webkit-keyframes spin {
	  0% { -webkit-transform: rotate(0deg); }
	  100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	  0% { transform: rotate(0deg); }
	  100% { transform: rotate(360deg); }
	}
</style>
</head>
<body>
<div id="block-loader">
  <div class="myloader"></div>
</div>
<div id="container">
  	@include('templates.oc_header')
  	@include('templates.oc_menu')
  	<div id="content">
	  	<div class="page-header">
	    	<div class="container-fluid">
	      		<div class="pull-right">
	      			@yield('button_pull_right')
	      		</div>
	      		<h1 style="color: #F4CB1C; font-size: 24px;"><?php echo ((isset($data->web_title))? $data->web_title:''); ?></h1>
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
	            <?php
				    if(Session::get('success')) { ?>
				      <div class="alert alert-success" id="success"><i class="fa fa-check-circle"></i> <?php echo Session::get('success'); ?>
				          <button type="button" class="close" data-dismiss="alert">&times;</button>
				      </div>
				<?php } ?>
				<?php
				    if(Session::get('warning')) { ?>
				      <div class="alert alert-warning" id="warning"><i class="fa fa-info-circle"></i> <?php echo Session::get('warning'); ?>
				          <button type="button" class="close" data-dismiss="alert">&times;</button>
				      </div>
				<?php } ?>
				<?php
				    if(Session::get('error')) { ?>
				      <div class="alert alert-danger" id="error"><i class="fa fa-times"></i> <?php echo Session::get('error'); ?>
				        <button type="button" class="close" data-dismiss="alert">&times;</button>
				      </div>
				<?php } ?>
	    	</div>
	  	</div>
	  	@yield('content')
	</div>
  	@include('templates.oc_footer')
</div>
<script type="text/javascript">$('#block-loader').hide();</script>
@yield('script')
</body>
</html>