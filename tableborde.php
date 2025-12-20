<?php
session_start();
if(!isset($_SESSION['user_id'])){
  header('location: compte_deja.php');
  exit;
}
include 'connexion.php';


?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion Financière</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="js.js"></script>
</head>
<body class=" flex flex-col gap-5 font-sans text-gray-800 bg-purple-100">

      <nav class="w-full shadow-md p-4 mb-6 bg-purple-200">
        <h1 class="text-center text-2xl font-bold text-gray-800">Gestion Financièreeee</h1>
        <div class="text-gray-600  lg:block"> <?php echo "salue ". $_SESSION['username'];?></div>
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


      <main class="flex-1 space-y-8">
        <section class="flex gap-4">
          <div class="flex-1 p-4 bg-blue-400 rounded-xl shadow-sm">
            <p class="font-semibold text-white">Revenu totales </p>
            <p class="text-2xl font-bold mt-2 text-white"> <?php  $stmt = $pdo->prepare("SELECT SUM(MontantIn) AS totalIN FROM incomes where user_id=?");
                                                                  $stmt ->execute([$_SESSION['user_id']]);
                                                            $totalIn = $stmt->fetch(PDO::FETCH_ASSOC)['totalIN'] ;
                                                            echo $totalIn;?> DH</p>
          </div>
          <div class="flex-1 p-4 bg-green-400 rounded-xl shadow-sm">
            <p class="font-semibold text-white">Dépenses totales</p>
            <p class="text-2xl font-bold mt-2 text-white"> <?php $stmt = $pdo->prepare("SELECT SUM(montantEx) AS totalEX FROM expenses where user_id=?");
                                                                 $stmt ->execute([$_SESSION['user_id']]);
                                                            $totalEx = $stmt->fetch(PDO::FETCH_ASSOC)['totalEX'] ;
                                                            echo $totalEx;?> DH</p>
          </div>
          <div class="flex-1 p-4 bg-purple-400 rounded-xl shadow-sm">
            <p class="font-semibold text-gray-700">Solde</p>
            <p class="text-2xl font-bold mt-2 text-gray-700">  <?php $solde = $totalIn - $totalEx;
            echo $solde; ?> DH</p>
          </div>
        </section>
      </main>




        <section class="border border-gray-300 rounded-xl shadow-sm bg-blue-100 p-4">
          <h2 class="text-xl font-bold text-gray-700 mb-4">Revenu</h2>

          <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 border-b border-gray-300">
              <tr>
                <th class="p-2 border-r border-gray-300">Montant</th>
                <th class="p-2 border-r border-gray-300">Description</th>
                <th class="p-2 border-r border-gray-300">Date</th>
                <th class="p-2">Action</th>
              </tr>
            </thead>
            <tbody>
                <?php
                  $stmt = $pdo->prepare('SELECT * FROM incomes  where user_id=?');
                  $stmt->execute([$_SESSION['user_id']]);
                  while($row=$stmt->fetch()){
                             echo"<tr> <td class=\"p-2 border-r border-gray-300\">{$row['MontantIn']}</td>
                              <td class=\"p-2 border-r border-gray-300\">{$row['descreptionIn']}</td>
                              <td class=\"p-2 border-r border-gray-300\">{$row['date_enterIn']}</td>
                              <td class=\"p-2\"> 
                              <div class=\" flex flex-row gap-2\">
                              <form action=\"modificationIn.php\" method=\"POST\">
                              <input name=\"idIn\" type=\"text\" class=\"hidden\" value=\"{$row['idIn']}\"/>
                              <button type=\"submit\" name=\"modifier_incomes\" class=\"bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 mr-2\">Modifier</button>
                              </form>

                              <form action=\"supprime.php\" method=\"POST\">
                              <input name=\"idIn\" type=\"text\" class=\"hidden\" value=\"{$row['idIn']}\"/>
                              <button name=\"delete_incomes\" class=\"bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600\">Supprimer</button>
                              </form>
                              </div>
                              </td>
                              </tr>";
                  }

                ?>              
            </tbody>
          </table>
        </section>

        <section class="border border-gray-300 rounded-xl shadow-sm bg-blue-100 p-4">
          <h2 class="text-xl font-bold text-gray-700 mb-4">Dépenses </h2>

          <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 border-b border-gray-300">
              <tr>
                <th class="p-2 border-r border-gray-300">Montant</th>
                <th class="p-2 border-r border-gray-300">Description</th>
                <th class="p-2 border-r border-gray-300">Date</th>
                <th class="p-2">Action</th>
              </tr>
            </thead>
            <tbody>
                  <?php
                  $stmt = $pdo->prepare('SELECT * FROM expenses  where user_id=?');
                  $stmt->execute([$_SESSION['user_id']]);
                  while($row=$stmt->fetch()){
                             echo"<tr>
                              <td class=\"p-2 border-r border-gray-300\">{$row['MontantEx']}</td>
                              <td class=\"p-2 border-r border-gray-300\">{$row['descreptionEx']}</td>
                              <td class=\"p-2 border-r border-gray-300\">{$row['date_enterEx']}</td>
                              <td class=\"p-2\"> 
                              <div class=\" flex flex-row gap-2\">
                              <form action=\"modificationEx.php\" method=\"POST\">
                              <input name=\"idEx\" type=\"text\" class=\"hidden\" value=\"{$row['idEx']}\"/>
                              <button type=\"submit\" name=\"modifier_Expenses\" class=\"bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 mr-2\">Modifier</button>
                              </form>

                              <form action=\"supprime.php\" method=\"POST\">
                              <input name=\"idEx\" type=\"text\" class=\"hidden\" value=\"{$row['idEx']}\"/>
                              <button name=\"delete_Expenses\" class=\"bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600\">Supprimer</button>
                              </form>
                              </div>
                              </td>
                              </tr>";
                  }

                ?> 
            </tbody>
          </table>
        </section>

</body>

</html>
