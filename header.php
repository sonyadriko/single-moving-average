<header class="main-header"> 
<!-- <a href="index.html" class="logo white-bg"> 
    <span class="logo-mini"><p>Single Moving Average</p></span> 
    <span class="logo-lg"><p>Single Moving Average</p></span> </a>  -->
    <nav class="navbar navbar-static-top" role="navigation"> 
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"> <span class="sr-only">Toggle navigation</span> </a>
     
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="user.jpg" class="user-image" alt="User Image"> <span class="hidden-xs"><?php echo $_SESSION['nama'] ?></span> </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <div class="pull-left user-img"><img src="user.jpg" class="img-responsive" alt="User"></div>
                <p class="text-left"><?php echo $_SESSION['nama'] ?>
                 <!-- <small><?php echo $_SESSION['email'] ?></small>  -->
                <small><?php if($_SESSION['role'] == 1) {
                  echo "Admin";
                } else {
                  echo "Pemilik";
                } ?></small> </p>
              </li>
              <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>