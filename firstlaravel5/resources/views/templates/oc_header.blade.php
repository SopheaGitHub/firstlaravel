<header id="header" class="navbar navbar-static-top">
  	<div class="navbar-header">
    	<a type="button" id="button-menu" class="pull-left" style="color: #F4CB1C;"><i style="color: #F4CB1C;" class="fa fa-indent fa-lg"></i></a>
      <a href="<?php echo url('/home'); ?>" class="navbar-brand hidden-xs" style="color: #F4CB1C;">LARAVEL 5.0</a>
    </div>
  	<ul class="nav pull-right">
    	<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left">1</span> <i class="fa fa-bell fa-lg"></i></a>
          <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
            	<li class="dropdown-header">Projects</li>
            	<li><a href="0" style="display: block; overflow: auto;"><span class="label label-warning pull-right">0</span>New Projects</a></li>
            	<li class="divider"></li>
            	<li class="dropdown-header">Messages</li>
            	<li><a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=message/contact_us&amp;token=9Xq1yENEhFPordUqD5lwKtRiQ9J17BWa&amp;filter_viewed=0"><span class="label label-success pull-right">0</span>Contact</a></li>
            	<li><a href="http://localhost/projects/koktepweb/upload/admin/index.php?route=message/feedback&amp;token=9Xq1yENEhFPordUqD5lwKtRiQ9J17BWa&amp;filter_viewed=0"><span class="label label-success pull-right">1</span>Feedback</a></li>
          </ul>
    	</li>
    	<li><a href="<?php echo url('/auth/logout'); ?>"><span>Logout</span> <i style="color: #F4CB1C;" class="fa fa-sign-out fa-lg"></i></a></li>
	</ul>
</header>