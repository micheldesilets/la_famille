<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Lectures</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <!--  <link rel="stylesheet" href="css/media_query.css" media="screen">-->
  <!--  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">-->
  <!--[if lt IE 9]>
  <!--<script src="js/html5shiv.js"></script>-->
  <![endif]-->
  <?php
  require_once 'classes/database/cl_readingsDB.php';
  $db = new readingsDB();
  $reading = $db->getReadings(5);
  ?>
</head>
<body>
<div class="page">
  <!-- ==== START MASTHEAD ==== -->
  <div class="header-cont">
    <header class="masthead" role="banner">
      <!--    <p class="logo"><a href="/"><img .../></a></p>-->
      <ul class="social-sites">
      </ul>

      <h1>Les Normandeau-Desilets</h1>
      <h2>Une courte histoire de Nous</h2>

      <nav role="navigation">
        <ul class="nav-main">
          <li><a href="index.php">Acceuil </a>
          </li>
          <li><a href="#">Généalogie</a>
          </li>
          <li><a href="#">Objets de famille</a>
          </li>
          <li><a href="family_photos.php">La famille en photos</a>
          </li>
          <li><a href="photo_arch_desilets.phpNG">Photos d'archives</a>
          </li>
        </ul>
      </nav>
  </div>
  </header>
  <!-- end masthead -->
  <div class="page">
    <div class="container">
      <a href="readings_desilets.php" class="flip-archive">
        <p class="archives-MD">Lectures des Bernard-Normandeau</p>
        <p class="normal-flip-archive">Vers les </p> Normandeau-Desilets</a>
      <main role=main>
        <!--        <article class="clearfix,about">
                  <p>
                    Cette page contient un ensemble de documents (livres,lettres personnelles, etc.) que vous trouverez
                    surement intéressants.
                  </p>
                </article> -->
        <!--The section element represents a generic section of a document or application. -->
        <section>
          <?php
          $i = 0;

          while ($i <= count($reading) - 1):
            // Associative array
            $row = $reading[$i];

            $i++;

            $title = $row->get_Title();
            $address = $row->get_Address();
            $intro = $row->get_Intro();
            $resume = $row->get_Resume();
            $file = $row->get_File();

            ?>
            <div class="clearfix">
              <a href="<?php printf($address); ?>" target="_blank">
                <img src="<?php printf($file); ?>" alt="" class="books">
                <p class="title">
                  <?php printf($title); ?>
                </p>
              </a>
              <p class="resume">
                <?php printf($intro); ?>
              </p>
              <p class="resume">
                <?php printf($resume); ?>
              </p>
              <br>
            </div>
          <?php endwhile; ?>

        </section>

        <!--    <a href="?at=1000lc66" target="_blank"><img src="http://www.niftybuttons.com/itunes/itunesbutton11.png"
                                                        alt="iTunes Button (via NiftyButtons.com)"></a>
            <a href="https://itunes.apple.com/ca/playlist/paul-simon-essentials/pl.8f4744d9a7344a659a38ba01a59df52c"
               target="_blank">Paul Simon</a>-->
      </main>
    </div>
  </div>
</body>
</html>
