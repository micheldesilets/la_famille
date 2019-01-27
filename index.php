<?php
include_once 'private/initialize.php';
include_once INCLUDES_PATH . 'psl-config.php';
include_once INCLUDES_PATH . 'db_connect.php';
include_once INCLUDES_PATH . 'functions.php';
include_once INCLUDES_PATH . 'register.inc.php';
require_once INCLUDES_PATH . 'role.php';
require_once INCLUDES_PATH . 'privilegedUser.php';

sec_session_start();

if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'out';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Famille Normandeau-Desilets - Acceuil</title>
    <link rel="stylesheet" href="public/css/normalize.css">
    <link rel="stylesheet" href="public/css/main.css">
    <script src="public/js/main.js"></script>
    <script type="text/JavaScript" src="public/js/forms.js"></script>
    <script type="text/JavaScript" src="public/js/sha512.js"></script>
    <?php
    include_once 'private/initialize.php';
    require_once CLASSES_PATH . '/database/cl_PhotosDB.php';
    $dbSb = new photosDB();
    $photoSb = $dbSb->getSidebarPhoto();
    $fileSb = $photoSb->get_Filename();
    $pathSb = $photoSb->get_P_Path();
    $locSb = $pathSb . $fileSb;
    ?>
</head>
<body>
<?php
if (isset($_GET['error'])) {
    echo '<p class="error">Error Logging In!</p>';
}
$u = PrivilegedUser::getByUsername($_SESSION["username"]);
?>
<div class="page">
    <div class="login">
        <input class="login__input"
               type='checkbox'
               id='login__form-switch'
               onclick="registerForm()">
        <form class='login__login-form'
              action="private/php/includes/process_login.php"
              method='post'
              name="login_form">
            <input class="login__input"
                   type="text"
                   placeholder="Courriel"
                   name="email"
                   required>
            <input class="login__input"
                   type="password"
                   placeholder="Mot de passe"
                   id="password"
                   name="password"
                   required>
            <button class="login__button"
                    type='button'
                    value="Login"
                    onclick="formhash(this.form, this.form.password);">
                Se connecter
            </button>
            <div>
                <label class="login__label"
                       for='login__form-switch'>
                    <span> S'enregistrer</span>
                </label>
            </div>
        </form>
        <input class="login__input"
               type='checkbox'
               id='login__register-switch'
               onclick="loginForm()">
        <form class='login__register-form'
              action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>"
              method='post'
              name="registration_form">
            <input class="login__input"
                   type="text" placeholder="Utilisateur"
                   name='username'
                   id='username' required>
            <input class="login__input"
                   type="email" placeholder="Courriel"
                   name="email" id="email" required>
            <input class="login__input"
                   type="password" placeholder="Mot de passe"
                   name="passwordReg"
                   id="passwordReg" required>
            <input class="login__input"
                   type="password" placeholder="Re Mot de passe"
                   name="confirmpwd"
                   id="confirmpwd" required>
            <input type="button"
                   class="login__button"
                   value="Enregistrer"
                   onclick="return regformhash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.passwordReg,
                                   this.form.confirmpwd);"/>
            <div class="login__label-container">
                <label class="login__label"
                       for='login__register-switch'>
                    Déjà membre? Connectez vous.
                </label>
            </div>
        </form>
    </div>
    <!-- ==== START MASTHEAD ==== -->
    <submit class="masthead" role="banner">
        <div class="masthead__dropdown">
            <?php if ($u->hasPrivilege("write")): ?>
            <button class="masthead__dropbtn">Gestion des photos</button>
            <div class="masthead__dropdown-content">
                <a class="masthead__item" href="public/php/addFolder.php">
                    Ajouter un répertoire</a>
                <a class="masthead__item" href="public/php/addPhotos.php">
                    Ajouter des photos</a>
            </div>
            <?php endif; ?>
        </div>
        <input type="button" class="masthead__connect" value="Se connecter"
               onclick="loginForm()">
        <form class="masthead__form-disconnect"
              action="private/php/includes/logout.php"
              method="post">
            <input type="submit"
                   class="masthead__disconnect"
                   value="Se déconnecter"/>
        </form>
    </submit>

    <h1 class="masthead__title masthead__title--index">Les
        Normandeau-Desilets</h1>
    <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

    <nav role="navigation">
        <ul class="c-nav-main">
            <li class="c-nav-main__item"><a class="c-nav-main__link"
                                            href1="#">Acceuil</a>
            </li>
            <li class="c-nav-main__item"><a class="c-nav-main__link"
                                            href="public/php/readings.php">
                    Lectures</a>
            </li>
            <li class="c-nav-main__item"><a class="c-nav-main__link"
                                            href="public/php/geneology.php">
                    Généalogie</a>
            </li>
            <li class="c-nav-main__item"><a class="c-nav-main__link"
                                            href="public/php/objects.php">
                    Objets de famille</a>
            </li>
            <li class="c-nav-main__item"><a class="c-nav-main__link"
                                            href="public/php/familyPhotos.php">
                    La famille en photos</a>
            </li>
        </ul>
    </nav>
    </header>
    <!-- end masthead -->
    <!-- ==== START SIDEBAR ==== -->
    <article class="about">
        <img class="about__img-me" src="<?php echo($locSb); ?>"
             alt="Michel Desilets">
        <p class="about__text">En construisant ce site, j'ai voulu conserver
            l'histoire de ma famille et un peu celle de ceux avec qui j'ai
            partagé ma vie.</p>
        <img src="public/img/home/TransparantSignature02.jpg" alt=""
             class="about__signature">
    </article>
    <!-- end sidebar -->
</div>

<!-- ==== START MAIN ==== -->
<main role="main">
    <section class="home">
        <img class='home__img'
             src="public\img\home\2015-01-06_Parc_Lafontaine1.jpg"
             srcset="public\img\home\2015-01-06_Parc_Lafontaine_400.jpg 400w,
        public\img\home\2015-01-06_Parc_Lafontaine_800.jpg 800w,
        public\img\home\2015-01-06_Parc_Lafontaine_1600.jpg 1600w"
             sizes="(min-width: 900px) 1000px,
            (max-width: 900px) and (min-width: 400px) 50em,
            ( not (orientation: portrait) ) 300px,
            ( (orientation: landscape) or (min-width: 1000px) ) 50vw,
            100vw"
             alt="">
        <!--        <script>getPhotos(5, 4)</script>-->
        <div class="home__photo"></div>
    </section>
</main>

<!-- end page -->
<?php
if (login_check($mysqli) == true) {
    echo '<script>document.getElementsByClassName(\'masthead__connect\')[0].style.display = \'none\'</script>';
    echo '<script>document.getElementsByClassName(\'masthead__form-disconnect\')[0].style.display = \'block\'</script>';
//    echo '<p>Currently logged ' . $logged . ' as ' . htmlentities($_SESSION['username']) . '.</p>';

//    echo '<p>Do you want to change user? <a href="includes/logout.php">Log out</a>.</p>';
} else {
//    echo '<p>Currently logged ' . $logged . '.</p>';
//    echo "<p>If you don't have a login, please <a href='register.php'>register</a></p>";
}
?>
</body>
</html>
