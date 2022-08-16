<?php
define("URL", 
        str_replace("index.php", "", (isset($_SERVER['HTTPS']) ? 'https' : 'http')."://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

if (isset($_GET['q'])){
  // VARIABLE
  $shortcut = htmlspecialchars($_GET['q']);

  // CONNEXION A LA BDD
  $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8','root','admin');
  $req = $bdd->prepare('SELECT COUNT(*) AS x
                        FROM links WHERE shortcut = ? ');
  $req->execute([$shortcut]);

  // SI L'ADRESSE N'EXISTE PAS => ERREUR
  while ($result = $req->fetch()){
    if($result['x'] != 1 ){
      header('location: ./?error=true&message=Adresse url non connue!');
      exit();
    }
  }

  //REDIRECTION VERS LA VRAIE ADRESSE
  $req = $bdd->prepare('SELECT * FROM links WHERE shortcut = ?');
  $req->execute([$shortcut]);
  while ($result = $req->fetch()){
    header('location: '.$result['url']);
    exit();
  }

}


if(isset($_POST['url']) && !empty($_POST['url'])){

  // VARIABLE
  $url = $_POST['url'];
  
  // VERIFICATION SI URL VALIDE
  if(!filter_var($url, FILTER_VALIDATE_URL)){
    header('location: ./?error=true&message=Url non valide');
    exit();
  }

  //  CREATION DU RACCOURCI
  $shortcut = crypt($url, rand());

  //  VERIF SI URL EXISTE DEJA EN BDD
  $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', 'admin');
  $req = $bdd->prepare('SELECT COUNT(*) AS x
                        FROM links WHERE url = ? ');
  $req->execute([$url]);
  while ($result = $req->fetch()){
    if($result['x'] != 0 ){
      header('location: ./?error=true&message=Adresse déjà raccourcie !');
      exit();
    }
  }

  //  AFFICHAGE
  $req = $bdd->prepare('INSERT INTO links (url, shortcut) VALUES (?, ?)');
  $req->execute([$url, $shortcut]);
  header('location: ./?short='.$shortcut);
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="design/style.css">
  <link rel="icon" href="pictures/favico.png" />
  <title>Bitly</title>
</head>
<body>
  <!-- PRESENTATION -->
  <section id="hello">

    <!-- CONTAINER -->
    <div class="container">
      
      <!-- HEADER -->
      <header>
        <!-- LOGO -->
        <img src="pictures/logo.png" alt="logo" id="logo">
      </header>
      <h1>Une url longue ? Raccourcissez la !</h1>
      <h2>Largement meilleur et plus court que les autres.</h2>

      <!-- FORMULAIRE -->
      <form method="post" action="index.php">
        <input type="url" name="url" placeholder="Collez un lien à raccourcir">
        <input type="submit" value="Raccourcir">
      </form>

      <?php if(isset($_GET['error']) && isset($_GET['message'])){ ?>
        <div class="center">
          <div id="result">
            <b><?= htmlspecialchars($_GET['message']) ?></b>
          </div>
        </div>
        <?php } else if (isset($_GET['short'])) { ?>
          <div class="center">
            <div id="result">
              <b>URL RACCOURCIE : <?= URL ?>?q=<?= htmlspecialchars($_GET['short']) ?></b>
            </div>
          </div>
      <?php }?>
    </div>
  </section>

  <!-- BRANDS -->
  <section id="brands">
    <div class="container">
      <h3>Ces marques nous font confiance</h3>
      <img src="pictures/1.png" alt="1" class="picture">
      <img src="pictures/2.png" alt="2" class="picture">
      <img src="pictures/3.png" alt="3" class="picture">
      <img src="pictures/4.png" alt="4" class="picture">
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="container">
      <img src="pictures/logo2.png" alt="" id="logo2">
      <p>2018 © Bitly</p>
      <p class="contact">
        <a href="#">Contact </a>- <a href="#">A propos</a>
      </p>
    </div>
  </footer>
</body>
</html>

 