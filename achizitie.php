<?php
require ('config.php');
session_start();

if (!isset ($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset ($_SESSION['cos'])) {
        foreach ($_SESSION['cos'] as $eveniment_id) {
            $query_nume_eveniment = "SELECT Nume_eveniment FROM Eveniment WHERE ID_eveniment = ?";
            $stmt_nume_eveniment = $conn->prepare($query_nume_eveniment);
            $stmt_nume_eveniment->bind_param("i", $eveniment_id);
            $stmt_nume_eveniment->execute();
            $result_nume_eveniment = $stmt_nume_eveniment->get_result();
            $row_nume_eveniment = $result_nume_eveniment->fetch_assoc();
            $nume_eveniment = $row_nume_eveniment['Nume_eveniment'];

            $cantitate = $_POST['cantitate_' . $eveniment_id];

            $user_id = $_SESSION['user_id'];
            $query = "INSERT INTO Rezervare (ID_user, ID_eveniment, Data_rezervare, Cantitate) VALUES (?, ?, NOW(), ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iii", $user_id, $eveniment_id, $cantitate);
            $stmt->execute();

            $mesaj_notificare = "Rezervarea dumneavoastră la evenimentul \"$nume_eveniment\" a fost realizată cu succes.";

            $query_notificare = "INSERT INTO Notificari (ID_user, mesaj, stare, data_creare) VALUES (?, ?, 'Trimisă', NOW())";
            $stmt_notificare = $conn->prepare($query_notificare);
            $stmt_notificare->bind_param("is", $user_id, $mesaj_notificare);
            $stmt_notificare->execute();
        }

        unset($_SESSION['cos']);

        header("Location: index.php");
        exit();
    }
}

