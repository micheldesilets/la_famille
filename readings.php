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
  $reading = $db->getReadings();
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
        <li><a href="index.php">Acceuil </a>
        </li>
        <li><a href="#">Généalogie</a>
        </li>
        <li><a href="#">Objets de famille</a>
        </li>
        <li><a href="#">La famille en photos</a>
        </li>
        <li><a href="photo_arch_desilets.php">Photos d'archives</a>
        </li>
      </ul>
    </nav>
  </header>
  <!-- end masthead -->
  <div class="page">
    <div class="container">
      <main role=main>
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
            $resume = $row->get_Resume();
            $file = $row->get_File();

            ?>
            <div class="clearfix">
              <a href="<?php printf($address) ?>" target="_blank">
                <img src="<?php printf($file) ?>" alt="" class="books">
                <p class="title">
                  <?php printf($title) ?>
                </p>
              </a>
              <p class="resume">
                <?php printf($resume) ?>
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
