<!DOCTYPE html>
<html>

<head>
    <title>Adaugă Administrator</title>
</head>

<body>
    <h2>Adaugă Administrator</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Procesați adăugarea unui administrator
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $email = $_POST['email'];
        $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT); // Criptare parolă
    
        // Adăugați administratorul în baza de date (tabelul Admin)
        require('config.php');
        $query = "INSERT INTO Admin (Nume, Prenume, Email, Parola) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nume, $prenume, $email, $parola);

        if ($stmt->execute()) {
            echo "Administratorul a fost adăugat cu succes.";
        } else {
            echo "Adăugarea administratorului a eșuat. Vă rugăm să verificați datele introduse.";
        }
    }
    ?>

    <form method="post" action="adauga_ad.php">
        Nume: <input type="text" name="nume" required><br>
        Prenume: <input type="text" name="prenume" required><br>
        Email: <input type="text" name="email" required><br>
        Parola: <input type="password" name="parola" required><br>
        <input type="submit" value="Adaugă Administrator">
    </form>

    <a href="admin.php">Înapoi la Panou de Control pentru Administrator</a>
</body>

</html>