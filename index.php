<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Acceuil</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <!--   <link rel="stylesheet" href="css/media_query.css" media="screen">-->
  <!--  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">-->
  <script src="js/main.js"></script>
  <?php
  require_once 'classes/database/cl_getPhotosDB.php';

  /* main photo */
  $db = new photosBD();
  $photo = $db->getHomePhoto();

  $file = $photo->get_Filename();
  $path = $photo->get_F_Path();
  $title = $photo->get_Title();
  $loc = $path . $file;

  /* sidebar photo */
  $dbSb = new photosBD();
  $photoSb = $dbSb->getSidebarPhoto();
  $fileSb = $photoSb->get_Filename();
  $pathSb = $photoSb->get_P_Path();
  $locSb = $pathSb . $fileSb;
  ?>
</head>
<body>
<div class="page">
  <!-- ==== START MASTHEAD ==== -->
  <header class="masthead" role="banner" id="myHeader">
    <!--    <p class="logo"><a href="/"><img .../></a></p>-->
    <h1>Les Normandeau-Desilets</h1>

    <h2>Une courte histoire de Nous</h2>

    <nav role="navigation">
      <ul class="nav-main">
        <li><a href="#">Acceuil</a>
        </li>
        <li><a href="readings.html">Lectures</a>
        </li>
        <li><a href="gallery.html">Généalogie</a>
        </li>
        <li><a href="ajax.html">Objets de famille</a>
        </li>
        <li><a href="family_photos.php">La famille en photos</a>
        </li>
        <li><a href="archive_photos.html">Photos d'archives</a>
        </li>
      </ul>
    </nav>
  </header>
  <!-- end masthead -->
  <!-- ==== START SIDEBAR ==== -->
  <div class="sidebar">
    <article class="about">
      <img src="<?php echo($locSb); ?>" alt="Michel Desilets"
           class="myself">
      <p>En construisant ce site, j'ai voulu, tenté à tout le moins, conserver l'histoire de ma famille et un peu celle
        de ceux avec qui j'ai partagé ma vie.</p>
      <img src="img/home/Signature.jpg" alt="" class="signature">
      <!--        <h2>About Me</h2>-->
      <!--        ...-->
    </article>
    <div class="mod">
      <!--<h2>My Travels</h2>
      ... [map image] ...-->
    </div>
    <aside class="mod">
      <!--<h2>Popular Posts</h2>
      <ul class="links">
        ... [links list items] ...
      </ul>-->
    </aside>
    <aside class="mod">
      <!--        <h2>Recently Shared</h2>-->
      <ul class="links">
        <!--          ... [links list items] ...-->
      </ul>
    </aside>
  </div>
  <!-- end sidebar -->
</div>
<div class="container">
  <!-- ==== START MAIN ==== -->
  <main role="main">
    <section class="post">
      <script>getPhotos(2)</script>
      <div id="homePhoto"></div>
      <!--      <img src="--><?php //echo($loc); ?><!--" alt="--><?php //echo($title) ?><!--">-->
      <!--        <img ... class="post-photo-full"/>-->
      <div class="post-blurb">
        <!--          <p>It is hard to believe ...</p>-->
      </div>
      <footer class="footer">
        <!--          ... [blog post snippet footer] ...-->
      </footer>
    </section>
    <section class="post">
      <!--        <h1>The City Named After Queen Victoria</h1>-->
      <!--        <img ... class="post-photo"/>-->
      <div class="post-blurb">
        <!--          <p>An hour and a half aboard ...</p>-->
      </div>
      <footer class="footer">
        <!--          ... [blog post snippet footer] ...-->
      </footer>
    </section>
    <nav role="navigation">
      <ol class="pagination">
        <!--          ... [links list items] ...-->
      </ol>
    </nav>
  </main>
  <!-- end main -->

  <!-- end container -->
  <!-- ==== START PAGE FOOTER ==== -->
  <footer role="contentinfo" class="footer">
    <p class="legal">
      <!--      <small>&copy; 2013 Le Journal ...</small>-->
    </p>
  </footer>
  <!-- end page footer -->
</div>
<!-- end page -->
<script>
  /*
  window.onscroll = function() {myFunction()};

  var header = document.getElementById("myHeader");
  var sticky = header.offsetTop;

  function myFunction() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }
  */
</script>
</body>
</html>
