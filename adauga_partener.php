<?php
require('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['adauga_partener'])) {
        $nume_partener = $_POST['nume_partener'];
        $descriere_partener = $_POST['descriere_partener'];

        if (!empty($nume_partener)) {
            $query = "INSERT INTO Partener (Nume, Descriere) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $nume_partener, $descriere_partener);

            if ($stmt->execute()) {
                // Redirecționează utilizatorul către o pagină de succes sau afișează un mesaj de succes
                echo "Partenerul a fost adăugat cu succes!";
            } else {
                echo "Eroare la adăugarea partenerului.";
            }
        } else {
            echo "Numele partenerului este obligatoriu.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Adăugare Partener Nou</title>
</head>

<body>
    <h2>Adăugare Partener Nou</h2>
    <form method="post" action="adauga_partener.php">
        <label for="nume_partener">Nume Partener:</label>
        <input type="text" name="nume_partener" required><br>
        <label for="descriere_partener">Descriere Partener:</label>
        <textarea name="descriere_partener"></textarea><br>
        <input type="submit" name="adauga_partener" value="Adaugă Partener">
    </form>

    <a href="admin.php">Înapoi la Panou de Control pentru Administrator</a>
</body>

</html>