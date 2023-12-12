<aside class="main-sidebar">
    <section class="sidebar">
    
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu</li>
        <li> <a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span> <span class="pull-right-container"></span> </a></li>       
        <li> <a href="penjualan.php"> <i class="fa fa-shopping-cart"></i> <span>Data Penjualan</span></a> </li>
        <li> <a href="prediksi.php"><i class="fa fa-calculator"></i> <span>Prediksi</span> <span class="pull-right-container"></span> </a></li>
        <?php if($_SESSION['role'] == '2') { ?>
          <li> <a href="history.php"><i class="fa fa-history"></i> <span>History Ramal</span> <span class="pull-right-container"></span> </a></li>
      <?php } ?>
    
      </ul>
      <!-- sidebar-menu --> 
    </section>
  </aside>