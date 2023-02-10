<?php

require_once("./DataBase/database.php");

if (isset($_GET['q'])) {
  $shortcut = htmlspecialchars($_GET['q']);

  // VERIFICATION DE L'EXISTENCE DE L'URL PAR L'ADRESSE RACCOURCIE
  $req = $db->prepare("SELECT COUNT(*) AS x FROM links WHERE shortcut = ?");
  $req->execute([$shortcut]);
  $result = $req->fetch();
  if ($result["x"] != 1) {
    header('location: ./?error=true&message=Adresse url non connue');
    exit();
  }

  // REDIRIGE VERS LA BONNE URL
  $req = $db->prepare("SELECT * FROM links WHERE shortcut = ?");
  $req->execute([$shortcut]);
  $result = $req->fetch();
  header('location: ' . $result['url']);
  $req->closeCursor();
}