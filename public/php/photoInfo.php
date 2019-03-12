<?php
include_once '../../priv/initialize.php';
include_once INCLUDES_PATH . 'db_connect.php';
include_once INCLUDES_PATH . 'functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Infos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body onload="getSelectedInfoPhoto()">
<?php if (login_check($mysqli) == true) : ?>
    <div class="page data-box">

        <div>
            <h1 class="data-box__h1 data-box__h1--info">Informations sur la
                photo</h1>
            <button onclick="closeWindow()"
                    class="data-box__exit data-box__exit--info">
                <strong>X</strong>
            </button>
        </div>

        <main role="main">
            <fieldset class="data-box__fieldset data-box__fieldset--info">
                <form method="post"
                      enctype="multipart/form-data"
                      class="data-box__form"
                      action="javascript:insertPhotoInfo()">
                    <input type="text"
                           hidden
                           class='data-box__input data-box__photo-id'>
                    <input type="submit"
                           value="Soumettre"
                           class="data-box__go-button"><br>
                    <div>
                        <label class="data-box__label">Titre de la photo</label>
                        <input type="text"
                               class="data-box__input data-box__input--info-title"
                               placeholder="ex.: Louis chez les grands-parents"
                               size="75">
                    </div>
                    <br>
                    <div>
                        <label class="data-box__label">Mots-clefs</label>
                        <input type="text"
                               class="data-box__input data-box__input--info-keywords"
                               size="75"
                               placeholder=
                               "Série de mots-clefs, séparés par une virgule, permettant une recherche">
                    </div>
                    <br>
                    <div>
                        <label class="data-box__label">Légende</label>
                        <input type="text"
                               class="data-box__input data-box__input--info-caption"
                               size="118"
                               placeholder="Courte description générale">
                    </div>
                    <br>
                    <div>
                        <label class="data-box__label">Année de la photo</label>
                        <input type="text"
                               class="data-box__input data-box__input--info-year"
                               size="10"
                               placeholder="ex.: 2019"
                               required>
                    </div>
                    <br>
                    <div>
                        <label class="data-box__label">Indexe(s)
                            généalogique(s)</label>
                        <input type="text"
                               class="data-box__input data-box__input--info-geneol"
                               size="85">
                    </div>
                </form>

                <div class="data-box__geneol-list">
                </div>

                <div class="data-box__photo-container">
                    <div class="data-box__photo" id="data-box__photo">
                    </div>
                </div>

                <div class="data-box__button">
                    <button class="data-box__rotate-photo-90degres-negative"
                            onclick="rotatePhotoNegative()">Tourner la photo -90
                        degrés
                    </button>
                    <button class="data-box__rotate-photo-90degres-positive"
                            onclick="rotatePhotoPositive()">Tourner la photo 90
                        degrés
                    </button>
                </div>

                <div class="data-box__button">
                    <button class="data-box__button-prev"
                            onclick="getPhotoInfoPrevious()">Photo précédente
                    </button>
                    <button class="data-box__button-next"
                            onclick="getPhotoInfoNext()">Photo suivante
                    </button>
                </div>
            </fieldset>
        </main>
    </div>
    <!-- end page -->
    <script src="../js/main.js"></script>

<?php else : ?>
    <p>
                <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p><?php endif; ?>
</body>
</html>
