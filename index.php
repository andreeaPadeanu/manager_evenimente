<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        .container {
            background-color: white;
            margin: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu {
            background-color: #0070cc;
            color: white;
            padding: 10px;
        }

        .menu ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            /* Centrare */
        }

        .menu ul li {
            display: inline;
            margin-right: 20px;
        }

        .content {
            margin: 20px 0;
            padding: 20px;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            clear: both;
        }

        .footer p {
            margin: 0;
        }

        a {
            color: white;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .top-right-menu {
            text-align: right;
            padding: 10px;
        }
    </style>
</head>

<body>
    <header>
        <div class="top-right-menu">
            <a href="#">Acasă</a>
            <a href="#">Cos</a>
            <a href="notificari.php">Notificari</a>
            <a href="contul_meu.php">Contul Meu</a>
            <a href="logout.php">Deconectare</a>
        </div>
        <h1>Bine ați venit pe Pagina Principală</h1>
    </header>
    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Evenimente viitoare</a></li>
                <li><a href="#">Categorii</a></li>
                <li><a href="#">Oferte speciale</a></li>
            </ul>
        </div>
        <div class="content">
            <!-- Aici adăugați conținutul paginii principale -->
            <h2>Acasă</h2>
            <!-- Aici puteți adăuga conținutul paginii principale -->
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2023 Numele Companiei. Toate drepturile rezervate</p>
    </div>
</body>

</html>