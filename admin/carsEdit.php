<?php
require_once(dirname(__DIR__)."/includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: /login.php");// to wyrzucamy go na stronę logowania

if(getUserAdminLvl($_SESSION['userID']) == 0)// jak nie jest adminem to tez
{
    header("Location: /index.php");
}

if(!isset($_GET['carid']))// jak nie jest adminem to tez
{
    header("Location: /admin/cars.php");
}

$car = getCar($_GET['carid']);

require_once(dirname(__DIR__)."/includes/navbar.php");
require_once(dirname(__DIR__)."/includes/sidebar.php");

?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Szczegóły samochodu - <?php echo $car['nazwa'] . ' [' . $car['tablica'] . ']'; ?>
            </div>
            <div class="card-body">
            <div class="row">

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Przegląd</h5>
                        <p class="card-text">
                            <?php
                            echo '<b>Do: </b>' . date("d.m.Y", strtotime($car['przegladDo']));
                            ?>
                        </p>
                        <a href="#" class="btn btn-primary">Zmień</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ubezpieczenie</h5>
                        <p class="card-text">
                            <?php
                            echo '<b>Do: </b>' . date("d.m.Y", strtotime($car['ubezDo']));
                            ?>
                        </p>
                        <a href="#" class="btn btn-primary">Zmień</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Wymiana oleju</h5>
                        <p class="card-text">
                            <?php
                            $daysToOilReplace = daysToOilReplace($car['id']);
                            $kmToOil = getOilMileageLeft($car['id']);

                            if(is_numeric($daysToOilReplace))
                            {
                                if($daysToOilReplace > 0)
                                {
                                    if($daysToOilReplace <= OIL_ALERT_DAYS)
                                        $dtOil = ", <span style='color:orange;'>dni do wymiany: " . abs($daysToOilReplace) . "</span>";
                                    else
                                        $dtOil = ", dni do wymiany: " . abs($daysToOilReplace);
                                }
                                else if(abs($daysToOilReplace) == 0)
                                    $dtOil = "<br><span style='color:red;'>Termin wymiany mija dziś!</span>";
                                else
                                    $dtOil = "<br><span style='color:red;'>Termin wymiany minął " . abs($daysToOilReplace) . " dni temu!</span>";
                            }

                            if(is_numeric($kmToOil))
                            {
                                if($kmToOil > 0)
                                {
                                    if($kmToOil <= OIL_ALERT_KM)
                                        $dtOilKm = ", <span style='color:orange;'>km do wymiany: " . abs($kmToOil) . "</span>";
                                    else
                                        $dtOilKm = ", km do wymiany: " . abs($kmToOil);
                                }
                                else if(abs($kmToOil) == 0)
                                    $dtOilKm = "<br><span style='color:red;'>Osiągnięto przebieg wymiany!</span>";
                                else
                                    $dtOilKm = "<br><span style='color:red;'>Przekroczono przebieg o " . abs($kmToOil) . " km!</span>";
                            }

                            if(!isset($car['olejDo']))
                            {
                                $olej = "<span style='color:gray;'>b/d</span>";
                            }
                            else
                            {
                                $olej = date("d.m.Y", strtotime($car['olejDo']));
                            }

                            if(!isset($car['olejDoKm']))
                            {
                                $olejKm = "<span style='color:gray;'>b/d</span>";
                            }
                            else
                            {
                                $olejKm = number_format($car['olejDoKm'],0,'',' ');
                            }
                    
                            echo '<b>Data: </b>' . $olej . @$dtOil;
                            echo '<br><b>Przy przebiegu: </b>' . $olejKm .' km'. @$dtOilKm;
                            ?>
                        </p>
                        <a href="#" class="btn btn-primary">Zmień</a>
                    </div>
                </div>
            </div>

            </div>
            
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