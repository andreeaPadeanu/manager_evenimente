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
        $evenimentID = $_POST['eveniment_id']; // Aici obținem ID-ul evenimentului care urmează să fie actualizat

        // Obține valorile introduse de utilizator pentru câmpurile de editare
        $nume_eveniment_nou = $_POST['nume_eveniment'];
        $data_noua = $_POST['data'];
        $ora_noua = $_POST['ora'];
        $tip_nou = $_POST['tip'];
        $locatie_noua = $_POST['locatie'];
        $descriere_noua = $_POST['descriere'];

        // Actualizează datele evenimentului în baza de date
        $query = "UPDATE Eveniment SET Nume_eveniment = ?, Data = ?, Ora = ?, Tip = ?, Locatie = ?, Descriere_eveniment = ? WHERE ID_eveniment = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $nume_eveniment_nou, $data_noua, $ora_noua, $tip_nou, $locatie_noua, $descriere_noua, $evenimentID);

        if ($stmt->execute()) {
            // Redirecționează utilizatorul către o pagină de succes sau afișează un mesaj de succes
            echo "Modificările au fost salvate cu succes!";
        } else {
            echo "Eroare la salvarea modificărilor.";
        }
    }
}

$detalii_eveniment = array();

// Obține datele evenimentului selectat din baza de date
if (!is_null($evenimentID)) {
    $query = "SELECT * FROM Eveniment WHERE ID_eveniment = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $evenimentID);
    $stmt->execute();
    $stmt->bind_result($id_eveniment, $nume_eveniment, $data, $ora, $tip, $locatie, $descriere, $id_tip_bilet, $id_sponsor, $id_speaker, $id_partener);
    $stmt->fetch();

    // Salvează datele într-un array pentru a le afișa ulterior
    $detalii_eveniment = array(
        'Nume_eveniment' => $nume_eveniment,
        'Data' => $data,
        'Ora' => $ora,
        'Tip' => $tip,
        'Locatie' => $locatie,
        'Descriere' => $descriere,
        // Adaugă aici și celelalte informații ale evenimentului (ID_tip_bilet, ID_sponsor, ID_speaker, ID_partener)
    );
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editare Eveniment</title>
</head>

<body>
    <h2>Editare Eveniment</h2>
    <form method="post" action="editare_eveniment.php">
        <select name="eveniment_selectat" required>
            <?php foreach ($evenimente as $id => $nume): ?>
                <option value="<?php echo $id; ?>">
                    <?php echo $nume; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="edit_event" value="Editează Eveniment">
    </form>

    <?php if (!empty($detalii_eveniment)): ?>
        <div>
            <h3>Detalii Eveniment</h3>
            Nume Eveniment:
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

            <!-- Afișează aici și celelalte informații ale evenimentului (ID_tip_bilet, ID_sponsor, ID_speaker, ID_partener) -->

            <h3>Editare Eveniment</h3>
            <form method="post" action="editare_eveniment.php">
                <!-- Alte câmpuri pentru editarea evenimentului -->
                Nume Eveniment: <input type="text" name="nume_eveniment"
                    value="<?php echo $detalii_eveniment['Nume_eveniment']; ?>"><br>
                Data: <input type="date" name="data" value="<?php echo $detalii_eveniment['Data']; ?>"><br>
                Ora: <input type="time" name="ora" value="<?php echo $detalii_eveniment['Ora']; ?>"><br>
                Tip: <input type="text" name="tip" value="<?php echo $detalii_eveniment['Tip']; ?>"><br>
                Locație: <input type="text" name="locatie" value="<?php echo $detalii_eveniment['Locatie']; ?>"><br>
                Descriere: <textarea name="descriere"><?php echo $detalii_eveniment['Descriere']; ?></textarea><br>

                <!-- Adaugă câmpurile pentru celelalte informații ale evenimentului (ID_tip_bilet, ID_sponsor, ID_speaker, ID_partener) -->
                <!-- Dacă aceste câmpuri sunt necesare pentru editare -->

                <input type="hidden" name="eveniment_id" value="<?php echo $evenimentID; ?>">
                <input type="submit" name="salveaza_editare" value="Salvează Modificările">
            </form>
        </div>
    <?php endif; ?>

    <a href="admin.php">Înapoi la Panou de Control pentru Administrator</a>

</body>

</html>