<div id="wrapper">

<!-- Sidebar -->
<ul class="sidebar navbar-nav">
  <li class="nav-item">
    <a class="nav-link " href="/">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
    <a class="nav-link bg-success text-white" href="/kartaDodaj.php">
      <i class="fas fa-plus"></i>
      <span>Wyjazd zespołu</span>
    </a>
    <a class="nav-link" href="/karty.php">
    <i class="fas fa-fw fa-clipboard"></i>
      <span>Zlecenia</span>
    </a>
    <a class="nav-link" href="/terminarz.php">
    <i class="fas fa-fw fa-calendar    "></i>
      <span>Terminarz</span>
    </a>
    
    <?php
    if(getUserAdminLvl($_SESSION['userID']) != 0)
    {
      echo '
      <a class="nav-link collapsed" data-toggle="collapse" href="#collapse2" role="button">
      <i class="fas fa-car-alt    "></i>
          <span>Samochody</span>
          <i class="fas fa-sort-down float-right"></i>
      </a>
      <div class="collapse" id="collapse2">
        <a class="nav-link" href="/kartyDrogowe.php">
          <span>Wydruk karty drogowej</span>
        </a>
      </div>
      ';

      echo '
      <a class="nav-link collapsed bg-secondary" data-toggle="collapse" href="#collapseExample" role="button">
      <i class="fas fa-tools"></i>
          <span>[ADMIN_MENU]</span>
          <i class="fas fa-sort-down float-right"></i>
      </a>
      <div class="collapse" id="collapseExample">
        <a class="nav-link" href="/admin/users.php">
          <span>Użytkownicy</span>
        </a>
        <a class="nav-link" href="/admin/log.php">
          <span>Log</span>
        </a>
      </div>
      ';
    }
    ?>
  </li>

</ul>