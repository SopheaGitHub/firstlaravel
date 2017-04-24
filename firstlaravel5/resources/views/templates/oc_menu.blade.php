<nav id="column-left">
  <div id="profile">
    <div>
      <i class="fa fa-opencart"></i>
    </div>
    <div>
      <h4>John Doe</h4>
      <small>Administrator</small>
    </div>
  </div>
  <ul id="menu">
    <li id="home">
      <a class="parent"><i class="fa fa-home fa-fw"></i> <span>Home</span></a>
      <ul>
        <li><a href="<?php echo url('/home') ?>">Home</a></li>
        <li><a href="<?php echo url('/dashboard') ?>">Dashboard</a></li>
      </ul>
    </li>
    <li id="website">
      <a class="parent"><i class="fa fa-globe fa-fw"></i> <span>Website</span></a>
      <ul>
        <li><a href="<?php echo url('/categories') ?>">Categories</a></li>
        <li><a href="<?php echo url('/posts') ?>">Posts</a></li>
        <li><a href="<?php echo url('/information') ?>">Information</a></li>
      </ul>
    </li>
    <li id="system">
      <a class="parent"><i class="fa fa-cog fa-fw"></i> <span>System</span></a>
      <ul>
        <li>
          <a href="<?php echo url('/settings'); ?>">Settings</a>
        </li>
        <li><a class="parent">Users</a>
          <ul>
            <li><a href="<?php echo url('/users'); ?>">Users</a></li>
            <li><a href="<?php echo url('/user-groups'); ?>">User Groups</a></li>
          </ul>
        </li>
        <li><a class="parent">Localisation</a>
          <ul>
            <li><a href="<?php echo url('/languages'); ?>">Languages</a></li>
            <li><a href="<?php echo url('/currencies'); ?>">Currencies</a></li>
            <li><a href="<?php echo url('/countries'); ?>">Countries</a></li>
            <li><a href="<?php echo url('/zones'); ?>">Zones</a></li>
            <li><a href="<?php echo url('/geo-zones'); ?>">Geo Zones</a></li>
          </ul>
        </li>
        <li><a href="#">API</a></li>
        <li><a class="parent">Tools</a>
          <ul>
            <li><a href="<?php echo url('/upload'); ?>">Uploads</a></li>
            <li><a href="<?php echo url('/backup-restore'); ?>">Backup / Restore</a></li>
            <li><a href="<?php echo url('/error-log'); ?>">Error Logs</a></li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</nav>