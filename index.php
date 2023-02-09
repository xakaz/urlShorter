<?php

if (isset($_POST['url']) && !empty($_POST['url'])) {
  $url = $_POST['url'];
  $shortcut = crypt($url, "shorty");

  // VERIFIE L'URL
  if (!filter_var($url, FILTER_VALIDATE_URL)) {
    header('location: ./?error=true&message=Adresse url non valide');
    exit();
  }

  // CONNEXION BASE DE DONNEES 
  try {
    $db = new PDO('mysql:host=localhost;dbname=url_shorter;charset=utf8', 'root', '');
  } catch (Exception $e) {
    echo $e->getMessage();
  }

  // REQUETE BASE DE DONNEES - URL EXISTANTE 
  $req = $db->prepare("SELECT COUNT(*) AS x FROM links WHERE url = ?");
  $req->execute([$url]);
  $result = $req->fetch();
  if ($result[0] != 0) {
    header('location: ./?error=true&message=Url déjà utilisée');
    exit();
  }
  // $req->closeCursor();

  // ENVOI EN DBBASE DE DONNEES 
  $req = $db->prepare("INSERT INTO links(url, shortcut) VALUES ( ?, ?)");
  $req->execute([$url, $shorcut]);
  header('location: ./?short=' . $shortcut);
  exit();
  // $req->closeCursor();
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="./pix/favicon.ico">
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <title>Url Shorter</title>
</head>

<body>
  <section id="hello">
    <div class="container">

      <!-- HEADER -->
      <header>
        <a href="./">
          <img src="pix/URL SHORTER.png" alt="logo url" class="logo">
        </a>
      </header>

      <!-- TITRE -->
      <h1>Url trop longue ? Raccourcissez-la !</h1>

      <!-- FORMULAIRE -->
      <form action="index.php" method="post">
        <input type="url" name="url" placeholder="Collez un lien à raccourcir" />
        <button type="submit">RACCOURCIR</button>
      </form>

      <!-- MESSAGE D'ERREUR -->
      <?php if (isset($_GET['error']) && isset($_GET['message'])) { ?>
        <div class="center">
          <div id="result">
            <b><?= htmlspecialchars($_GET['message']) ?></b>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- OUTILS -->
  <section id="brands">
    <div class="container">
      <h3>Pour réaliser ce site j'ai utilisé </h3>
      <div class="img">
        <img src="./pix/HTML5.png" alt="" class="picture">
        <img src="./pix/css.png" alt="" class="picture">
        <img src="./pix/php.png" alt="" class="picture">
        <img src="./pix/mysql.jpg" alt="" class="picture">
        <img src="./pix/git.png" alt="" class="picture">
        <img src="./pix/vs.png" alt="" class="picture">
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <div class="container">
    <footer>
      <img src="./pix/URL SHORTER.png" alt="logo" class="logo">
      <div>
        <p>2023 © Url Shorter</p>
        <a href="#">Contact</a>
        <span> - </span>
        <a href="#">à propos</a>
      </div>
    </footer>
  </div>
</body>

</html>