<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_POST['przyjazdGodz']))
{
    $time = $_POST['przyjazdGodz'];
    $mile = $_POST['przyjazdPrzebieg'];
    finishPrzejazd($_GET['id'], $time, $mile);
    $_SESSION["lastEditSuccess"] = 1;
}

if(!isset($_GET['id']) || !isset($_GET['idkarty']))
{
    header("Location: karty.php");
}
if(hasCardUnfinishedPrzejazd($_GET['idkarty']))
    $nieukonczony = getCardUnfinishedPrzejazd($_GET['idkarty']);
else
    header("Location: przejazdy.php?idkarty=" . $_GET['idkarty']);

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">

  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item active">Karta</li>
    <li class="breadcrumb-item active">Przejazdy</li>
    <li class="breadcrumb-item active">Dodaj przyjazd</li>
  </ol>
<?php
$time2 = strtotime($nieukonczony['wyjazdTime']);
?>
        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header">
            Dodaj przyjazd</div>
          <div class="card-body">
          <form method="post">
            <div class="form-group">
                <label>Wyjazd godz.</label>
                <input value="<?php echo date("H:i", $time2); ?>" type="text" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label>Wyjazd stan licznika</label>
                <input value="<?php echo $nieukonczony['wyjazdPrzebieg']; ?>" type="text" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label>Przyjazd godz.</label>
                <input name="przyjazdGodz" value="<?php echo date("H:i"); ?>" type="time" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Przyjazd stan licznika</label>
                <input name="przyjazdPrzebieg" type="number" value="<?php echo $nieukonczony['wyjazdPrzebieg']; ?>" min="<?php echo $nieukonczony['wyjazdPrzebieg']; ?>" max="999999" step="1" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj przyjazd</button>
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