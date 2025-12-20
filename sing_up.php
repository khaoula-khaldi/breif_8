 <?php  
 session_start();
 include 'connexion.php';
  if(isset($_POST['inscrire'])) {
    $nomcomplet=$_POST['nomcomplet'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt=$pdo->prepare("SELECT email FROM utilisateur WHERE email=?") ;
    $stmt -> execute([$email]);
    $email_bd = $stmt ->fetch(PDO :: FETCH_ASSOC);
    if(!$email_bd){
       $stmt = $pdo->prepare("INSERT INTO utilisateur(nomcomplet,email,password) VALUES (?,?,?)");
       $stmt -> execute([$nomcomplet,$email,$hashedpassword]);
        header("Location: compte_deja.php");
        exit;
    }else{
       $_SESSION['email_erreur'] = ["deja un compte a cette eamil !! "];
         header("Location: compte_deja.php");
    }
   } 
  ?>
