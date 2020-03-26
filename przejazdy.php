<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(!isset($_GET['idkarty']))
    header("Location: karty.php");
$karta = getKarta($_GET['idkarty']);
if(empty($karta))
  header("Location: karty.php");
$car = getCar($karta['car_id']);
$staff1 = getStaff($karta['zaloga_id1']);
if($karta['zaloga_id2'] != -1)
  $staff2 = getStaff($karta['zaloga_id2']);
$przejazdy = getPrzejazdyByKarta($_GET['idkarty']);

$idkarty = $_GET['idkarty'];

if(hasCardUnfinishedPrzejazd($_GET['idkarty']))
{
  $nieukonczony = getCardUnfinishedPrzejazd($_GET['idkarty']);
}
require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Karta</li>
    <li class="breadcrumb-item active">Przejazdy</li>
  </ol>
  <?php
  if(@$_SESSION['lastEditSuccess'] == 1)
  {
    echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Sukces!</strong> Dodano przyjazd pomyślnie.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    ';
    $_SESSION['lastEditSuccess'] = 0;
  }
  else if(@$_SESSION["lastInitSuccess"] == 1)
  {
    echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Sukces!</strong> Dodano wyjazd pomyślnie.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    ';
    $_SESSION['lastInitSuccess'] = 0;
  }
  ?>

        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Karta wyjazdu (ID: <?php echo $_GET['idkarty']; ?>)
            <?php
            if($karta['zakonczony'] == 0)
              echo '<span class="badge badge-warning">W trakcie</span>';
            else
              echo '<span class="badge badge-success">Zakończona</span>';
            ?>
            </div>

          <div class="card-body">
          <div class="row">
          <div class="col">
        <table class="table table-striped table-responsive table-sm">
            <tbody>
                <tr>
                <th scope="row">Data zlecenia:</th>
                <td><?php
                 $date = date_create($karta['data']);
                 echo date_format($date, 'd.m.Y');
                 ?></td>
                </tr>
                <tr>
                <th scope="row">Samochód:</th>
                <td><?php echo $car['nazwa']. " [". $car['tablica']. "]"; ?></td>
                </tr>
                <tr>
                <th scope="row">Załoga:</th>
                <td><?php
                if(!empty($staff1))
                  echo $staff1['nazwisko']. " ". $staff1['imie'] ; 
                if(isset($staff2))
                echo ", " . $staff2['nazwisko']. " ". $staff2['imie'] ; 
                ?></td>
                <tr>
                <th scope="row">Dysponent:</th>
                <td><?php
                echo $karta['przychodnia']. ", ". $karta['lekarz'] ;
                if(hadCardDysponent($karta['id']))
                {
                  echo '<a role="button" class="btn btn-success float-right" href="editDysponent.php?idkarty='. $karta['id'] .'"><i class="fas fa-plus-circle    "></i> Dodaj</button>';
                }
                else
                  echo '<a role="button" class="btn btn-success float-right" href="editDysponent.php?idkarty='. $karta['id'] .'"><i class="fas fa-pen    "></i> Edytuj</button>';
                ?></td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="col">
        <?php
        if(hadKartaPacjent($karta['id']))
        {
          $p = getPacjentByKarta($karta['id']);
          echo '
          <table class="align-middle table table-striped table-sm">
          <thead>
            <tr>
              <th class="bg-success text-white" colspan="4">PACJENT</th>
            </tr>
            <tr>
              <th>#</th>
              <th>Imię i Nazwisko</th>
            </tr>
          </thead>
          <tbody>
          ';
          $i = 1;
          foreach($p as $key => $value)
          {
            echo '<tr><td>'. $i .'</td>';
            echo '<td>'. $p[$key]['pacjent'] .'</td>';
            $i++;
          }
          echo '
          </tbody>
        </table>
          ';
        }
        ?>
        </div>

        <div class="col">
        <?php
        if(hasCardFueling($karta['id']))
        {
          $f = getCardFueling($karta['id']);
          echo '
          <table class="align-middle table table-striped table-sm">
          <thead>
            <tr>
              <th class="bg-success text-white" colspan="4">TANKOWANIE</th>
            </tr>
            <tr>
              <th>#</th>
              <th>Ilość paliwa<br>[litry]</th>
              <th>Koszt<br>[złotych]</th>
              <th>Nr faktury</th>
            </tr>
          </thead>
          <tbody>
          ';
          $i = 1;
          foreach($f as $key => $value)
          {
            echo '<tr><td>'. $i .'</td>';
            echo '<td>'. $f[$key]['litry'] .'</td>';
            echo '<td>'. $f[$key]['koszt'] .'</td>';
            echo '<td>'. $f[$key]['faktura'] .'</td></tr>';
            $i++;
          }
          echo '
          </tbody>
        </table>
          ';
        }
        ?>
        </div>


        </div>
              <hr>

              <div class="row">
                <div class="col">
                <?php
                if($karta['zakonczony'] != 1 && !hasCardUnfinishedPrzejazd($karta['id']))
                  echo '
                  <a class="btn btn-primary" role="button" href="przejazdyNowy.php?idkarty='. $_GET['idkarty'] .'">Dodaj nowy przejazd</a>
                  <button class="btn btn-danger" role="button" onclick="closeCardConf('. $karta['id'] .')") " >Zakończ kartę</button>
                  ';
                else if($karta['zakonczony'] != 1 && hasCardUnfinishedPrzejazd($karta['id']))
                {
                  echo '<a class="btn btn-warning" role="button" href="przejazdyKoniec.php?idkarty='. $_GET['idkarty'] .'&id='. $nieukonczony['id'] .'">Dodaj przyjazd</a>&nbsp;';
                  echo '<a class="btn btn-secondary" role="button" href="tankowanieDodaj.php?idkarty='. $_GET['idkarty'] .'"><i class="fas fa-gas-pump"></i> Dodaj tankowanie</a>';
                }
                else if($karta['zakonczony'])
                {
                  echo '<a class="btn btn-primary" role="button" href="printKarta.php?idkarty='. $_GET['idkarty'].'"><i class="fas fa-print"></i> Drukuj kartę</a>&nbsp;';
                }
                ?>
                </div>
              </div>

              <div class="row">
          <div class="col">
          <table class="table table-striped table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Skąd -> dokąd</th>
      <th scope="col">Wyjazd<br> godz.</th>
      <th scope="col">Wyjazd<br>Stan licznika</th>
      <th scope="col">Przyjazd<br> godz.</th>
      <th scope="col">Przyjazd<br>Stan licznika</th>
      <th scope="col">Przebieg<br>km</th>
      <th scope="col">Czas</th>
      <th scope="col">Pacjent</th>
      <th scope="col">Opcje</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach ($przejazdy as $key => $value)
  {
    $time1 = explode(":" , $przejazdy[$key]['przyjazdTime']);
    $time2 = explode(":" , $przejazdy[$key]['wyjazdTime']);
    $id = $przejazdy[$key]['id'];
    $przebieg1 = intval($przejazdy[$key]['przyjazdPrzebieg']);
    $przebieg2 = intval($przejazdy[$key]['wyjazdPrzebieg']);
    $przebieg_roznica = $przebieg1 - $przebieg2;

    echo "<tr>";
    echo "<th scope='row'>$i</th>";// #
    echo "<td>". $przejazdy[$key]['skad']. " -> ". $przejazdy[$key]['dokad'] ."</td>";// skąd dokąd
    echo "<td>". date("H:i", mktime($time2[0],$time2[1])) ."</td>";// Wyjazd godz
    echo "<td>". $przebieg2 ."</td>";// Wyjazd Stan licznika
    if($przebieg1 != 0)
    {
      echo "<td>". date("H:i", mktime($time1[0],$time1[1])) ."</td>";// Przyjazd godz
      echo "<td>". $przebieg1 ."</td>";// Przyjazd Stan licznika
      echo "<td>". $przebieg_roznica ."</td>";// Przebieg
      echo "<td>". displayTimeDiff($time2[0],$time2[1],$time1[0],$time1[1]) ."</td>";// Czas
      echo "<td>". $przejazdy[$key]['pacjent'] ."</td>";// Czas
      echo "<td><a href='przejazdyEdit.php?id=$id&idKarty=$idkarty'><i class='fas fa-pen'></i> Edycja</a></td>";// opcje
    }
    else
    {
      echo "<td><span class='badge badge-warning'>W trasie</span></td>";
      echo "<td><span class='badge badge-warning'>W trasie</span></td>";
      echo "<td><span class='badge badge-warning'>W trasie</span></td>";// Przebieg
      echo "<td><span class='badge badge-warning'>W trasie</span></td>";// Czas
      echo "<td>". $przejazdy[$key]['pacjent'] ."</td>";// Czas
      echo "<td><a href='przejazdyEdit.php?id=$id&idKarty=$idkarty'><i class='fas fa-pen'></i> Edycja</a></td>";// Czas
    }
    echo "</tr>";
    $i++;
  }
  ?>
  </tbody>
</table>
        </div>
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