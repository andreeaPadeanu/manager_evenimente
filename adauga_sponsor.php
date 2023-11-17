<?php
require('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adauga_sponsor'])) {
        $nume_speaker = $_POST['nume_sponsor'];
        $descriere_speaker = $_POST['descriere_sponsor'];

        // Validează și inserează speaker-ul nou în baza de date
        if (!empty($nume_speaker)) {
            $query = "INSERT INTO Sponsor (Nume, Descriere) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $nume_sponsor, $descriere_sponsor);

            if ($stmt->execute()) {
                // Redirecționează utilizatorul către o pagină de succes sau afișează un mesaj de succes
                echo "Sponsorul a fost adăugat cu succes!";
            } else {
                echo "Eroare la adăugarea sponsorului.";
            }
        } else {
            echo "Numele sponsorului este obligatoriu.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Adăugare Sponsor Nou</title>
</head>

<body>
    <h2>Adăugă un sponsor nou</h2>
    <form method="post" action="adauga_sponsor.php">
        <label for="nume_speaker">Numele sponsorului:</label>
        <input type="text" name="nume_spnsor" required><br>
        <label for="descriere_sponsor">Descrierea sponsorului:</label>
        <textarea name="descriere_sponsor"></textarea><br>
        <input type="submit" name="adauga_speaker" value="Adaugă noul sponsor">
    </form>

    <a href="admin.php">Înapoi la pagina principală</a>
</body>

</html>