<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(getUserAdminLvl($_SESSION['userID']) == 0)
{
    header("Location: /index.php");
}
if(!isset($_POST['car']))
  $dateVisible = "disabled";
else
  $dateVisible = "";

function getOptionsCars()
{
  $cars = getUsedCarsAnytime();

  foreach($cars as $key => $value)
  {
    echo "<option value='". $cars[$key]['id'] ."'>". $cars[$key]['nazwa'] . " [" . $cars[$key]['tablica'] ."]</option>";
  }
}

function getOptionsDates()
{
  $months = array('','Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień', 'Październik', 'Listopad', 'Grudzień');
  if(isset($_POST['car']))
  {
    $dates = getCarUsedDates($_POST['car']);
    foreach($dates as $key => $value)
    {
      $data = explode("-", $dates[$key]['data']);

      echo "<option value='". $dates[$key]['data'] ."'>". $months[intval($data[1])] ." ". $data[0] ."</option>";
    }
  }
}

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">
<script>
function getval(sel)
{
  $(document).ready(function() {
    if(sel.value != 'none')
    {
      $('#printB').show();
      $('#dateSelect').prop("disabled", false);
      $.post('kartyDrogowe.php', {car:sel.value}, function (data) {
        $('#dateSelect').html($(data).find("#dateSelect").html());
      });
    }
    else
    {
      $('#dateSelect').prop("disabled", true);
      $('#printB').hide();
    }
  });
}
function print()
{

  var year = document.getElementById("dateSelect").value.substring(0, 4);
  var month = document.getElementById("dateSelect").value.substring(5, 7)*1;
  var carid = document.getElementById("carSelect").value;
  document.location.href = "/printPojazd.php?y="+year+"&m="+month+"&car="+carid;
}
</script>
<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
        
            <div class="card-header bg-success text-white font-weight-bold">
                Drukowanie karty drogowej
            </div>
            <div class="card-body">

            <div class="form-group">
                <label  for="exampleInputEmail1">Dla: </label>
                <select id="carSelect" class="form-control"  name="cars" onchange="getval(this);">
                    <option value="none" selected>--- WYBIERZ ---</option>
                    <?php getOptionsCars(); ?>
                </select>
            </div>
            <div class="form-group">
                <label  for="exampleInputEmail1">Miesiąc: </label>
                <select id="dateSelect" class="form-control"  name="date" <?php echo $dateVisible; ?>>
                  <?php getOptionsDates(@$_POST['car']); ?>
                </select>
            </div>
            <button id="printB" style="display:none;" type="button" onclick="print();" class="btn btn-primary"><i class="fas fa-print    "></i> Wydrukuj</button>
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