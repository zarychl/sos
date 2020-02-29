<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_POST['idkarty']))
{
    updateCardDysponent($_POST['idkarty'], $_POST['przychodnia'], $_POST['lekarz']);
    header("Location: przejazdy.php?idkarty=". $_POST['idkarty']);
}
if(!isset($_GET['idkarty']))
{
    header("Location: karty.php");
}
$karta = getKarta($_GET['idkarty']);

require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Dysponent - Dodaj/Edytuj </div>
          <div class="card-body">
          <form method="post">
          <input style="display:none;" value="<?php echo $_GET['idkarty']; ?>" name="idkarty" type="number" class="form-control" readonly>
          <div class="form-group">
                <label>Przychodnia</label>
                <input value="<?php echo $karta['przychodnia']; ?>" name="przychodnia" type="text"class="form-control">
            </div>
            <div class="form-group">
                <label>Lekarz</label>
                <input value="<?php echo $karta['lekarz']; ?>" name="lekarz" type="text"class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Edytuj</button>
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