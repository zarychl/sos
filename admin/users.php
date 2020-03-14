<?php
require_once(dirname(__DIR__)."/includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: /login.php");// to wyrzucamy go na stronę logowania
if(getUserAdminLvl($_SESSION['userID']) == 0)
{
    header("Location: /index.php");
}

require_once(dirname(__DIR__)."/includes/navbar.php");
require_once(dirname(__DIR__)."/includes/sidebar.php");
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Profil
            </div>
            <div class="card-body">
            
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