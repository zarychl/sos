<?php
session_start();

require_once("includes/functions.php");
require_once("includes/db.php");

if(isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: karty.php");// to wyrzucamy go na stronę logowania

  if(isset($_POST['uname']))// użytkownik podał nazw uż.
  {
      $given_name = $_POST['uname'];
      $given_pass = $_POST['pass'];
      if(isset($_POST['r']))
      {
          $bool_r = 1;
      }
      else
      {
          $bool_r = 0;
      }
      GLOBAL $conn;
      $result = mysqli_query( $conn , "SELECT * FROM `users` WHERE mail = '$given_name';");
      if(mysqli_num_rows($result) != 0)
      {
          $row = mysqli_fetch_assoc($result);
          if(password_verify($given_pass, $row['pass']))
          {
                $_SESSION['userID'] = $row['id'];
              header("Location: /index.php");
          }
          else
          {
              $err = "Zła nazwa użytkownika i/lub hasło !";
          }
      }
      else
      {
          $err = "Zła nazwa użytkownika i/lub hasło !";
      }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SOS Ambulans - Logowanie</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header"><span class="text-center navbar-brand mr-1" href="index.html"><i class="fas fa-ambulance    "></i> SOS Ambulans</span><br>Logowanie</div>
      <div class="card-body">
        <form method="post">
          <div class="form-group">
            <div class="form-label-group">
              <input name="uname" type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
              <label for="inputEmail">Adres Email</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input name="pass" type="password" id="inputPassword" class="form-control" placeholder="Password" required="required">
              <label for="inputPassword">Hasło</label>
            </div>
          </div>
          <div style="display:none;" class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="r">
                Zapamiętaj mnie
              </label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Zaloguj</a>
        </form>
        <div class="text-center">
        <!--
          <a class="d-block small mt-3" href="register.html">Register an Account</a>
          <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
          -->
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

</body>

</html>
