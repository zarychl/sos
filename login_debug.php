<?php
session_start();

if(@$_GET['id'] == 1)
    $_SESSION['userID'] = 1;
else if(@$_GET['id'] == 2)
    $_SESSION['userID'] = 2;

if(isset($_GET['id']))
    header("Location: index.php");
?>

<a href="login_debug.php?id=1"><h2>Jeden </h2></a>
<a href="login_debug.php?id=2"><h2>Dwa </h2></a>