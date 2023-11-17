<?php
require('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesează formularul de achiziție
    if (isset($_SESSION['cos'])) {
        foreach ($_SESSION['cos'] as $eveniment_id) {
            // Obține numele evenimentului
            $query_nume_eveniment = "SELECT Nume_eveniment FROM Eveniment WHERE ID_eveniment = ?";
            $stmt_nume_eveniment = $conn->prepare($query_nume_eveniment);
            $stmt_nume_eveniment->bind_param("i", $eveniment_id);
            $stmt_nume_eveniment->execute();
            $result_nume_eveniment = $stmt_nume_eveniment->get_result();
            $row_nume_eveniment = $result_nume_eveniment->fetch_assoc();
            $nume_eveniment = $row_nume_eveniment['Nume_eveniment'];

            // Obține cantitatea și tipul biletului din formular
            $cantitate = $_POST['cantitate_' . $eveniment_id];


            // Inserează rezervarea în baza de date
            $user_id = $_SESSION['user_id'];
            $query = "INSERT INTO Rezervare (ID_user, ID_eveniment, Data_rezervare, Cantitate) VALUES (?, ?, NOW(), ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iii", $user_id, $eveniment_id, $cantitate);
            $stmt->execute();

            // Trimitere notificare către utilizator
            $mesaj_notificare = "Rezervarea dumneavoastră la evenimentul \"$nume_eveniment\" a fost realizată cu succes.";
            // Aici puteți utiliza un sistem de notificări sau trimite un email către utilizator
            // Exemplu: mail($_SESSION['email'], 'Notificare rezervare', $mesaj_notificare);
            // Sau adăugați notificarea într-un tabel de notificări în baza de date
            $query_notificare = "INSERT INTO Notificari (ID_user, mesaj, stare, data_creare) VALUES (?, ?, 'Trimisă', NOW())";
            $stmt_notificare = $conn->prepare($query_notificare);
            $stmt_notificare->bind_param("is", $user_id, $mesaj_notificare);
            $stmt_notificare->execute();
        }

        // Șterge evenimentele din coș după achiziție
        unset($_SESSION['cos']);

        // Redirecționează utilizatorul către pagina "index.php" după achiziție
        header("Location: index.php");
        exit();
    }
}

// Afișați notificările aici sau redirecționați utilizatorul înapoi la pagina "cos.php" cu notificări
// Alegeți metoda preferată de afișare a notificărilor sau de redirecționare.
// Exemplu: header("Location: cos.php?notificare=1"); sau afișarea notificărilor direct pe această pagină.
?>