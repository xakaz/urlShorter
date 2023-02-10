<?php

require_once("./DataBase/database.php");

if (isset($_POST['url']) && !empty($_POST['url'])) {
  $url = $_POST['url'];
  $shortcut = crypt($url, rand());

  // VERIFIE L'URL
  if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header('location: ./?error=true&message=Adresse url non valide');
    exit();
  }

  // REQUETE BASE DE DONNEES - URL EXISTANTE 
  $req = $db->prepare("SELECT COUNT(*) AS x FROM links WHERE url = ?");
  $req->execute([$url]);
  $result = $req->fetch();
  if ($result["x"] != 0) {
    header('location: ./?error=true&message=Url déjà utilisée');
    exit();

  } 

  // ENVOI EN BASE DE DONNEES 
  $req = $db->prepare("INSERT INTO links(url, shortcut) VALUES ( ?, ?)");
  $req->execute([$url, $shortcut]);
  header('location: ./?short=' . $shortcut);
  exit();
  $req->closeCursor();
}