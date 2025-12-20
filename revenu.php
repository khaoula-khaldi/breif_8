<?php
include 'connexion.php';
session_start();
if(!isset($_SESSION['user_id'])){
  header('location: compte_deja.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="https://cdn.tailwindcss.com"></script>
      <script src="js.js"></script>
    <title>revenu</title>
</head>
<body class=" flex flex-col gap-5 font-sans text-gray-800 bg-purple-100">


          <nav class="w-full shadow-md p-4 mb-6 bg-purple-200">
        <h1 class="text-center text-2xl font-bold text-gray-800">Gestion Financière</h1>
        <div class="text-gray-600 hidden lg:block">Salute <?php echo  $_SESSION['username'];?></div>
      </nav>
        <div class="w-[100%] bg-green-100 border border-gray-200 rounded-xl p-4 shadow-sm flex flex-row justify-between ">
          <h2 class="text-lg font-bold text-gray-700 ">Menu</h2>
          <ul class=" flex flex-row  gap-10">
            <li class="hover:bg-pink-100 transition p-2 rounded cursor-pointer"><a href="tableborde.php">Tableau de bord</a></li>
            <li class="hover:bg-pink-100 transition p-2 rounded cursor-pointer"><a href="revenu.php">Revenu</a></li>
            <li class="hover:bg-pink-100 transition p-2 rounded cursor-pointer"><a href="despenses.php">Dépenses</a></li>
            <li class="relative">
                  <button onclick="toggleParams()" 
                    class="hover:bg-pink-100 transition p-2 rounded cursor-pointer">
                    Paramètres 
                  </button>
                  <ul id="submenu" class="hidden absolute top-full left-0 mt-2
           bg-white border rounded shadow-md w-48
           z-50">
                    <li class="hover:bg-gray-100 p-2"><a href="profil.php">Profil</a></li>
                    <li class="hover:bg-gray-100 p-2"><a href="ajouter_carte.php">ajouter un carte</a></li>
                    <li class="hover:bg-pink-100 transition p-2 rounded cursor-pointer"><a href="transaction.php">transaction</a></li>
                    <li class="hover:bg-pink-100 transition p-2 rounded cursor-pointer"><a href="cards.php">les cartes</a></li>
                    <li class="hover:bg-gray-100 p-2"><a href="deconnexion.php">Déconnexion</a></li>
                  </ul>
             </li>
          </ul>
        </div>
        <section class="border border-gray-200 p-4 rounded-xl shadow-sm bg-blue-100">
          <h2 class="text-xl font-bold text-gray-700 mb-4">Revenu</h2>
          <form method="POST" class="flex flex-col gap-3">

              <div class="flex gap-3">
                  <input type="number" step="0.01" name="MontantIn" placeholder="Montant" class="border border-gray-300 p-2 rounded w-1/4" required>
                  <input type="text" name="descreptionIn" placeholder="Description" class="border border-gray-300 p-2 rounded flex-1" required>
                  <input type="date" name="date_enterIn" class="border border-gray-300 p-2 rounded w-1/4" required>
              </div>

              <div class="flex gap-3">
                  <input type="text" name="category" placeholder="categorie" class="border border-gray-300 p-2 rounded w-1/4" required>

                <select name="id_carte" class="border border-gray-300 p-2 rounded w-1/4" required>
                    <option value=""> carte aleatoir </option>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM carte WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $cartes = $stmt->fetchAll();
                    foreach($cartes as $c){
                        echo "<option value='".$c['id']."'>".$c['nom']."</option>";
                    }
                    ?>
                </select>
              </div>

              <button name="ajouter_revenu" class="bg-pink-300 hover:bg-pink-400 text-white px-4 py-2 rounded shadow mt-2">Ajouter</button>
          </form>
      </section>

</body>
</html>
<?php

if($_SERVER['REQUEST_METHOD'] =='POST' && isset($_POST['ajouter_revenu'])){

    $MontantIn = $_POST['MontantIn'];
    $descreptionIn = $_POST['descreptionIn'];  
    $date_enterIn = $_POST['date_enterIn'];
    $user_id = $_SESSION['user_id'];
    $category = trim($_POST['category']);
    $carte_id = $_POST['id_carte'];

    //  Verifier si la categorie existe deja
    $stmt = $pdo->prepare("SELECT id FROM category WHERE nom = ?");
    $stmt->execute([$category]);
    $existing = $stmt->fetch();

    if($existing){
        $id_category = $existing['id'];
    } else {
        // inserer nouvelle categorie
        $stmt = $pdo->prepare("INSERT INTO category(nom) VALUE (?)");
        $stmt->execute([$category]);
        $id_category = $pdo->lastInsertId();
    }
     // limite de category 
    $stmt=$pdo->prepare("SELECT limite_mensuelle FROM category WHERE id=? ");
    $stmt->execute([$id_category]);
    $category_limite = $stmt->fetch();
    $limite = $category_limite['limite_mensuelle'] ?? 0;

    //calcule somme depenses
    $stmt = $pdo->prepare("SELECT SUM(montantIn) AS total FROM incomes WHERE  category_id = ?AND MONTH(date_enterIn) = MONTH(CURRENT_DATE()) AND YEAR(date_enterIn) = YEAR(CURRENT_DATE()) ");
    $stmt->execute([$id_category]);
    $total_mois = $stmt->fetch()['total'] ?? 0;

    //ajoute limite 
    if($limite>0 && ($total_mois+$montantIn)>$limite){
    $_SESSION['erreur'] = "❌ Vous avez dépassé la limite mensuelle de cette catégorie";
    header('Location: despenses.php');
    exit;
    }

    //  Inserer dans expense
    $stmt = $pdo->prepare("INSERT INTO incomes(MontantIn, descreptionIn, date_enterIn, user_id, carte_id, category_id) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$MontantIn, $descreptionIn, $date_enterIn, $user_id, $carte_id, $id_category]);
    
    // redirection
    header('Location: revenu.php');
    exit;
}
?>


