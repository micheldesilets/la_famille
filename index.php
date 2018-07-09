<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Famille Normandeau-Desilets - Acceuil</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
<!--  <link rel="stylesheet" href="css/media_query.css" media="screen">-->
  <!--[if lt IE 9]>
  <!--<script src="js/html5shiv.js"></script>-->
  <![endif]-->
  <?php
   require_once 'classes/database/cl_photosDB.php';

   $db = new photosBD();
   $photo = $db->getHomePhoto();
   $file = $photo->get_Filename();
   $path = $photo->get_F_Path();
   $loc = $path . $file;
  ?>

</head>
<body>
<div class="page">
  <!-- ==== START MASTHEAD ==== -->
  <header class="masthead" role="banner">
    <!--    <p class="logo"><a href="/"><img .../></a></p>-->
    <ul class="social-sites">
    </ul>


    <h1>Les Normandeau-Desilets</h1>
    <p class="subhead">Une courte histoire de Nous</p>

    <nav role="navigation">
      <ul class="nav-main">
        <li><a href="lectures.php">Lectures</a>
        </li>
        <li><a href="#">Généalogie</a>
        </li>
        <li><a href="#">Objets de famille</a>
        </li>
        <li><a href="#">La famille en photos</a>
        </li>
        <li><a href="photo_gallery_copy.php">Photos d'archives</a>
        </li>
      </ul>
    </nav>
    <img src="<?php echo($loc); ?>" alt="Le Parc Lafontaine" class="home-pic">
  </header>
  <!-- end masthead -->
  <div class="container">
    <!-- ==== START MAIN ==== -->
    <main role="main">
      <section class="post">
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
    <!-- ==== START SIDEBAR ==== -->
    <div class="sidebar">
      <article class="about">
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
</body>
</html>
