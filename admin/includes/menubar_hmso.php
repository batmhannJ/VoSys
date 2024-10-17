<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo (!empty($user['photo'])) ? '../images/'.$user['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p><?php echo $user['firstname'].' '.$user['lastname']; ?></p>
        <a><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">REPORTS</li>
      <li class=""><a href="home_hmso.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
      <li class=""><a href="livepoll_hmso.php"><i class="fa fa-bar-chart"> </i><span>Live Polling</span></a></li>
      <li class=""><a href="votes_hmso.php"><span class="glyphicon glyphicon-lock"></span> <span>Votes Result</span></a></li>
      <li class="header">MANAGE</li>
      <!--<li class=""><a href="sub_admin.php"><i class="fa fa-user-plus"></i> <span>Sub Admins</span></a></li>-->
      <li class=""><a href="voters_hmso.php"><i class="fa fa-users"></i> <span>Voters</span></a></li>
      <li class=""><a href="positions_hmso.php"><i class="fa fa-tasks"></i> <span>Positions</span></a></li>
      <li class=""><a href="candidates_hmso.php"><i class="fa fa-black-tie"></i> <span>Candidates</span></a></li>
      <li class=""><a href="elections_hmso.php"><i class="fa fa-cog"></i> <span>Elections</span></a></li>
      <!--<li class=""><a href="categories.php"><i class="fa fa-cog"></i> <span>Categories</span></a></li>-->
      <li class="header">SETTINGS</li>
      
      <!--<li class=""><a href="#" data-toggle="modal"><i class="fa fa-book"></i> <span>Archives</span></a></li>-->
      <li class=""><a href="history_hmso.php"><i class="fa fa-clock-o"></i> <span>History</span></a></li>
      <li class=""><a href="archive_hmso.php"><i class="fa fa-archive"></i> <span>Archives</span></a></li>
      <li class=""><a href="ballot_hmso.php"><i class="fa fa-file-text"></i> <span>Ballot Position</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<?php include 'config_modal.php'; ?>