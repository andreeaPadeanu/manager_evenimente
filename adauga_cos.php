<?php
require ('config.php');

function startSessionIfNotStarted()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

if (!defined('PHPUNIT_RUNNING')) {
    startSessionIfNotStarted();
}

if (!isset ($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset ($_POST['eveniment_id'])) {
    $eveniment_id = $_POST['eveniment_id'];

    if (!isset ($_SESSION['cos'])) {
        $_SESSION['cos'] = array();
    }

    if (!in_array($eveniment_id, $_SESSION['cos'])) {
        $_SESSION['cos'][] = $eveniment_id;
        echo 'Evenimentul a fost adăugat în coș.';
    } else {
        echo 'Evenimentul există deja în coș.';
    }
}
