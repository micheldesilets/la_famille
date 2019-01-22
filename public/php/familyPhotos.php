<?php
include_once '../../private/initialize.php';
include_once INCLUDES_PATH . 'db_connect.php';
include_once INCLUDES_PATH . 'functions.php';
require_once INCLUDES_PATH . 'Role.php';
require_once INCLUDES_PATH . 'PrivilegedUser.php';

sec_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Famille Normandeau-Desilets - Photos Famille</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body id="bdy" onmousedown="isKeyPressed(event)" onload="getShiftingFolders()">
<?php if (login_check($mysqli) == true) :
    $u = PrivilegedUser::getByUsername($_SESSION["username"]); ?>
    <div class="page ">
        <!-- ==== START MASTHEAD ==== -->
        <header class="masthead" role="banner">
            <h1 class="masthead__title">Les Normandeau-Desilets</h1>
            <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

            <nav role="navigation">
                <ul class="c-nav-main">
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="../../index.php">Acceuil</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="readings.php">Lectures</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="geneology.php">Généalogie</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="objects.php">Objets de
                            famille</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="familyPhotos.php">La
                            famille en photos</a>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- end masthead -->

        <div class="clearfix">
            <div><p class="photos__thumb-title"></p>
                <button onclick="downloadPhotos()"
                        class="photos__download-photos"
                        style="display:none">Télécharger
                </button>
                <button onclick="showPreviousFolder()"
                        class="photos__previous-folder"
                        style="display: none">Répertoire précédent
                </button>
                <button onclick="showNextFolder()"
                        class="photos__next-folder"
                        style="display: none">Répertoire suivant
                </button>
                <button class="search__back-to-tree" onclick="backToTree()">
                    <strong>X</strong>
                </button>
            </div>
        </div>

        <!--  Search-->
        <div class="search">
            <section class="search__section">
                <input autofocus="autofocus" type="button"
                       class="search__search-button" value="Recherche"
                       onclick="initSearchForm()">

                <form action="javascript:searchInputs()"
                      class="search__keyword">
                    <fieldset class="search__fieldset">
                        <input type="text"
                               class="search__key-words"
                               placeholder="mots clefs séparés par une virgule"
                               size="110"
                               onkeypress="cancelPid()"><br>
                        <input type="submit"
                               value="Rechercher"
                               class="search__go-button"><br>

                        <div class="search__from-year">
                        </div>
                        <div class="search__to-year">
                        </div>

                        <p class="search__radio-button">
                            <input type="radio"
                                   id="search__radio-exact"
                                   value="wExact"
                                   name="scope"/>
                            <label for="search__radio-exact"
                                   class="search__rb-label">Contient le/les mots
                                exacts</label>
                            <br>
                            <input type="radio"
                                   id="search__radio-partial"
                                   value="wPartial"
                                   name="scope"
                                   checked/>
                            <label for="search__radio-partial"
                                   class="search__rb-label">Contient une partie
                                du/des
                                mot(s)</label>
                        </p><br>

                        <div class="search__check-box">
                            <input type="checkbox"
                                   id="search__keys"
                                   value="clefs"
                                   checked>
                            <label for="search__keys" class="search__cb-label">Recherche
                                des mots clefs</label>
                            <br>
                            <input type="checkbox"
                                   id="search__titles"
                                   value="titres"
                                   checked>
                            <label for="search__titles"
                                   class="search__cb-label">Recherche
                                des titres</label>
                            <br>
                            <input type="checkbox"
                                   id="search__comments"
                                   value="commentaires"
                                   checked>
                            <label for="search__comments"
                                   class="search__cb-label">Recherche
                                des commentaires</label>
                            <br>
                        </div>

                        <fieldset class="search__pid-context">
                            <label>
                                PID-
                            </label>
                            <input class="search__pid"
                                   type="text"
                                   placeholder="Le numéro de la photo"
                                   size="24"
                                   onkeypress="cancelKeywords()">

                            <p class="search__context">
                                <input type="radio"
                                       id="search__radio-uniq"
                                       value="idUnique"
                                       name="context"
                                       checked/>
                                <label for="search__radio-uniq"
                                       class="search__rb-label">Unique</label>
                                <br>
                                <input type="radio"
                                       id="search__radio-context"
                                       value="idContext"
                                       name="context"/>
                                <label for="search__radio-context"
                                       class="search__rb-label">Contexte</label>
                            </p><br>
                        </fieldset>
                    </fieldset>
                </form>
            </section>
        </div>

        <!-- ==== MAIN ==== -->
        <div class="photos">
            <!--  Drop down menu is here  -->
            <section class="folders__drop-down" id="photos__folders"></section>

            <!--  Thumbnails  -->
            <section class="photos__thumb-container">
                <div class="photos__imgs"></div>
            </section>

            <section>
                <!-- The Modal -->
                <div class="photos__modal">

                    <!-- Previous and Next buttons -->
                    <button class="photos__previous"><</button>
                    <button class="photos__next">></button>

                    <?php if ($u->hasPrivilege("write")): ?>
                    <img src="../img/icons/iconfinder_Streamline-22_185042.png"
                         class="photos__edit-photo"
                         alt="Edition"
                         onclick="editPhoto()">
                    <?php endif; ?>

                    <span class="close">x</span>


                    <img src="" class="photos__modal-content" id="img01">
                    <div class="photos__modal-title"></div>
                    <div class="photos__caption"></div>
                    <div class="photos__idg-geneol">
                        <div class="photos__photo-id"></div>
                        <div class="photos__geneol"></div>
                    </div>
                </div>
            </section>
        </div>

    </div>

    <script src="../js/main.js"></script>
    <script>getFolderTree()</script>

<?php else : ?>
    <p>
          <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p>
<?php endif; ?>
</body>
</html>
