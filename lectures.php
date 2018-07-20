<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Lectures</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media_query.css" media="screen">
  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">
  <!--[if lt IE 9]>
  <!--<script src="js/html5shiv.js"></script>-->
  <![endif]-->
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
        <li><a href="photo_gallery.php">Photos d'archives</a>
        </li>
      </ul>
    </nav>
  </header>
  <!-- end masthead -->
  <main role=main>
    <!--The section element represents a generic section of a document or application. -->
    <section>
      <?php
      include 'connection/connect.php';

      $sql = "SELECT * FROM pdf_pdo ";

      if ($result = mysqli_query($con, $sql)) {
        // Return the number of rows in result set
        $rowcount = mysqli_num_rows($result);
        /*       printf( "Result set has %d rows.\n", $rowcount ); */
      } else {
        echo("nothing");
      };

      $l = 0;

      while ($l <= $rowcount):
        // Associative array
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $l++;

        $name = utf8_encode($row["name_pdo"]);
        $address = $row["address_pdo"];
        ?>

        <a href="<?php printf($address) ?>" target="_blank">
          <?php printf($name) ?>
        </a><br>

      <?php endwhile; ?>
      <?php

      // Free result set
      mysqli_free_result($result);

      mysqli_close($con);
      ?>
    </section>

<!--    <a href="?at=1000lc66" target="_blank"><img src="http://www.niftybuttons.com/itunes/itunesbutton11.png"
                                                alt="iTunes Button (via NiftyButtons.com)"></a>
    <a href="https://itunes.apple.com/ca/playlist/paul-simon-essentials/pl.8f4744d9a7344a659a38ba01a59df52c"
       target="_blank">Paul Simon</a>-->
  </main>
</body>
</html>
