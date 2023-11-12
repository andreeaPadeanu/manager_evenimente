<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['eveniment_id'])) {
    $eveniment_id = $_POST['eveniment_id'];

    // Poți adăuga evenimentul în coș aici (într-o sesiune sau bază de date)

    if (!isset($_SESSION['cos'])) {
        $_SESSION['cos'] = array();
    }

    // Adaugă evenimentul în coș
    $_SESSION['cos'][] = $eveniment_id;

    // Poți să trimiți un răspuns către client
    echo 'Evenimentul a fost adăugat în coș.';
}
