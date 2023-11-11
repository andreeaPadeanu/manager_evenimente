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
        $eveniment_id = $_POST['eveniment_id'];
        $utilizatori_selectati = $_POST['utilizatori_selectati'];

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

        $mesaj = "Invitațiile au fost trimise cu succes și notificările au fost înregistrate!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Trimite Invitații</title>
</head>

<body>
    <h2>Trimite Invitații la Eveniment</h2>
    <form method="post" action="trimite_invitatie.php">
        <label for="eveniment_id">Selectează Eveniment:</label>
        <select name="eveniment_id" required>
            <?php
            // Obține toate evenimentele din baza de date
            $query_evenimente = "SELECT ID_eveniment, Nume_eveniment FROM Eveniment";
            $result_evenimente = $conn->query($query_evenimente);

            while ($row_evenimente = $result_evenimente->fetch_assoc()) {
                echo "<option value='" . $row_evenimente['ID_eveniment'] . "'>" . $row_evenimente['Nume_eveniment'] . "</option>";
            }
            ?>
        </select>
        <br>
        <label for="utilizatori_selectati">Selectează Utilizatori:</label>
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
        <input type="submit" name="trimite_invitatii" value="Trimite Invitații">
    </form>

    <p>
        <?php echo $mesaj; ?>
    </p> <!-- Afișează mesajul de confirmare -->

    <a href="admin.php">Înapoi la Panou de Control pentru Administrator</a>
</body>

</html>