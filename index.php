<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">
      Dashboard
    </li>
    <li class="breadcrumb-item active">Przegląd</li>
  </ol>
  <?php
        $koniec = getCarsKonczaceSie();
        if(!isset($_SESSION['koniecAlertShown']))
        {
          $_SESSION['koniecAlertShown'] = 0;
        }
        if(($koniec['o_km'] || $koniec['o'] || $koniec['p'] || $koniec['u']) && !@$_SESSION['koniecAlertShown'])
        {
          $_SESSION['koniecAlertShown'] = 1;
          echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            if($koniec['o'])
            {
              echo '<a href="/admin/cars.php"><p>W <b>'. $koniec['o'] . '</b>';
              if($koniec['o'] == 1) echo ' samochodzie ';
              else echo ' samochodach ';
              echo 'zbliża się termin wymiany oleju!</p></a>';
            }
            
            else if($koniec['o_km'])
            {
              echo '<a href="/admin/cars.php"><p>W <b>'. $koniec['o_km'] . '</b>';
              if($koniec['o_km'] == 1) echo ' samochodzie ';
              else echo ' samochodach ';
              echo 'zostało mniej niż '. OIL_ALERT_KM .' km do wymiany oleju!</p></a>';
            }

            if($koniec['u'])
            {
              echo '<a href="/admin/cars.php"><p>W <b>'. $koniec['u'] . '</b>';
              if($koniec['u'] == 1) echo ' samochodzie ';
              else echo ' samochodach ';
              echo 'kończy się ważność ubezpieczenia!</p></a>';
            }

            if($koniec['p'])
            {
              echo '<a href="/admin/cars.php"><p>W <b>'. $koniec['p'] . '</b>';
                if($koniec['p'] == 1) echo ' samochodzie ';
                else echo ' samochodach ';
                echo 'zbliża się termin przeglądu!</p></a>';
            }
          echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }

      
        ?>
  <div class="row">
    <div class="col">
        <!-- Area Chart Example-->
        <div style="cursor:pointer;" id="terminarzGlowna" class="card mb-3">
          <div id="terminarzGlowna" class="card-header bg-success text-white">
            <i class="fas fa-calendar-times    "></i> 
            Zaplanowane transporty na dziś</div>
            <div class="card-body">
            <?php 
              $tEvents = getTodayEvents();
              if(!$tEvents)// jeśli nie ma dziśiaj żadnych wydarzeń
              {
                echo '
                <div class="alert alert-secondary" role="alert">
                  <b>Informacja</b><hr>
                  Nie ma żadnych zaplanowanych transportów na dzisiaj.
                </div>
                ';
                //informujemy o tym
              }
              else
              {
                foreach($tEvents as $key => $value)
                {
                  echo '<h5>' . $tEvents[$key]['nazwa'] . '</h5><hr>';
                  echo '<span><b>Godzina:</b> ' . substr($tEvents[$key]['time'],0,5) . '</span><br>';
                  echo '<span><b>Opis:<br></b> ' . $tEvents[$key]['opis'] . '</span><br><br>';
                }
              }
            ?>
          </div></a>
        </div>
</div>
</div>

</div>
<!-- /.container-fluid -->

<!-- Sticky Footer -->
<footer class="sticky-footer">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright ©</span>
    </div>
  </div>
</footer>

</div>
<!-- /.content-wrapper -->
<?php
require_once("includes/footer.php");
?>