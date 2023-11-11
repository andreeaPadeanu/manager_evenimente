<?php
require('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adauga_speaker'])) {
        $nume_speaker = $_POST['nume_speaker'];
        $descriere_speaker = $_POST['descriere_speaker'];

        // Validează și inserează speaker-ul nou în baza de date
        if (!empty($nume_speaker)) {
            $query = "INSERT INTO Speaker (Nume, Descriere) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $nume_speaker, $descriere_speaker);

            if ($stmt->execute()) {
                // Redirecționează utilizatorul către o pagină de succes sau afișează un mesaj de succes
                echo "Speaker-ul a fost adăugat cu succes!";
            } else {
                echo "Eroare la adăugarea speaker-ului.";
            }
        } else {
            echo "Numele speaker-ului este obligatoriu.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Adăugare Speaker Nou</title>
</head>

<body>
    <h2>Adăugare Speaker Nou</h2>
    <form method="post" action="adauga_speaker.php">
        <label for="nume_speaker">Nume Speaker:</label>
        <input type="text" name="nume_speaker" required><br>
        <label for="descriere_speaker">Descriere Speaker:</label>
        <textarea name="descriere_speaker"></textarea><br>
        <input type="submit" name="adauga_speaker" value="Adaugă Speaker">
    </form>

    <a href="admin.php">Înapoi la Panou de Control pentru Administrator</a>
</body>

</html>