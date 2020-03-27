<?php
require_once(dirname(__DIR__)."/includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: /login.php");// to wyrzucamy go na stronę logowania

if(getUserAdminLvl($_SESSION['userID']) == 0)// jak nie jest adminem to tez
{
    header("Location: /index.php");
}

require_once(dirname(__DIR__)."/includes/navbar.php");
require_once(dirname(__DIR__)."/includes/sidebar.php");


?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Samochody
            </div>
            <div class="card-body">
                <table id="dataTable" style="cursor:pointer;" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nazwa</th>
                        <th>Tablica</th>
                        <th>Przebieg</th>
                        <th>W bazie ?</th>
                        <th>Przegląd</th>
                        <th>Ubezpieczenie</th>
                        <th>Wymiana oleju</th>
                        <th>Wymiana oleju przy</th>
                    </tr>
                    </thead>
                    <tbody>
            <?php
                $cars = getAllCars();
                $i = 1;
                foreach ($cars as $key => $value)
                {
                    if($cars[$key]['czyWBazie'] == 1)
                        $bazaTick = "<i style='color:green' class='fas fa-check'></i>";
                    else
                        $bazaTick = "<i style='color:red' class='fas fa-times'></i>";
                    
                    if(!$cars[$key]['olejDo'])
                    {
                        $olej = "<span style='color:gray;'>b/d<span>";
                    }
                    else
                    {
                        $olej = date("d.m.Y", strtotime($cars[$key]['olejDo']));
                    }

                    if(!$cars[$key]['olejDoKm'])
                    {
                        $olejKm = "<span style='color:gray;'>b/d<span>";
                    }
                    else
                    {
                        $olejKm = number_format($cars[$key]['olejDoKm'],0,',',' ');
                    }
                    $alertOil = "";
                    $daysToOil = daysToOilReplace($cars[$key]['id']);

                    if($daysToOil <= OIL_ALERT_DAYS && $daysToOil != 'err')
                        $alertOil = "bg-warning";
                    if($daysToOil < -0)
                        $alertOil = "bg-danger text-white";
                    else if(abs($daysToOil) == 0 && is_numeric($daysToOil))
                        $alertOil = "bg-warning";

                    $alertOilKm = "";
                    $kmToOil = getOilMileageLeft($cars[$key]['id']);
                    
                    if(is_numeric($kmToOil))
                    {
                        if($kmToOil <= OIL_ALERT_KM)
                            $alertOilKm = "bg-warning";
                        if($kmToOil < 0)
                            $alertOilKm = "bg-danger  text-white";                        
                        else if($kmToOil == 0)
                            $alertOilKm = "bg-warning";
                    }

                    echo '<tr class="clickable-row" data-href="carsEdit.php?carid='. $cars[$key]['id'] .'">';
                    echo '<td>'. $i .'</td>';
                    echo '<td>'. $cars[$key]['nazwa'] .'</td>';
                    echo '<td>'. $cars[$key]['tablica'] .'</td>';
                    echo '<td>'. number_format($cars[$key]['przebieg'],0,',',' ') .' km</td>';
                    echo '<td>'. $bazaTick .'</td>';
                    echo '<td>'. date("d.m.Y", strtotime($cars[$key]['przegladDo'])) .'</td>';
                    echo '<td>'. date("d.m.Y", strtotime($cars[$key]['ubezDo'])) .'</td>';
                    echo '<td class="'. $alertOil .'">'. $olej .'</td>';
                    echo '<td class="'. $alertOilKm .'">'. $olejKm .' km</td>';
                    echo '</tr>';
                    $i++;
                }
            ?>
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
require_once(dirname(__DIR__)."/includes/footer.php");
?>