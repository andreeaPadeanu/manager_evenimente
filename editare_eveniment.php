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
    if (isset($_POST['edit_event'])) {
        $evenimentID = $_POST['eveniment_selectat'];
    }

    if (isset($_POST['salveaza_editare'])) {
        $evenimentID = $_POST['eveniment_id'];

        $nume_eveniment_nou = $_POST['nume_eveniment'];
        $data_noua = $_POST['data'];
        $ora_noua = $_POST['ora'];
        $tip_nou = $_POST['tip'];
        $locatie_noua = $_POST['locatie'];
        $descriere_noua = $_POST['descriere'];
        $pret_nou = $_POST['pret'];

        $query = "UPDATE Eveniment SET Nume_eveniment = ?, Data = ?, Ora = ?, Tip = ?, Locatie = ?, Descriere_eveniment = ?, Pret = ? WHERE ID_eveniment = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssdi", $nume_eveniment_nou, $data_noua, $ora_noua, $tip_nou, $locatie_noua, $descriere_noua, $pret_nou, $evenimentID);

        if ($stmt->execute()) {
            echo "Modificările au fost salvate cu succes!";
        } else {
            echo "Eroare la salvarea modificărilor.";
        }
    }
}

$detalii_eveniment = array();

if (!is_null($evenimentID)) {
    $query = "SELECT * FROM Eveniment WHERE ID_eveniment = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evenimentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $detalii_eveniment = array(
            'Nume_eveniment' => $row['Nume_eveniment'],
            'Data' => $row['Data'],
            'Ora' => $row['Ora'],
            'Tip' => $row['Tip'],
            'Locatie' => $row['Locatie'],
            'Descriere' => $row['Descriere_eveniment'],
            'Pret' => $row['Pret'],
            'ID_sponsor' => $row['ID_sponsor'],
            'ID_speaker' => $row['ID_speaker'],
            'ID_partener' => $row['ID_partener'],
        );
    }
}
?>

<!DOCTYPE html>
<html>

<head>
<link href="editare_eveniment.css" rel="stylesheet" type="text/css">
    <title>Editează un eveniment</title>
</head>

<body>

    <h2>Editează un eveniment</h2>
    <form method="post" action="editare_eveniment.php">
        <select name="eveniment_selectat" required>
            <?php foreach ($evenimente as $id => $nume): ?>
                <option value="<?php echo $id; ?>">
                    <?php echo $nume; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="edit_event" value="Editează evenimentul">
    </form>

    <?php if (!empty($detalii_eveniment)): ?>
        <div>
            <h3>Detaliile actuale despre eveniment</h3>
            Numele evenimentului:
            <?php echo $detalii_eveniment['Nume_eveniment']; ?><br>
            Data:
            <?php echo $detalii_eveniment['Data']; ?><br>
            Ora:
            <?php echo $detalii_eveniment['Ora']; ?><br>
            Tip:
            <?php echo $detalii_eveniment['Tip']; ?><br>
            Locație:
            <?php echo $detalii_eveniment['Locatie']; ?><br>
            Descriere:
            <?php echo $detalii_eveniment['Descriere']; ?><br>
            Pret:
            <?php echo $detalii_eveniment['Pret']; ?><br>
            ID Sponsor:
            <?php echo $detalii_eveniment['ID_sponsor']; ?><br>
            ID Speaker:
            <?php echo $detalii_eveniment['ID_speaker']; ?><br>
            ID Partener:
            <?php echo $detalii_eveniment['ID_partener']; ?><br>

            <h3>Editare Eveniment</h3>
            <form method="post" action="editare_eveniment.php">
                Nume eveniment: <input type="text" name="nume_eveniment"
                    value="<?php echo $detalii_eveniment['Nume_eveniment']; ?>"><br>
                Data: <input type="date" name="data" value="<?php echo $detalii_eveniment['Data']; ?>"><br>
                Ora: <input type="time" name="ora" value="<?php echo $detalii_eveniment['Ora']; ?>"><br>
                Tip: <input type="text" name="tip" value="<?php echo $detalii_eveniment['Tip']; ?>"><br>
                Locație: <input type="text" name="locatie" value="<?php echo $detalii_eveniment['Locatie']; ?>"><br>
                Pret: <input type="number" name="pret" value="<?php echo $detalii_eveniment['Pret']; ?>"><br>
                Descriere: <textarea name="descriere"><?php echo $detalii_eveniment['Descriere']; ?></textarea><br>
                ID Sponsor: <input type="text" name="id_sponsor"
                    value="<?php echo $detalii_eveniment['ID_sponsor']; ?>"><br>
                ID Speaker: <input type="text" name="id_speaker"
                    value="<?php echo $detalii_eveniment['ID_speaker']; ?>"><br>
                ID Partener: <input type="text" name="id_partener"
                    value="<?php echo $detalii_eveniment['ID_partener']; ?>"><br>

                <input type="hidden" name="eveniment_id" value="<?php echo $evenimentID; ?>">
                <input type="submit" name="salveaza_editare" value="Salvează modificările">
            </form>
        </div>
    <?php endif; ?>

    <a href="admin.php" class="inapoi">Înapoi la pagina principală</a>

</body>

</html>