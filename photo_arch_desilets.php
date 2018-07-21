<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Archives</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media_query.css" media="screen">
  <!--  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">-->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <![endif]-->
  <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>

  <script type="text/javascript">
    $("[data-fancybox]").fancybox({
      idleTime: false,
      buttons: [
        'download',
      ]
    });
  </script>

  <?php
  require_once 'classes/database/cl_photosDB.php';
  $db = new photosBD();
  $photo = $db->getPhotos();
  ?>
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
        <li><a href="readings.php">Lectures</a>
        </li>
        <li><a href="#">Généalogie</a>
        </li>
        <li><a href="#">Objets de famille</a>
        </li>
        <li><a href="#">La famille en photos</a>
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
          $i = 0;

          while ($i <= count($photo) - 1):
            // Associative array
            $row = $photo[$i];

            $i++;

            $title = $row->get_Title();
            $keywords = $row->get_Keywords();
            $height = $row->get_Height();
            $width = $row->get_Width();
            $caption = $row->get_Caption();
            $file = $row->get_Filename();
            $f_path = $row->get_F_Path();
            $p_path = $row->get_P_Path();
            $thumb = $p_path . $file;
            $imageURL = $f_path . $file;
            ?>

            <a data-fancybox="images" data-caption="<?php echo $caption; ?>" href="<?php echo $imageURL; ?>">
              <img class="thumb-arch" id="boxshadow" src="<?php echo $thumb; ?>" title="<?php echo $title; ?>"
                   alt="<?php echo $file; ?>"/>
            </a>

          <?php endwhile; ?>

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
