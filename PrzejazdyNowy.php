<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_POST['wyjazdGodz']))
{
    $time = $_POST['wyjazdGodz'];
    $mile = $_POST['wyjazdPrzebieg'];
    $skad = $_POST['skad'];
    $dokad = $_POST['dokad'];
    initPrzejazd($_GET['idkarty'], $skad, $dokad, $time, $mile);
    $_SESSION["lastInitSuccess"] = 1;
    header("Location: przejazdy.php?idkarty=". $_GET['idkarty']);
}

if(!isset($_GET['idkarty']))
{
    header("Location: karty.php");
}

$karta = getKarta($_GET['idkarty']);
$car = getCar($karta['car_id']);
require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Dodaj wyjazd</div>
          <div class="card-body">
          <form method="post">
          <div class="form-group">
                <label>Z</label>
                <input value="<?php echo getCardLastLocation($_GET['idkarty']); ?>" name="skad" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Do</label>
                <input name="dokad" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Wyjazd godz.</label>
                <input name="wyjazdGodz" value="<?php echo date("H:i"); ?>" type="time" class="form-control" >
            </div>
            <div class="form-group">
                <label>Wyjazd stan licznika</label>
                <input name="wyjazdPrzebieg" value="<?php echo $car['przebieg']; ?>" max="999999" step="1" min="<?php echo $car['przebieg']; ?>" type="number" class="form-control" >
            </div>
            <button type="submit" class="btn btn-primary">Dodaj wyjazd</button>
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