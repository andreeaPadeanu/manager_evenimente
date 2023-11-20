<?php
require('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$evenimente = array();

// Obține toate evenimentele din baza de date
$query = "SELECT ID_eveniment, Nume_eveniment FROM Eveniment";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $evenimente[$row['ID_eveniment']] = $row['Nume_eveniment'];
    }
}

$evenimentID = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['sterge_event'])) {
        $evenimentID = $_POST['eveniment_selectat'];
    }

    if (isset($_POST['confirm_stergere']) && isset($_POST['eveniment_id'])) {
        // Utilizatorul a confirmat ștergerea, deci ștergem evenimentul
        $evenimentID = $_POST['eveniment_id'];

        $query = "DELETE FROM Eveniment WHERE ID_eveniment = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $evenimentID);

        if ($stmt->execute()) {
            // Redirecționează utilizatorul către o pagină de succes sau afișează un mesaj de succes
            echo "Evenimentul a fost șters cu succes!";
        } else {
            echo "Eroare la ștergerea evenimentului.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<link href="sterge_eveniment.css" rel="stylesheet" type="text/css">

    <title>Ștergeți un eveniment</title>
    <script>
        function confirmaStergere() {
            var confirmare = confirm("Ești sigur că dorești să ștergi acest eveniment?");
            if (confirmare) {
                // Utilizatorul a confirmat, trimite formularul pentru ștergere
                document.getElementById("form_stergere").submit();
            }
        }
    </script>
</head>

<body>
    <h2>Ștergeți un eveniment</h2>
    <form method="post" action="sterge_eveniment.php" id="form_stergere">
        <select name="eveniment_selectat" required>
            <?php foreach ($evenimente as $id => $nume): ?>
                <option value="<?php echo $id; ?>">
                    <?php echo $nume; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="sterge_event" value="Șterge Eveniment" onclick="confirmaStergere()">
    </form>

    <a href="admin.php" class="inapoi">Înapoi la pagina principală </a>
</body>

</html>