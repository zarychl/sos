<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="/"><i class="fas fa-ambulance"></i> SOS Ambulans</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
    </form>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto ml-md-0">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle fa-fw"></i> Witaj,<strong> <?php echo getUserName($_SESSION['userID']); ?></strong>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
          <a class="dropdown-item" href="/profile.php"><i class="fas fa-user-alt    "></i> Profil</a>
          <a class="dropdown-item" href="#"><i class="fas fa-wrench    "></i> Ustawienia</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/logout.php" ><i class="fas fa-door-open    "></i> Wyloguj</a>
        </div>
      </li>
    </ul>

  </nav>