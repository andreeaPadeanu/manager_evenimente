<?php
require('config.php');
session_start();

// Verifică dacă utilizatorul este autentificat ca administrator
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adauga_eveniment'])) {
    $nume_eveniment = $_POST['nume_eveniment'];
    $data_eveniment = $_POST['data_eveniment'];
    $ora_eveniment = $_POST['ora_eveniment'];
    $tip_eveniment = $_POST['tip_eveniment'];
    $locatie_eveniment = $_POST['locatie_eveniment'];
    $descriere_eveniment = $_POST['descriere_eveniment'];
    $sponsor = $_POST['sponsor'];
    $speaker = $_POST['speaker'];
    $partener = $_POST['partener'];
    $pret = $_POST['pret'];

    // Inserează evenimentul în baza de date
    $query_insert_eveniment = "INSERT INTO Eveniment (Nume_eveniment, Data, Ora, Tip, Locatie, Descriere_eveniment, Pret) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_eveniment = $conn->prepare($query_insert_eveniment);
    $stmt_insert_eveniment->bind_param("ssssssd", $nume_eveniment, $data_eveniment, $ora_eveniment, $tip_eveniment, $locatie_eveniment, $descriere_eveniment, $pret);

    if ($stmt_insert_eveniment->execute()) {



        $last_event_id = $conn->insert_id;

        // Actualizează evenimentul cu sponsorul, speakerul și partenerul selectați
        $query_update_eveniment = "UPDATE Eveniment SET ID_sponsor = (SELECT ID_sponsor FROM Sponsor WHERE Nume = ?), 
                                                            ID_speaker = (SELECT ID_speaker FROM Speaker WHERE Nume = ?), 
                                                            ID_partener = (SELECT ID_partener FROM Partener WHERE Nume = ?) 
                                    WHERE ID_eveniment = ?";
        $stmt_update_eveniment = $conn->prepare($query_update_eveniment);
        $stmt_update_eveniment->bind_param("sssi", $sponsor, $speaker, $partener, $last_event_id);
        $stmt_update_eveniment->execute();
        $stmt_update_eveniment->close();

    }
    $stmt_insert_eveniment->close();
}


?>

<!DOCTYPE html>
<html lang="ro">

<head>
<link href="adauga_eveniment.css" rel="stylesheet" type="text/css">

    <title>Adaugă Eveniment</title>
</head>

<body>

    <h2>Adaugă un eveniment nou</h2>
    <form method="post" action="adauga_eveniment.php">
        <label for="nume_eveniment">Nume eveniment:</label>
        <input type="text" name="nume_eveniment" required>
        <br>
        <label for="data_eveniment">Data eveniment:</label>
        <input type="date" name="data_eveniment" required>
        <br>
        <label for="ora_eveniment">Ora eveniment:</label>
        <input type="time" name="ora_eveniment" required>
        <br>
        <label for="tip_eveniment">Tip eveniment:</label>
        <input type="text" name="tip_eveniment" required>
        <br>
        <label for="locatie_eveniment">Locație eveniment:</label>
        <input type="text" name="locatie_eveniment" required>
        <br>
        <label for="descriere_eveniment">Descriere eveniment:</label>
        <textarea name="descriere_eveniment" rows="4" required></textarea>
        <br>
        <label for="pret">Preț bilet:</label>
        <input type="number" name="pret" step="0.01" required>
        <br>
        <label for="sponsor">Sponsor:</label>
        <select name="sponsor" required>
            <?php
            $query_sponsori = "SELECT Nume FROM Sponsor";
            $result_sponsori = $conn->query($query_sponsori);

            while ($row = $result_sponsori->fetch_assoc()) {
                echo "<option value=\"" . $row['Nume'] . "\">" . $row['Nume'] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="speaker">Speaker:</label>
        <select name="speaker" required>
            <?php
            $query_speakeri = "SELECT Nume FROM Speaker";
            $result_speakeri = $conn->query($query_speakeri);

            while ($row = $result_speakeri->fetch_assoc()) {
                echo "<option value=\"" . $row['Nume'] . "\">" . $row['Nume'] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="partener">Partener:</label>
        <select name="partener" required>
            <?php
            $query_parteneri = "SELECT Nume FROM Partener";
            $result_parteneri = $conn->query($query_parteneri);

            while ($row = $result_parteneri->fetch_assoc()) {
                echo "<option value=\"" . $row['Nume'] . "\">" . $row['Nume'] . "</option>";
            }
            ?>
        </select>
        <br>
        <input type="submit" name="adauga_eveniment" value="Adaugă eveniment">
    </form>

    <a href="admin.php" class="inapoi">Înapoi la pagina principală</a>


</body>

</html>