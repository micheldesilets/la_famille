<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Lectures</title>
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/style.css">
  <!--  <link rel="stylesheet" href="css/media_query.css" media="screen">-->
  <!--  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">-->
  <!--[if lt IE 9]>
  <!--<script src="js/html5shiv.js"></script>-->
  <![endif]-->
  <?php
  require_once 'classes/database/cl_readingsDB.php';
  $db = new readingsDB();
  $reading = $db->getReadings(3);
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
          <li><a href="../index.php">Acceuil </a>
          </li>
          <li><a href="#">Généalogie</a>
          </li>
          <li><a href="#">Objets de famille</a>
          </li>
          <li><a href="family_photos.php_NG">La famille en photos</a>
          </li>
          <li><a href="photo_arch_desilets.phpNG">Photos d'archives</a>
          </li>
        </ul>
      </nav>
    </header>
  </div>

  <!-- end masthead -->
  <div class="page">
    <div class="container">
      <a href="readings_normandeau.php" class="flip-archive">
        <p class="archives-MD">Lectures des Normandeau-Desilets</p>
        <p class="normal-flip-archive">Vers les </p> Bermard-Normandeau</a>
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
            $summary = $row->get_Summary();
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
                <?php printf($summary); ?>
              </p>
              <br>
            </div>
          <?php endwhile; ?>

        </section>
      </main>
    </div>
  </div>
</body>
</html>
