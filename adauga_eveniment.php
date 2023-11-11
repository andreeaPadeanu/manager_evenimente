<!DOCTYPE html>
<html>

<head>
    <title>Editare eveniment</title>
</head>

<body>
    <h2>Editare eveniment</h2>

    <?php
    session_start();

    // Verificați dacă administratorul este autentificat
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    include 'config.php';

    // Verificați dacă a fost trimis un eveniment pentru editare
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eveniment'])) {
        // Verificăm dacă s-a ales un eveniment din meniu
        $event_id = $_POST['eveniment'];

        // Faceți o interogare pentru a prelua datele evenimentului selectat
        $sql = "SELECT * FROM Eveniment WHERE ID_eveniment = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Evenimentul a fost găsit, afișați detaliile într-un formular pentru editare
            $row = $result->fetch_assoc();
            ?>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <label for="nume_eveniment">Nume eveniment:</label>
                <input type="text" name="nume_eveniment" id="nume_eveniment" value="<?php echo $row['Nume_eveniment']; ?>"
                    required><br>

                <!-- Aici adăugați câmpurile pentru editarea detaliilor evenimentului -->
                <label for="data">Data:</label>
                <input type="date" name="data" id="data" value="<?php echo $row['Data']; ?>" required><br>

                <label for="ora">Ora:</label>
                <input type="time" name="ora" id="ora" value="<?php echo $row['Ora']; ?>" required><br>

                <label for="tip">Tip eveniment:</label>
                <input type="text" name "tip" id="tip" value="<?php echo $row['Tip']; ?>" required><br>

                <label for="locatie">Locație:</label>
                <input type="text" name="locatie" id="locatie" value="<?php echo $row['Locatie']; ?>" required><br>

                <label for="descriere_eveniment">Descriere eveniment:</label>
                <textarea name="descriere_eveniment" id="descriere_eveniment"
                    required><?php echo $row['Descriere_eveniment']; ?></textarea><br>

                <input type="submit" name="salveaza" value="Salvează Modificările">
            </form>

            <?php
        } else {
            echo "Evenimentul nu a fost găsit.";
        }

        // Închideți conexiunea la baza de date
        $stmt->close();
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salveaza'])) {
        // Procesați aici salvarea modificărilor evenimentului în baza de date
    
        // Conectați-vă la baza de date (folosiți config.php)
    
        // Preiați valorile din formular
        $event_id = $_POST['event_id'];
        $nume_eveniment = $_POST['nume_eveniment'];
        $data = $_POST['data'];
        $ora = $_POST['ora'];
        $tip = $_POST['tip'];
        $locatie = $_POST['locatie'];
        $descriere_eveniment = $_POST['descriere_eveniment'];

        // Actualizați datele evenimentului în baza de date
        $sql = "UPDATE Eveniment SET Nume_eveniment=?, Data=?, Ora=?, Tip=?, Locatie=?, Descriere_eveniment=? WHERE ID_eveniment=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nume_eveniment, $data, $ora, $tip, $locatie, $descriere_eveniment, $event_id);

        if ($stmt->execute()) {
            echo "Evenimentul a fost actualizat cu succes.";
        } else {
            echo "Eroare la actualizarea evenimentului: " . $conn->error;
        }

        // Închideți conexiunea la baza de date
        $stmt->close();
    }
    $conn->close();
    ?>

    <a href="admin.php">Înapoi la Panou de Control</a>

</body>

</html>