<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">

        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Zlecenia</div>
          <div class="card-body">

            <table id="dataTable" style="cursor:pointer;" class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Dysponent</th>
                    <th>Samochód</th>
                    <th>Załoga</th>
                    <th>Ostatnia lokalizacja</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $cards = getAllCards();
                    $i = 1;
                    foreach ($cards as $key => $value)
                    {
                        if($cards[$key]['zakonczony'])
                            $status = '<span class="badge badge-success">Zakończone</span>';
                        else
                            $status = '<span class="badge badge-warning">W trakcie</span>';
                        $staff1 = getStaff($cards[$key]['zaloga_id1']);
                        if($cards[$key]['zaloga_id2'] != -1)
                            $staff2 = getStaff($cards[$key]['zaloga_id2']);
                        $date = date_create($cards[$key]['data']);
                        echo '<tr class="clickable-row" data-href="przejazdy.php?idkarty='. $cards[$key]['id'] .'"><td>'. $i . " " .$status .'</td>';
                        echo '<td>'. date_format($date, 'd.m.Y') .'</td>';
                        echo '<td>'. $cards[$key]['przychodnia'] . ", " . $cards[$key]['lekarz'] .'</td>';
                        echo '<td>'. $cards[$key]['nazwa'] . " [" . $cards[$key]['tablica']. ']</td>';
                        echo '<td>';
                        if(!empty($staff1))
                          echo $staff1['nazwisko']. " " . $staff1['imie'];
                        if($cards[$key]['zaloga_id2'] != -1)
                            echo ", ".$staff2['nazwisko']. " " . $staff2['imie'];
                        echo '</td>';
                        echo '<td>' . getCardLastLocation($cards[$key]['id']) . '</td></tr>';
                        $i++;
                    }
                ?>
                </tbody>
            </table>
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