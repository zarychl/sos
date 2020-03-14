<?php
require_once(dirname(__DIR__)."/includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: /login.php");// to wyrzucamy go na stronę logowania

if(getUserAdminLvl($_SESSION['userID']) == 0)// jak nie jest adminem to tez
{
    header("Location: /index.php");
}

require_once(dirname(__DIR__)."/includes/navbar.php");
require_once(dirname(__DIR__)."/includes/sidebar.php");

GLOBAL $conn;

$result = mysqli_query($conn, "SELECT * FROM log;");
if(mysqli_num_rows($result))
{
  $log = array();
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($log,$row);
  }
}
else $log = 0;
?>

<div id="content-wrapper">

<div class="container-fluid">


        <!-- Area Chart Example-->
        <div class="card mb-3">
          <div class="card-header bg-success text-white font-weight-bold">
            Logi systemowe
            </div>
            <div class="card-body">
            <?php
              if($log)
              {
                echo '
                <table class="table">
                <thead>
                <tr>
                <th>ID</th><th>Co sie stałosie</th><th>Kto [ID]</th><th>Kiedy</th>
                </tr>
                </thead>
                <tbody>
                ';

                foreach($log as $key => $value)
                {
                  echo '<tr>';

                  echo '<td>'. $log[$key]['id'] .'</td>';
                  echo '<td>'. $log[$key]['name'] .'</td>';
                  echo '<td>'. $log[$key]['who'] .'</td>';
                  echo '<td>'. $log[$key]['time'] .'</td>';

                  echo '</tr>';
                }

                echo '
                </tbody>
                </table>
                ';
              }
              else
              {
                echo '<div class="alert alert-warning" role="alert">
                      Brak logów !
                      </div>';
              }
            ?>
            </table>
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