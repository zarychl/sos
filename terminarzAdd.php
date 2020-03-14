<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania
if(!isset($_GET['date']))
{
    header("Location: terminarz.php");
}

if(isset($_POST['date']))
{
    addEvent($_POST['date'], $_POST['nazwa'], $_POST['opis'], $_POST['time']);
    header("Location: terminarzDay.php?date=". $_POST['date']);
}

$daysOfWeek = array('Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota');

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Dodaj wydarzenie w dniu <?php echo date("d.m.Y", strtotime($_GET['date'])) . " (" . $daysOfWeek[date("w", strtotime($_GET['date']))] . ")" ?></div>
            <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Data</label>
                    <input value="<?php echo $_GET['date']; ?>" name="date" type="date" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Godzina</label>
                    <input name="time" type="time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Nazwa</label>
                    <input name="nazwa" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Opis</label>
                    <textarea class="form-control" name="opis" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Dodaj</button>
            </form>
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