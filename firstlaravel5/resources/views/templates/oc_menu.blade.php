<nav id="column-left"><div id="profile">
  <div>
    <i class="fa fa-opencart"></i>
  </div>
  <div>
    <h4>John Doe</h4>
    <small>Administrator</small>
  </div>
</div>
  <ul id="menu">
    <li id="dashboard">
      <a href="<?php echo url('/home') ?>"><i class="fa fa-dashboard fa-fw"></i> <span>Dashboard</span></a>
    </li>
    <li id="system">
      <a class="parent"><i class="fa fa-cog fa-fw"></i> <span>System</span></a>
      <ul>
        <li>
          <a href="<?php echo url('/setting'); ?>">Settings</a>
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
            <li><a href="<?php echo url('/currency'); ?>">Currencies</a></li>
            <li><a href="<?php echo url('/country'); ?>">Countries</a></li>
            <li><a href="<?php echo url('/zone'); ?>">Zones</a></li>
            <li><a href="<?php echo url('/geo-zone'); ?>">Geo Zones</a></li>
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
    
    <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span>Reports</span></a>
      <ul>
        <li><a class="parent">Projects</a>
          <ul>
            <li><a href="<?php echo url('/viewed'); ?>">Viewed</a></li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</nav>