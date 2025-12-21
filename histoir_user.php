<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des transactions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-100 min-h-screen p-6">

    <!-- Titre -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">
        Historique des transactions entre les utilisateurs 
    </h1>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow-lg">
        <table class="w-full border-collapse">
            <thead class="bg-purple-200 text-gray-700">
                <tr>
                    <th class="p-3 text-left">Source</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Destination</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Montant</th>
                    <th class="p-3 text-left">Description</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $stmt = $pdo->prepare("SELECT * FROM transactions_users WHERE id=? ");
                    $stmt->execute([$_SESSION['user_id']]);
                    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (count($transactions) === 0) {
                        echo "<tr><td colspan='6' class='p-4 text-center text-gray-500'>Aucune transaction trouvée</td></tr>";
                    }

                    foreach ($transactions as $t) {
                        echo "
                        <tr class='border-t hover:bg-gray-50'>
                            <td class='p-3'>{$t['user_send']}</td>
                            <td class='p-3'>User → User</td>
                            <td class='p-3'>{$t['user_dest']}</td>
                            <td class='p-3'>{$t['date_trans']}</td>
                            <td class='p-3 font-semibold text-green-600'>{$t['montant']} DH</td>
                            <td class='p-3'>{$t['description']}</td>
                        </tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-6 text-center">
        <a href="tableborde.php" class="text-blue-600 hover:underline">
            ⬅ Retour au tableau de bord
        </a>
    </div>

</body>
</html>
