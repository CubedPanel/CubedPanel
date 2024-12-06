<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('config.php');

    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = hash('sha256', $password);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && $hashed_password === $admin['password']) {
        $_SESSION['admin'] = $admin['email'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #121212;
            color: white;
        }
        .login-container {
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
        }
        .login-container h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: white;
        }
        .input-field:focus {
            border-color: #007bff;
            outline: none;
        }
        .login-button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .login-button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #ff4d4d;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Connexion Admin</h1>
    
    <?php if (isset($error)): ?>
        <p class="error-message"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" class="input-field" placeholder="Email" required>
        <input type="password" name="password" class="input-field" placeholder="Mot de passe" required>
        <button type="submit" class="login-button">Se connecter</button>
    </form>
</div>

</body>
</html>
