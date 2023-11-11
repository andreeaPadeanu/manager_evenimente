<!DOCTYPE html>
<html>

<head>
    <title>Înregistrare</title>
</head>

<body>
    <h2>Înregistrare</h2>
    <?php
    require('config.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $email = $_POST['email'];
        $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT); // Criptare parolă
    
        $query = "INSERT INTO User (Nume, Prenume, Email, Parola) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nume, $prenume, $email, $parola);

        if ($stmt->execute()) {
            echo "Înregistrarea a fost efectuată cu succes. Acum vă puteți conecta.";
        } else {
            echo "Înregistrarea a eșuat. Vă rugăm să verificați datele introduse.";
        }
    }
    ?>

    <form method="post" action="register.php">
        Nume: <input type="text" name="nume" required><br>
        Prenume: <input type="text" name="prenume" required><br>
        Email: <input type="text" name="email" required><br>
        Parola: <input type="password" name="parola" required><br>
        <input type="submit" value="Înregistrare">
    </form>
    <br>

    <a href="login.php">Înapoi la pagina de conectare</a>
</body>

</html>