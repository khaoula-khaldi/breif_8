<?php 
session_start();
include 'connexion.php';

if (!isset($_SESSION['user_id'])) {
   exit('Accès interdit');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body class="flex flex-wrap gap-10 bg-purple-100">
            <a href="tableborde.php" class="block text-center text-sm text-gray-600 mt-3">
            ← Retour au tableau de bord
        </a>
    <?php
    $stmt = $pdo->prepare("SELECT  carte.id, carte.nom, category.nom AS category_name FROM carte JOIN category ON carte.category_id = category.id WHERE carte.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    
        while($carte = $stmt->fetch()){

            // total incomes par carte
            $stmtIn = $pdo->prepare("SELECT SUM(MontantIn) FROM incomes WHERE carte_id = ?");
            $stmtIn->execute([$carte['id']]);
            $totalIn = $stmtIn->fetchColumn() ?? 0;

            // total expenses par carte
            $stmtEx = $pdo->prepare("SELECT SUM(MontantEx) FROM expenses WHERE carte_id = ?");
            $stmtEx->execute([$carte['id']]);
            $totalEx = $stmtEx->fetchColumn() ?? 0;

            $solde = $totalIn - $totalEx;

            echo '
            
            <div class=" w-[350px] h-[200px] rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-6 shadow-2xl">
                
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">ID : '.$carte['id'].'</span>
                    
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">category : '.$carte['category_name'].'</span>
                    
                </div>

                <div class="mt-4 text-lg font-semibold">
                    Nom : '.$carte['nom'].'
                </div>

                <div class="mt-10 text-xl tracking-[0.25em] font-mono">
                    Solde : '. $solde .' DH
                </div>

            </div>';
        }


    
    ?>
</body>
</html>




