<?php 
session_start();
include 'connexion.php';

    // $stmt =$pdo->prepare("SELECT category FROM expenses WHERE user_id=? ");
    // $category=$stmt->execute([$_SESSION['user_id']]);

    
    $stmt=$pdo->prepare("SELECT SUM(MontantIn) AS totalIN FROM incomes where user_id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalIn=$stmt->fetch(PDO::FETCH_ASSOC)['totalIN'];
    $stmt=$pdo->prepare("SELECT SUM(MontantEx) AS totalEX FROM expenses where user_id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalEx=$stmt->fetch(PDO::FETCH_ASSOC)['totalEX'];
    $solde = $totalIn - $totalEx;


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
    <p class="text-xl centre font-semibold tracking-widest w-[350px] h-[200px] rounded-2xl bg-purple-100 p-6 shadow-2xl"><a href="tableborde.php">Retour a table de borde </a></p>
    <?php
     $stmt = $pdo->prepare("SELECT * from carte where user_id= ?");
        $stmt->execute([$_SESSION['user_id']]);
    
        while($carte = $stmt->fetch()){
            echo '
            
            <div class=" w-[350px] h-[200px] rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white p-6 shadow-2xl">
                
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">ID : '.$carte['id'].'</span>
                    
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">category : '.$carte['category'].'</span>
                    
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




