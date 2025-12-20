<?php 
try{
    $pdo = new pdo('mysql:host=localhost;dbname=smart_wallet','root','');

}catch(PDOException $e){
    echo 'error : '. $e->getMessage();
}
?>