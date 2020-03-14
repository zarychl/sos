<?php
require_once("includes/head.php");

if(!isUserLoggedIn())//jeśli użytkownik nie jest zalogowany
  header("Location: login.php");// to wyrzucamy go na stronę logowania

if(isset($_GET['id']))
{
    deleteEvent($_GET['id']);
    header("Location: terminarzDay.php?date=". $_SESSION['lastVievedDay']);
}
else
{
    header("Location: terminarz.php");
}
?>