<?php
include_once '../../priv/initialize.php';
include_once INCLUDES_PATH . 'db_connect.php';
include_once INCLUDES_PATH . 'functions.php';
require_once INCLUDES_PATH . "role.php";
require_once INCLUDES_PATH . "privilegedUser.php";

sec_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../js/main.js"></script>
    <!--    <link rel="stylesheet" href="public/css/media_query.css" media="screen">-->
</head>

<body>
<?php /*if (login_check($mysqli) == true) :
    $u = PrivilegedUser::getByUsername($_SESSION["username"]);
    */ ?>
<div class="page data-box">

    <h1 class="data-box__h1 data-box__h1--folder">Ajouter un répertoire</h1>
    <div>
        <a href="../../index.php" class="data-box__exit">X</a>
    </div>

    <main role="main">
        <fieldset class="data-box__fieldset data-box__fieldset--folder">
            <form action="javascript:addFolder()"
                  class="data-box__form data-box__form--folder">
                <input type="submit"
                       value="Soumettre"
                       class="data-box__go-button"><br>
                <div>
                    <label for="data-box__select--level0"
                           class="data-box__label data-box__label--level0">
                        Répertoire principal
                    </label>
                    <input type="text" class="data-box__input
                                              data-box__input--level0" readonly>
                </div>
                <br>

                <div>
                    <label for="data-box__select--level1"
                           class="data-box__label data-box__label--level1">
                        Sous-répertoire - niveau 1
                    </label>
                    <select class="data-box__select
                                   data-box__select--level1"
                            id="data-box__select--level1"
                            onchange="FirstLevelOnChange()">
                    </select>
                    <input type="text" class="data-box__input
                               data-box__input--level1">
                </div>
                <br>

                <div>
                    <label for="data-box__select--level2"
                           class="data-box__label data-box__label--level2">
                        Sous-répertoire - niveau 2
                    </label>
                    <select class="data-box__select
                                   data-box__select--level2"
                            id="data-box__select--level2"
                            onchange="SecondLevelOnChange()">
                    </select>
                    <input type="text" class="data-box__input
                               data-box__input--level2">
                </div>
                <br>
                <div>
                    <label for="data-box__select--level3"
                           class="data-box__label data-box__label--level3">
                        Sous-répertoire - niveau 3
                    </label>
                    <input type="text" class="data-box__input
                               data-box__input--level3">
                </div>
            </form>

            <p class='data-box__message' hidden>Cliquer
                <a href="addPhotos.php"><span class="data-box__message--link">
                        ici</span></a> pour déposer des photos</p>
        </fieldset>
    </main>
</div>
<script>
    //var user = '<?php //echo $u->getUsername();?>//';
  //  getDecades();
    GetFolderLevel0('Michel');
</script>
<?php /*else: */ ?><!--
    <p><span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p>
--><?php /*endif; */ ?>
</body>
</html>
