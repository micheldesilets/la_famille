<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Famille Normandeau-Desilets - Photos Famille</title>
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <!--   <link rel="stylesheet" href="css/media_query.css" media="screen">-->
  <!--  <link href="https://fonts.googleapis.com/css?family=Alex+Brush|Raleway:400,400i,700,700i" rel="stylesheet">-->
  <script src="//code.jquery.com/jquery-3.3.1.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css"/>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>

</head>
<body>
<div class="page">
  <!-- ==== START MASTHEAD ==== -->
  <header class="masthead" role="banner">
    <!--    <p class="logo"><a href="/"><img .../></a></p>-->
    <h1>Les Normandeau-Desilets</h1>

    <h2>Une courte histoire de Nous</h2>

    <nav role="navigation">
      <ul class="nav-main">
        <li><a href="index.php">Acceuil</a>
        </li>
        <li><a href="readings_desilets.php">Lectures</a>
        </li>
        <li><a href="#">Généalogie</a>
        </li>
        <li><a href="#">Objets de famille</a>
        </li>
        <li><a href="photo_arch_desilets.php">Photos d'archives</a>
        </li>
      </ul>
    </nav>
  </header>
  <!-- end masthead -->
  <!-- ==== START SIDEBAR ==== -->
  <div class="sidebar-family">
    <!--    <article class="about">-->
    <!--        <h2>About Me</h2>-->
    <!--        ...-->
    <!--    </article>-->
    <section class="find-photos">
      <form method="get" action="search-results.php" role="search">
        <label for="search">Search:</label>
        <input type="search" id="search" name="search" size="30" placeholder="e.g., a book or magazine"/>
        <input type="submit" value="Find It!"/>
      </form>
      <form method="post" action="show-data.php">
        <fieldset>
          <label for="annee">Année</label>
          <select id="annee" name="annee">
            <option value="1970">1970</option>
            <option value="1971">1971</option>
            <option value="1971">1972</option>
            <option value="1971">1973</option>
            <option value="1971">1974</option>
          </select>
        </fieldset>
        <fieldset>
          <h2>Name</h2>
          <div class="fields">
            <p class="row">
              <label for="first-name">First Name:</label>
              <input type="text" id="first-name" name="first_name" class="field-large" required autofocus
                     maxlength="30"/>
            </p>
            <p class="row">
              <label for="last-name">Last Name:</label>
              <input type="text" id="last-name" name="last_name" class="field-large" required/>
            </p>
            </p>
            <p class="row">
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" placeholder="yourname@example.com" class="field-large"/>
            </p>

          </div>
        </fieldset>

        <fieldset>
          <h2 class="hdr-public-profile">Public Profile</h2>
          <div class="fields">
            <fieldset class="radios">
              <legend>Gender</legend>
              <p class="row">
                <input type="radio" id="gender-male" name="gender" value="male"/>
                <label for="gender-male">Male</label>
                <br>
                <input type="radio" id="gender-female" name="gender" value="female"/>
                <label for="gender-female">Female</label>
              </p>
            </fieldset>
          </div>
        </fieldset>

        <fieldset>
          <h2 class="hdr-emails">Emails</h2>

  </div>
  </fieldset>
  <div><input type="submit" value="Create Account" class="btn"/>
    </section>

    <section class="drop-down">
      <input type="checkbox" id="menu"/>
      <!--  <img src="img/icons/arrow.png" class="arrow">-->
      <label for="menu" class="names">Photos Michel</label>

      <div class="multi-level">
        <div class="item">
          <input type="checkbox" id="A"/>
          <img src="img/icons/arrow.png" class="arrow">
          <label for="A">1970-1979</label>

          <ul>
            <li>
              <div class="sub-item">
                <input type="checkbox" id="A-A"/>
                <img src="img/icons/arrow.png" class="arrow">
                <label for="A-A">1975</label>

                <ul>
                  <li><a href="#">Princeville Chantal et Micha</a></li>
                </ul>
              </div>
            </li>
          </ul>

        </div>
        <div class="item">
          <input type="checkbox" id="B"/>
          <img src="img/icons/arrow.png" class="arrow">
          <label for="B">1980-1989</label>

          <ul>

            <li>
              <div class="sub-item">
                <input type="checkbox" id="B-A"/>
                <img src="img/icons/arrow.png" class="arrow">
                <label for="B-A">1981</label>
                <ul>
                  <li><a href="#">Été vacances</a></li>
                  <li><a href="#">Ail des bois, Evelyne dans le bain (6 mois)</a></li>
                  <li><a href="#">Temple avec Bigras, famille Provost </a></li>
                  <li><a href="#">Maquette Josette, enfants Centre d'Achat les Rivières</a></li>
                </ul>
              </div>
            </li>

            <li>
              <div class="sub-item">
                <input type="checkbox" id="B-B"/>
                <img src="img/icons/arrow.png" class="arrow">
                <label for="B-B">1982</label>
                <ul>
                  <li><a href="#">Printemps famille</a></li>
                  <li><a href="#"> Anniversaire Ali, glissade Valleyfield</a></li>
                  <li><a href="#">Bateaux, Anniversaire Do</a></li>
                  <li><a href="#">Noel, Jour de l'An</a></li>
                </ul>
              </div>
            </li>

            <li>
              <div class="sub-item">
                <input type="checkbox" id="B-C"/>
                <img src="img/icons/arrow.png" class="arrow"><label for="B-C">1983</label>
                <ul>
                  <li><a href="#">Fête Do et fraises</a></li>
                </ul>
              </div>
            </li>

          </ul>
        </div>

        <div class="item">
          <input type="checkbox" id="C"/>
          <img src="img/icons/arrow.png" class="arrow"><label for="C">1990-1999</label>
          <ul>

            <li>
              <div class="sub-item">
                <input type="checkbox" id="C-A"/>
                <img src="img/icons/arrow.png" class="arrow"><label for="C-A">1991</label>
                <ul>
                  <li><a href="#">Été Alberta</a></li>
                </ul>
              </div>
            </li>

          </ul>
        </div>
      </div>
    </section>
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
<script type="text/javascript">
  $("[data-fancybox]").fancybox({
    idleTime: false,
    buttons: [
      'download',
      'thumbs',
      'close',
    ]
  });
</script>
<script>
  var items = document.querySelectorAll("#B-A li"),
    tab = [], index;

  // add values to the array
  for (var i = 0; i < items.length; i++) {
    tab.push(items[i].innerHTML);
  }

  // get selected element index
  for (var i = 0; i, items.length; i++) {
    items[i].onclick = function () {
      index = tab.indexOf(this.innerHTML);
      console.log(this.innerHTML + " Index = " + index);
    };
  }
</script>
</body>
</html>
