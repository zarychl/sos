<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_POST['paliwo']))
{
    addFueling($_POST['idkarty'],$_POST['paliwo'],$_POST['koszt'], $_POST['faktura']);
    header("Location: przejazdy.php?idkarty=". $_POST['idkarty']);
}

if(!isset($_GET['idkarty']))
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
            Dodaj tankowanie</div>
          <div class="card-body">
          <form method="post">
          <input style="display:none;" value="<?php echo $_GET['idkarty']; ?>" name="idkarty" type="number" class="form-control" readonly>
          <div class="form-group">
                <label>Ilość paliwa [litry]</label>
                <input name="paliwo" type="number" min="0" max="99999.99" step="0.01" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Koszt [złotych]</label>
                <input name="koszt" type="number" min="0" max="99999.99" step="0.01" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nr faktury</label>
                <input name="faktura" type="text" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj tankowanie</button>
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