<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_GET['idkarty']))
{
    finilizeCard($_GET['idkarty']);
    header("Location: przejazdy.php?idkarty=". $_GET['idkarty']);
}
else
{
    header("Location: karty.php");
}
?>