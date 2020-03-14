<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_GET['userid']))
{
    if(getUserAdminLvl($_SESSION['userID']) == 0)
    {
        header("Location: profile.php");
    }
    else $vUserID = $_GET['userid'];
}
else $vUserID = $_SESSION['userID'];

$user = getStaff($vUserID);
require_once("includes/navbar.php");
require_once("includes/sidebar.php");
?>

<div id="content-wrapper">
<script>

</script>
<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
        
          <div class="card-header bg-success text-white font-weight-bold">

            <?php
            if(!isset($_GET['userid']))
            {
                echo "Twój profil";
            }
            else echo "Profil użytkownika " . $user['imie'] . " " . $user['nazwisko'] . " (ID: " . $user['id'] . ")";

            if(!$user['ratownik'] && $user['admin_lvl'])
            {
                echo " <span class='badge badge-info'>Administrator</span>";
            }
            ?>
            </div>
          <div class="card-body">
            <ul>
            <li><b>ID: </b><?php echo $user['id']; ?></li>
            <li><b>Imię: </b><?php echo $user['imie']; ?></li>
            <li><b>Nazwisko: </b><?php echo $user['nazwisko']; ?></li>
            <li><b>Adres e-Mail: </b><?php echo $user['mail']; ?></li>
            <?php if($user['ratownik'])
            echo '
            <hr>
            <li><b>Całkowita liczba wyjazdów: </b> ' . countUserWyjazdy($user['id']) . '</li>

            ';
            ?>
            </ul>
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