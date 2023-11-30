<?php

@include 'config.php';

@include 'decision_support.php';

session_start();
session_unset();
session_destroy();

header('location:index.php');

?>