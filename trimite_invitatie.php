<?php
require('config.php');
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$mesaj = ""; // Mesajul de confirmare

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['trimite_invitatii'])) {
        $nume_eveniment = $_POST['eveniment'];
        $utilizatori_selectati = $_POST['utilizatori_selectati'];

        // Obține ID-urile evenimentelor în funcție de nume
        $query_eveniment_id = "SELECT ID_eveniment FROM Eveniment WHERE Nume_eveniment = ?";
        $stmt_eveniment_id = $conn->prepare($query_eveniment_id);
        $stmt_eveniment_id->bind_param("s", $nume_eveniment);
        $stmt_eveniment_id->execute();
        $stmt_eveniment_id->bind_result($eveniment_id);
        $stmt_eveniment_id->fetch();
        $stmt_eveniment_id->close();

        if ($eveniment_id) {
            // Obține detalii despre eveniment din baza de date
            $query_eveniment = "SELECT Nume_eveniment, Data, Ora, Locatie FROM Eveniment WHERE ID_eveniment = ?";
            $stmt_eveniment = $conn->prepare($query_eveniment);
            $stmt_eveniment->bind_param("i", $eveniment_id);
            $stmt_eveniment->execute();
            $stmt_eveniment->bind_result($nume_eveniment, $data_eveniment, $ora_eveniment, $locatie_eveniment);
            $stmt_eveniment->fetch();
            $stmt_eveniment->close();

            // Construiește mesajul invitației
            $mesaj_invitatie = "Suntem bucuroși să vă invităm la evenimentul nostru: $nume_eveniment\n";
            $mesaj_invitatie .= "Data: $data_eveniment, Ora: $ora_eveniment, Locație: $locatie_eveniment\n";

            // Inserează notificări pentru utilizatorii selectați
            foreach ($utilizatori_selectati as $utilizator_id) {
                // Inserează notificarea în baza de date
                $query_insert_notificare = "INSERT INTO Notificari (ID_user, ID_eveniment, mesaj, stare, data_creare) VALUES (?, ?, ?, 'Trimisă', NOW())";
                $stmt_insert_notificare = $conn->prepare($query_insert_notificare);
                $stmt_insert_notificare->bind_param("iis", $utilizator_id, $eveniment_id, $mesaj_invitatie);
                $stmt_insert_notificare->execute();
                $stmt_insert_notificare->close();
            }

            $mesaj = "Invitația a fost trimisă cu succes!";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<link href="trimite_invitatie.css" rel="stylesheet" type="text/css">

    <script>
        
        function showPopup(message) {
            alert(message);
        }
    </script>

    <title>Trimite invitații</title>
</head>

<body>
    <h2>Trimite invitații la un eveniment</h2>
    <form method="post" action="trimite_invitatie.php">
        <label for="eveniment">Selectează evenimentul:</label>
        <select name="eveniment" required>
            <?php
            // Obține toate evenimentele din baza de date
            $query_evenimente = "SELECT Nume_eveniment FROM Eveniment";
            $result_evenimente = $conn->query($query_evenimente);

            while ($row_evenimente = $result_evenimente->fetch_assoc()) {
                echo "<option value='" . $row_evenimente['Nume_eveniment'] . "'>" . $row_evenimente['Nume_eveniment'] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="utilizatori_selectati">Selectează utilizatori:</label>
        <select name="utilizatori_selectati[]" multiple required>
            <?php
            // Obține toți utilizatorii din baza de date
            $query_utilizatori = "SELECT ID_user, Nume, Prenume FROM User";
            $result_utilizatori = $conn->query($query_utilizatori);

            while ($row_utilizatori = $result_utilizatori->fetch_assoc()) {
                echo "<option value='" . $row_utilizatori['ID_user'] . "'>" . $row_utilizatori['Nume'] . " " . $row_utilizatori['Prenume'] . "</option>";
            }
            ?>
        </select>
        <br>
        <input type="submit" name="trimite_invitatii" value="Trimite invitație">
    </form>

    <script>
        <?php
        if (!empty($mesaj)) {
            echo "showPopup('" . addslashes($mesaj) . "');";
        }
        ?>
    </script>

    <a href="admin.php">Înapoi la pagina principală </a>
</body>

</html>