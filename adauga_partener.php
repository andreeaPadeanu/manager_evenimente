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
    <link href="adauga_partener.css" rel="stylesheet" type="text/css">
    <title>Adăugă un partener nou</title>
</head>

<body>

<main>
    <h2>Adăugă un partener nou</h2>
    <form method="post" action="adauga_partener.php">
        <label for="nume_partener">Numele partenerului:</label>
        <input type="text" name="nume_partener" required><br>
        <label for="descriere_partener">Descrierea partenerului:</label>
        <textarea name="descriere_partener"></textarea><br>
        <input type="submit" name="adauga_partener" value="Adaugă noul partener">
    </form>
    <a href="admin.php" class="inapoi">Înapoi la pagina principală</a>
</main>
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?>  Eventica. Toate drepturile rezervate</p>
    </div>
</body>

</html>