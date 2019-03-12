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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajout de photos</title>
    <meta name="viewport" , initial-scale=1"/>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <!--    <link rel="stylesheet" href="public/css/media_query.css" media="screen">-->
    <!--[if lt IE 9]>
    <![endif]-->
</head>
<body>
<?php if (login_check($mysqli) == true) :
    $u = PrivilegedUser::getByUsername($_SESSION["username"]); ?>
    <div class="page data-box">

        <h1 class="data-box__h1 data-box__h1--add-photos">Ajouter des
            photos</h1>
        <div>
            <a href="../../index.php" class="data-box__exit">X</a>
        </div>

        <main role="main">
            <fieldset class="data-box__fieldset data-box__fieldset--add-photo">
                <form method="post"
                      enctype="multipart/form-data"
                      class="data-box__form data-box__form--add-photos"
                      action="javascript:uploadPhotos()">
                    <input type="submit"
                           value="Soumettre"
                           class="data-box__go-button"><br>

                    <div>
                        <label for="data-box__select--add-ph-type"
                               class="data-box__label">Type de
                            regroupement</label>
                        <select class="data-box__select data-box__select--add-ph-type">
                            <option value="2" selected>Famille</option>
                            <option value="3">Livres</option>
                            <option value="6">Objets</option>
                        </select><br>
                    </div>

                    <div>
                        <label for="data-box__select--add-ph-author"
                             class="data-box__label">Membre</label>
                        <select class="data-box__select"
                                   id="data-box__select--add-ph-member">
                        </select><br>
                    </div>

                    <div>
                        <label for="data-box__select--add-folder-photo-decade"
                               class="data-box__label">Décennie</label>
                        <select class="data-box__select data-box__select--add-folder-photo-decade"
                                onchange="getYearsSelected();">
                        </select><br>
                    </div>

                    <div>
                        <label for="data-box__select--add-folder-photo-year"
                               class="data-box__label">Année</label>
                        <select class="data-box__select data-box__select--add-folder-photo-year"
                                onchange="getFolders()">
                        </select><br>
                    </div>

                    <div>
                        <label for="data-box__select--add-ph-title"
                               class="data-box__label">Nom du répertoire</label>
                        <select class="data-box__select data-box__select--add-ph-title">
                        </select><br>
                    </div>

                    <div>
                        <label for="data-box__input--photos"
                               class="data-box__label">Sélection des
                            photos</label>
                        <input type="file"
                               multiple
                               class="data-box__input data-box__input--photos"
                               id="data-box__input--photos"
                               name="data-box__data[]"
                               onchange="renderSelectedPhotos()"><br>
                        <input type="text"
                               id="data__box--text-input"
                               class=" data-box__text data-box__text--photos"
                               size="75"
                               readonly>
                    </div>
                </form>
                <p class="data-box__upload-done" hidden> Opération terminée.</p>
                <p class='data-box__message' hidden>Svp sélectionner une ou
                    plusieurs photos.</p>
            </fieldset>
        </main>
    </div>

    <script src="../js/main.js"></script>
    <script>
        var user = '<?php echo $u->getUsername();?>';
        getDecades();
        getMembers(user);
    </script>
<?php else : ?>
    <p>
       <span class="error">Vous devez être connecté au site pour pouvoir
           voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p>
<?php endif; ?>
</body>
</html>
