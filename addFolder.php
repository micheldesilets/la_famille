<?php
include_once 'private/initialize.php';
include_once CONNECTION_PATH . '/connect.php';
include_once 'includes/functions.php';

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
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <!--    <link rel="stylesheet" href="assets/css/media_query.css" media="screen">-->
</head>

<body onload="getDecades();">
<?php if (login_check($mysqli) == true) : ?>
    <div class="page data-box">

        <h1 class="data-box__h1 data-box__h1--folder">Ajouter un répertoire</h1>
        <div>
            <a href="index.php" class="data-box__exit">X</a>
        </div>

        <main role="main">
            <fieldset class="data-box__fieldset data-box__fieldset--folder">
                <form action="javascript:addFolder()"
                      class="data-box__form data-box__form--folder">
                    <input type="submit"
                           value="Soumettre"
                           class="data-box__go-button"><br>

                    <div>
                        <label for="data-box__select--level"
                               class="data-box__label">Nombre de niveaux</label>
                        <select class="data-box__select data-box__select--add-folder-level"
                                id="data-box__select--level">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4" selected>4</option>
                        </select>
                    </div>
                    <br>
                    <div>
                        <label for="data-box__select--family"
                               class="data-box__label">Type de
                            regroupement</label>
                        <select class="data-box__select data-box__select--add-folder-family"
                                id="data-box__select--family">
                            <option value="2" selected>Famille</option>
                            <option value="3">Livres</option>
                            <option value="6">Objets</option>
                        </select>
                    </div>
                    <br>
                    <div>
                        <label for="data-box__select--author"
                               class="data-box__label">Auteur</label>
                        <select class="data-box__select data-box__select--add-folder-author"
                                id="data-box__select--author">
                            <option value="2" selected>Michel</option>
                            <option value="3">Amélie</option>
                            <option value="4">Chantal</option>
                            <option value="5">Archives</option>
                            <option value="1">NA</option>
                        </select>
                    </div>
                    <br>
                    <div>
                        <label for="data-box__select--decade"
                               class="data-box__label">Décennie</label>
                        <select class="data-box__select data-box__select--add-folder-photo-decade"
                                id="data-box__select--decade"
                                onchange="getYearsSelected();">
                        </select>
                    </div>
                    <br>
                    <div>
                        <label for="data-box__select--year"
                               class="data-box__label">Année</label>
                        <select class="data-box__select data-box__select--add-folder-photo-year"
                                id="data-box__select--year">
                        </select>
                    </div>
                    <br>
                    <div>
                        <label for="data-box__select--title"
                               class="data-box__label">Nom du répertoire</label>
                        <input type="text"
                               class="data-box__select data-box__select--title"
                               id="data-box__select--title"
                               size="50">
                    </div>
                </form>

                <p class='data-box__message' hidden>Cliquer
                    <a href="addPhotos.php"><span class="data-box__message--link">
                        ici</span></a> pour déposer des photos</p>
            </fieldset>
        </main>
    </div>
    <!-- end page -->
    <script src="assets/js/main.js"></script>

<?php else : ?>
    <p>
               <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="index.php">connectez-vous.</a>.
    </p>
<?php endif; ?>
</body>
</html>
