<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Famille Normandeau-Desilets - Archives</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <![endif]-->
</head>
<body>
<div class="page">
  <!-- ==== START MASTHEAD ==== -->
  <header class="masthead" role="banner">
    <!--     <p class="logo"><a href="/"><img .../></a></p>-->
    <ul class="social-sites">
    </ul>

    <h1>Les Normandeau-Desilets</h1>
    <p class="subhead">Une courte histoire de Nous</p>

    <nav role="navigation">
      <ul class="nav-main">
        <li><a href="index.php">Acceuil</a>
        </li>
        <li><a href="lectures.php">Lectures</a>
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
  <div class="page">
    <div class="container">
      <!-- ==== START MAIN ==== -->
      <main role="main" class="arch-photo">
        <section>
          <?php
          include 'connection/connect.php';

          $sql = "SELECT
  paths_pat.path_pat,
  photos_pho.filename_pho,
  photos_pho.title_pho,
  photos_pho.keywords_pho,
  photos_pho.height_pho,
  photos_pho.width_pho,
  photos_pho.caption_pho
FROM paths_pat
  INNER JOIN photos_pho
    ON paths_pat.id_pat = photos_pho.idpat_pho
WHERE paths_pat.id_pat = 1";

          if ($result = mysqli_query($con, $sql)) {
            // Return the number of rows in result set
            $rowcount = mysqli_num_rows($result);
            /* printf("Result set has %d rows.\n", $rowcount); */
          } else {
            echo("nothing");
          };

          $l = 1;

          while ($l <= $rowcount):
            // Associative array
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            $l++;

            $title = utf8_encode($row["title_pho"]);
            $keywords = utf8_encode($row["keywords_pho"]);
            $height = $row["height_pho"];
            $width = $row["width_pho"];
            $caption = utf8_encode($row["caption_pho"]);
            $path = utf8_encode($row["path_pat"]);
            $filename = utf8_encode($row["filename_pho"]);

            ?>

            <a href="<?php echo($path . $filename) ?>" target=""><img class="thumb-arch clearfix"
                                                                      src="<?php printf($path . $filename) ?>"
                                                                      title="<?php printf($title) ?>"
                                                                      height="<?php printf($height) ?>"
                                                                      width="<?php printf($width) ?>"></a>

          <?php endwhile; ?>
          <?php

          // Free result set
          mysqli_free_result($result);

          mysqli_close($con);
          ?>
        </section>
        <section class="post">
          <!--          <h1>Sunny East Garden at the Getty Villa</h1>-->
<!--          <img ... class="post-photo-full"/>-->
          <div class="post-blurb">
            <!--            <p>It is hard to believe ...</p>-->
          </div>
          <footer class="footer">
            <!--            ... [blog post snippet footer] ...-->
          </footer>
        </section>
        <section class="post">
          <!--          <h1>The City Named After Queen Victoria</h1>-->
<!--          <img ... class="post-photo"/>-->
          <div class="post-blurb">
            <!--            <p>An hour and a half aboard ...</p>-->
          </div>
          <footer class="footer">
            <!--            ... [blog post snippet footer] ...-->
          </footer>
        </section>
        <nav role="navigation">
          <ol class="pagination">
            <!--            ... [links list items] ...-->
          </ol>
        </nav>
      </main>
      <!-- end main -->
      <!-- ==== START SIDEBAR ==== -->
      <div class="sidebar">
        <article class="about">
          <!--          <h2>About Me</h2>-->
          <!--          ...-->
        </article>
        <div class="mod">
          <!--          <h2>My Travels</h2>-->
          <!--          ... [map image] ...-->
        </div>
        <aside class="mod">
          <!--          <h2>Popular Posts</h2>-->
          <ul class="links">
            <!--            ... [links list items] ...-->
          </ul>
        </aside>
        <aside class="mod">
          <!--          <h2>Recently Shared</h2>-->
          <ul class="links">
            <!--            ... [links list items] ...-->
          </ul>
        </aside>
      </div>
      <!-- end sidebar -->
    </div>
    <!-- end container -->
    <!-- ==== START PAGE FOOTER ==== -->
    <footer role="contentinfo" class="footer">
      <p class="legal">
        <!--        <small>&copy; 2013 Le Journal ...</small>-->
      </p>
    </footer>
    <!-- end page footer -->
  </div>
  <!-- end page -->
</body>
</html>
