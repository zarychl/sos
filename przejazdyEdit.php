<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

$p = getPrzejazd($_GET['id']);
if($p['pacjent'] != "")
{
  $hasPacjent = 1;
}
else $hasPacjent = 0;

$stage = 0;
if($p['przyjazdPrzebieg'])
{
    $stage = 1;
}

if(isset($_POST['wyjazdGodz']))
{
    
    $time = $_POST['wyjazdGodz'];
    $mile = $_POST['wyjazdPrzebieg'];
    $skad = $_POST['skad'];
    $dokad = $_POST['dokad'];
    $time2 = $_POST['przyjazdGodz'];
    $mile2 = $_POST['przyjazdPrzebieg'];
    $pacjent = "";
    if(@$_POST['pacjentCheck'] == 1)
    {
      $pacjent = $_POST['pacjentName'];
    }
    if($stage)
    {
      editPrzejazd($_POST['idPrzejazd'], $skad, $dokad, $time, $time2, $mile, $mile2, $pacjent);
    }
    else
    {
      editPrzejazd($_POST['idPrzejazd'], $skad, $dokad, $time, "", $mile, "", $pacjent);
    }

    header("Location: przejazdy.php?idkarty=". $_POST['idKarty']);
}

if(!isset($_GET['id']))
{
    header("Location: karty.php");
}
require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Edytuj przejazd</div>
          <div class="card-body">
          <form method="post">
          <input style="display:none;" name="idPrzejazd" value="<?php echo $_GET['id']; ?>" type="number" readonly>
          <input style="display:none;" name="idKarty" value="<?php echo $_GET['idKarty']; ?>" type="number" readonly>
          <div class="row">
            <div class="col">
                <div class="form-group">
                    <label>Z</label>
                    <input id="skad" value="<?php echo $p['skad']; ?>" name="skad" type="text" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Do</label>
                    <input id="dokad" name="dokad" value="<?php echo $p['dokad']; ?>" type="text" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Wyjazd godz.</label>
                    <input name="wyjazdGodz" value="<?php echo substr($p['wyjazdTime'],0,5); ?>" type="time" class="form-control" >
                </div>
                <div class="form-group">
                    <label>Wyjazd stan licznika</label>
                    <input name="wyjazdPrzebieg" value="<?php echo $p['wyjazdPrzebieg']; ?>" max="999999" step="1" min="
                    " type="number" class="form-control" >
                </div>

                <div class="form-group <?php if(!$stage) echo "d-none" ?>">
                    <label>Przyjazd godz.</label>
                    <input name="przyjazdGodz" value="<?php echo substr($p['przyjazdTime'],0,5); ?>" type="time" class="form-control <?php if(!$stage) echo "d-none" ?>" <?php if(!$stage) echo "disabled" ?>>
                </div>
                <div class="form-group <?php if(!$stage) echo "d-none" ?>">
                    <label>Przyjazd stan licznika</label>
                    <input name="przyjazdPrzebieg" value="<?php echo $p['przyjazdPrzebieg']; ?>" max="999999" step="1" min="" type="number" class="form-control" <?php if(!$stage) echo "disabled" ?>>
                </div>

                <button type="submit" class="btn btn-primary">Edytuj przejazd</button>
              </div>
              <div class="col">
                <div class="form-check">
                  <input name="pacjentCheck" class="form-check-input" type="checkbox" value="1" id="pacjentCheck" <?php if($hasPacjent) echo 'checked'; ?>>
                  <span>Pacjent ?</span>
                </div>
                <div class="form-group">
                    <input value="<?php if($hasPacjent) echo $p['pacjent']; ?>" placeholder="Imię i nazwisko" name="pacjentName" type="text" class="form-control" id="pacjentName" style="display: none;">
                </div>
              </div>
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