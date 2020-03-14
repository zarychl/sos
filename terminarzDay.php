<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania
if(!isset($_GET['date']))
{
    header("Location: terminarz.php");
}
$_SESSION['lastVievedDay'] = $_GET['date'];
$daysOfWeek = array('Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota');

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Wydarzenia w dniu <?php echo date("d.m.Y", strtotime($_GET['date'])) . " (" . $daysOfWeek[date("w", strtotime($_GET['date']))] . ")" ?></div>
            <div class="card-body">
            <a role="button" href="terminarzAdd.php?date=<?php echo $_GET['date']; ?>" class="btn btn-success"><i class="fas fa-plus-circle    "></i> Dodaj</a>
            <hr>
            <?php
                $events = getEventsByDay($_GET['date']);
                if($events)// sprawdzamy czy w tym dniu są w ogóle jakieś eventy
                {
                    foreach($events as $key => $value)
                    {
                        $time1 = explode(":" , $events[$key]['time']);
                        echo '<h3>' . $events[$key]['nazwa'] . ' <a style="font-size:11pt;color:red;" href="#" onclick="deleteEventConf('. $events[$key]['id'] .')">Usuń</a></h3><ul>';
                        echo '<b><li>Godzina: </b>' . date("H:i", mktime($time1[0],$time1[1])) . '</li>';
                        echo '<b><li>Opis: </b>' . $events[$key]['opis'] . '</li></ul>';
                    }
                }
                else// jeśli nie to informujemy o tym usera
                {
                    echo '<div class="alert alert-info" role="alert">
                    Brak wydarzeń w tym dniu !
                    </div>';
                }
            ?>
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