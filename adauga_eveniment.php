<!DOCTYPE html>
<html>

<head>
    <title>Adăugare Eveniment</title>
</head>

<body>
    <h2>Adăugare Eveniment</h2>

    <?php
    // Verificați dacă a fost trimis formularul de adăugare a evenimentului
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        // Includeți fișierul de configurație al bazei de date
        include 'config.php';

        // Colectați datele din formular
        $nume_eveniment = $_POST['nume_eveniment'];
        $data = $_POST['data'];
        $ora = $_POST['ora'];
        $tip = $_POST['tip'];
        $locatie = $_POST['locatie'];
        $descriere_eveniment = $_POST['descriere_eveniment'];
        $id_tip_bilet = $_POST['id_tip_bilet'];
        $id_sponsor = $_POST['id_sponsor'];
        $id_speaker = $_POST['id_speaker'];
        $id_partener = $_POST['id_partener'];
        // Alte date pentru eveniment
    
        // Construiți o declarație SQL pentru inserarea evenimentului în baza de date
        $sql = "INSERT INTO Eveniment (Nume_eveniment, Data, Ora, Tip, Locatie, Descriere_eveniment, ID_tip_bilet, ID_sponsor, ID_speaker, ID_partener) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiiii", $nume_eveniment, $data, $ora, $tip, $locatie, $descriere_eveniment, $id_tip_bilet, $id_sponsor, $id_speaker, $id_partener);

        // Executați declarația SQL
        if ($stmt->execute()) {
            echo "Evenimentul a fost adăugat cu succes în baza de date.";
        } else {
            echo "Eroare la adăugarea evenimentului: " . $conn->error;
        }

        // Închideți conexiunea la baza de date
        $conn->close();
    }
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="nume_eveniment">Nume eveniment:</label>
        <input type="text" name="nume_eveniment" id="nume_eveniment" required><br>

        <label for="data">Data:</label>
        <input type="date" name="data" id="data" required><br>

        <label for="ora">Ora:</label>
        <input type="time" name="ora" id="ora" required><br>

        <label for="tip">Tip eveniment:</label>
        <input type="text" name="tip" id="tip" required><br>

        <label for="locatie">Locație:</label>
        <input type="text" name="locatie" id="locatie" required><br>

        <label for="descriere_eveniment">Descriere eveniment:</label>
        <textarea name="descriere_eveniment" id="descriere_eveniment" required></textarea><br>

        <label for="id_tip_bilet">ID tip bilet:</label>
        <input type="number" name="id_tip_bilet" id="id_tip_bilet" required><br>

        <label for="id_sponsor">ID sponsor:</label>
        <input type="number" name="id_sponsor" id="id_sponsor" required><br>

        <label for="id_speaker">ID speaker:</label>
        <input type="number" name="id_speaker" id="id_speaker" required><br>

        <label for="id_partener">ID partener:</label>
        <input type="number" name="id_partener" id="id_partener" required><br>

        <!-- Alte câmpuri necesare pentru eveniment -->

        <input type="submit" name="submit" value="Adaugă Eveniment">
    </form>
</body>

</html>