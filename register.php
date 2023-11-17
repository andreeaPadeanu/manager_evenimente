<!DOCTYPE html>
<html>

<head>

    <link href="register.css" rel="stylesheet" type="text/css">

    <title>Înregistrare</title>
</head>

<body>
    <div class="register">
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
            <input type="text" name="nume" required placeholder="Nume"><br>
            <input type="text" name="prenume" required placeholder="Prenume"><br>
            <input type="text" name="email" required placeholder="Email"><br>
            <input type="password" name="parola" required placeholder="Parola"><br>
            <input type="submit" value="Înregistreaza-te">
        </form>
        <br>

        <a href="login.php">Înapoi la pagina de conectare</a>
    </div>
</body>

</html>