<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

include('config.php');

$query = "SHOW TABLES LIKE 'servers'";
$stmt = $pdo->prepare($query);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    $createTableQuery = "
    CREATE TABLE servers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        version VARCHAR(50) NOT NULL,
        status VARCHAR(50) NOT NULL,
        owner VARCHAR(255) NOT NULL,  -- Ajout de la colonne 'owner'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($createTableQuery);
}
$adminEmail = $_SESSION['admin'];

$query = "SELECT * FROM servers WHERE owner = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$adminEmail]);
$servers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .dashboard-container {
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 8px;
            width: 600px;
            text-align: center;
        }
        .dashboard-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .server-list {
            margin-top: 20px;
            text-align: left;
        }
        .server-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .server-list th, .server-list td {
            padding: 10px;
            border: 1px solid #444;
        }
        .server-list th {
            background-color: #333;
        }
        .server-list td {
            background-color: #222;
        }
        .logout-button {
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
        .logout-button:hover {
            background-color: #e03e3e;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <p>Vos serveurs:</p>

    <div class="server-list">
        <?php if (count($servers) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom du serveur</th>
                        <th>Version</th>
                        <th>Statut</th>
                        <th>Propriétaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servers as $server): ?>
                        <tr>
                            <td><?= htmlspecialchars($server['name']) ?></td>
                            <td><?= htmlspecialchars($server['version']) ?></td>
                            <td><?= htmlspecialchars($server['status']) ?></td>
                            <td><?= htmlspecialchars($server['owner']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez pas encore de serveurs.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
