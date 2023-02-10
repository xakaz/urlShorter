<?php
// try {
//   // $db = new PDO('mysql:host=localhost;dbname=url_shorter;charset=utf8', 'root', '');
//   $db = new PDO(PDO_LOCALHOST_CONNEXION, USER_LOCALHOST, PASSWORD_LOCALHOST, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
// } catch (Exception $e) {
//   echo $e->getMessage();
// }

require_once("credentials.php");

try {
  if ($_SERVER['HTTP_HOST'] === "localhost") {
    $db = new PDO(PDO_LOCALHOST_CONNEXION, USER_LOCALHOST, PASSWORD_LOCALHOST, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  } else {
    $db = new PDO(PDO_SERVER_CONNEXION, USER_SERVER, PASSWORD_SERVER, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  }
} catch (Exception $e) {
  echo "Impossible d'accéder à la base de données : " . $e->getMessage();
}
