<?php
session_start();
$_SESSION = array();
session_destroy(); //distruggo tutte le sessioni

//ritorno a index.php 
header("location: index.php");
exit();
?>
