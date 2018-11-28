/*global describe:true*/
/*global console:true*/
/*jshint loopfunc: true */

var searchChoice = false;
var searchItmCont;
var folderTitle;
var myData = '';

var current;
var imgs;
var opacity;
var currentIdx;
var maxLength;
var prev;
var img;
var modalImg;
var captionText;
var forward;
var backward;
var modalTitle;
var geneolCont;
var photoIdCont;
var ctrlPressed = false;
var selectedPhotoId;
var obj;
var modalObj;
var modal;
var infoPhotoData;
var repositoryData = [];

var author = '';
var decade = '';
var year = 0;
var itm = 0;
var repoId = 0;
var sbitm = 0;
var htmlString = '';
var j = -1;
var level;

function getFolderTree() {
    'use strict';
    const folderRequest = new XMLHttpRequest();
    folderRequest.open('GET', 'php/getFolderTree.php', true);
    folderRequest.onload = function () {
        const folderData = JSON.parse(folderRequest.responseText);
        buildFolderTree(folderData);
    };
    folderRequest.send();
}

/*** Family Photos ***/
function buildFolderTree(data) {
    'use strict';
    const folderContainer = document.getElementById("photos__folders");

    for (const branch of data) {
        if (author !== branch.author && branch.author.length > 0) {
            if (author !== "" && level === "4") {
                htmlString = htmlString +
                    "</ul>\n" +
                    "</li>\n" +
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n";
            }
            if (author !== "" && level === "2") {
                htmlString = htmlString +
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n";
            }
            j++;

            htmlString = htmlString +
                "<input type=\"checkbox\" id=\"folders__menu" + j + "\"/>\n" +
                "<label for=\"folders__menu" + j + "\" class=\"folders__names\">" + branch.author + "</label>\n" +
                "<div class=\"folders__multi-level" + j + "\">\n";
            author = branch.author;
            decade = "";
        }

        level = branch.levels;
        switch (level) {
            case "2":
                folderLevel2(branch);
                break;
            case "4":
                folderLevel4(branch);
                break;
        }
    }

    if (level === "4") {
        htmlString = htmlString +
            "</ul>\n" +
            "</div>\n" +
            "</li>\n" +
            "</ul>\n" +
            "</div>\n" +
            "</div>\n";
    }

    folderContainer.insertAdjacentHTML("beforeend", htmlString);
}

function folderLevel2(branch) {
    'use strict';
    if (decade !== branch.decade && branch.decade.length > 0) {
        htmlString = htmlString +
            "<div class=\"folders__itemL2\">\n" +
            "<ul>\n";
        decade = branch.decade;
    }

    if ((author === branch.author && branch.author.length > 0) &&
        (repoId !== branch.repository)) {

        htmlString = htmlString +
            "<li class='folders__photofolder L2' onclick='getFamilyPhotos(this," + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
        repoId = branch.repository;
    }
}

// TODO Add folder level 3

function folderLevel4(branch) {
    'use strict';
    if (decade !== branch.decade && branch.decade.length > 0) {
        if (decade !== "") {
            htmlString = htmlString +
                "</ul>\n" +
                "</div>\n" +
                "</li>\n" +
                "</ul>\n" +
                "</div>\n";
        }
        itm++;
        sbitm = 0;
        htmlString = htmlString +
            "<div class=\"folders__item\">\n" +
            "<input type=\"checkbox\" id=\"IT" + itm + "\"/>\n" +
            "<img src=\"assets/img/icons/arrow.png\" class=\"folders__arrow\">\n" +
            "<label for=\"IT" + itm + "\">" + branch.decade + "</label>\n" +
            "<ul>\n";
        decade = branch.decade;
        year = 0;
    }

    if (year !== branch.year && branch.year.length > 0) {
        if (year !== 0) {
            htmlString = htmlString +
                "</ul>\n" +
                "</div>\n" +
                "</li>\n";
        }
        sbitm++;
        htmlString = htmlString +
            "<li>\n" +
            "<div class=\"folders__sub-item\">\n" +
            "<input type=\"checkbox\" id=\"SIT" + itm + "-" + sbitm + "\"/>\n" +
            "<img src=\"assets/img/icons/arrow.png\" class=\"folders__arrow\">\n" +
            "<label for=\"SIT" + itm + "-" + sbitm + "\">" + branch.year + "</label>\n" +
            "<ul>\n";
        year = branch.year;
    }
    if ((author === branch.author && branch.author.length > 0) &&
        (decade === branch.decade && branch.decade.length > 0) &&
        (year === branch.year && branch.year.length > 0) &&
        (repoId !== branch.repository)) {
        htmlString = htmlString +
            "<li class=\"folders__photofolder\" value=\"0\" onclick=\"getFamilyPhotos(this," + branch.repository + "," + branch.type + ")\">" + branch.title + "</li>\n";
        repoId = branch.repository;
    }
}

function getFamilyPhotos(obj, path, type) {
    'use strict';
    folderTitle = obj.innerHTML;
    getPhotos(path, type);
}

function getPhotos(path, type) {
    'use strict';
    searchChoice = false;
    const myRequest = new XMLHttpRequest();
    myRequest.open("GET", "php/getPhotos.php?path=" + path, true);
    myRequest.onload = function () {
        if (myRequest.readyState === 4) {
            console.log(myRequest.responseText);
            myData = JSON.parse(myRequest.responseText);
            switch (type) {
                case 4:
                    renderHomePhoto();
                    break;
                case 2:
                    turnOffFolders();
                    renderFamilyPhotos();
                    break;
            }
        }
    };
    myRequest.send();
}

function searchInputs() {
    'use strict';
    searchChoice = true;
    folderTitle = "";
    const searchFormData = getSearchInputs();

    const myRequest = new XMLHttpRequest();
    myRequest.open("GET", "php/getSearchedPhotos.php?kwrd=" + searchFormData.kwords + "&startYear=" + searchFormData.startYear + "&endYear=" + searchFormData.endYear +
        "&wExact=" + searchFormData.wExact.toString() + "&wPart=" + searchFormData.wPart.toString() +
        "&searchKw=" + searchFormData.searchClefs.toString() + "&searchTitles=" + searchFormData.searchTitres.toString() + "&searchComments=" + searchFormData.searchComment.toString() + "&photoId=" + searchFormData.photoId +
        "&idUnique=" + searchFormData.idUnique.toString() + "&idContext=" + searchFormData.idContext.toString(), true);
    myRequest.onload = function () {
        console.log(myRequest.responseText);
        myData = JSON.parse(myRequest.responseText);
        turnOffSearchFolders();
        renderFamilyPhotos();
    };
    myRequest.send();
}

function getSelectedInfoPhoto() {
    'use strict';
    searchChoice = false;
    const url = new URL(window.location.href);
    selectedPhotoId = parseInt(url.searchParams.get('pid'), 10);
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getInfoPhoto.php?pid=' + selectedPhotoId, true);
    myRequest.onload = function () {
        console.log(myRequest.responseText);
        const myInfoPhoto = JSON.parse(myRequest.responseText);
        renderInfoPhoto(myInfoPhoto);
    };

    myRequest.send();
}

function getPhotoInfoPrevious() {
    'use strict';
    selectedPhotoId -= 1;
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getInfoPhoto.php?pid=' + selectedPhotoId, true);
    myRequest.onload = function () {
        console.log(myRequest.responseText);
        const myInfoPhoto = JSON.parse(myRequest.responseText);
        renderInfoPhoto(myInfoPhoto);
    };

    myRequest.send();
}

function getPhotoInfoNext() {
    'use strict';
    selectedPhotoId += 1;
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getInfoPhoto.php?pid=' + selectedPhotoId, true);
    myRequest.onload = function () {
        console.log(myRequest.responseText);
        const myInfoPhoto = JSON.parse(myRequest.responseText);
        renderInfoPhoto(myInfoPhoto);
    };

    myRequest.send();
}

function renderInfoPhoto(data) {
    'use strict';
    const infoContain = document.getElementsByClassName('data-box__photo');
    var infoInputs = document.getElementsByClassName('data-box__input');
    const infoContainer = infoContain[0];

    infoContainer.innerText = '';

    for (const obj of data) {
        const thumb = obj.prev_path + obj.filename;
        const htmlString = "<div><img src=\"" + thumb + "\"></div>";

        infoInputs[0].value = obj.title;
        infoInputs[1].value = obj.keywords;
        infoInputs[2].value = obj.caption;
        infoInputs[3].value = obj.year;
        infoInputs[4].value = obj.geneolnames;

        infoContainer.insertAdjacentHTML('beforeend', htmlString);
    }
}

function renderHomePhoto() {
    'use strict';
    const container = document.getElementsByClassName('home__photo');
    const archivesContainer = container[0];

    for (const obj of myData) {
        const imageURL = obj.path + obj.filename;

        const htmlString = "<img class='home__img' src=\"" + imageURL + "\" alt=\"" + obj.title + "\">";

        archivesContainer.insertAdjacentHTML('beforeend', htmlString);
    }
}

function renderFamilyPhotos() {
    'use strict';
    const imgDisplay = document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'block';

    const familyContainer = document.getElementsByClassName('photos__imgs')[0];

    imgDisplay.innerHTML = '';

    for (const obj of myData) {
        const thumb = obj.prev_path + obj.filename;
        const htmlString = "<div><img src=\"" + thumb + "\" alt=\"" + obj.caption + "\" title=\"" + obj.title + "\" class=\"thumbimg\"></div>";

        familyContainer.insertAdjacentHTML('beforeend', htmlString);
    }
    animatePhotos();
}

function turnOffFolders() {
    'use strict';
    const titleContainer = document.getElementsByClassName('photos__thumb-title')[0];
    // Hide search and tree and bring up back to tree button
    document.getElementById('photos__folders').style.display = 'none';
    const kw = document.getElementsByClassName('search__keyword')[0];
    kw.style.display = 'none';
    // document.getElementById('searchKw').style.display = 'none';
    const element = document.getElementsByClassName('search__search-button');
    element[0].style.display = 'none';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle = document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'block';
    if (!searchChoice) {
        titleContainer.innerText = folderTitle;
    } else {
        titleContainer.innerText = '';
    }
}

function turnOffSearchFolders() {
    'use strict';
    const titleContainer = document.getElementsByClassName('photos__thumb-title')[0];
    // Hide search and tree and bring up back to tree button
    document.getElementById('photos__folders').style.display = 'none';
    const kword = document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'none';
    const fmButton = document.getElementsByClassName('search__search-button')[0];
    fmButton.style.display = 'none';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle = document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'block';
    if (document.getElementById('search__radio-context').checked !== true) {
        titleContainer.innerText = '';
    } else {
        titleContainer.innerText = myData[0].rptTitle;
    }
    btt.onclick = function () {
        backToSearch();
    };
}

/*** SEARCH
 **********************************/
function initSearchForm() {
    'use strict';
    prepareSearchScreen();
    initSearchInputs();
}

function searchForm() {
    'use strict';
    prepareSearchScreen();
    }

function prepareSearchScreen() {
    'use strict';
    const buttn = document.getElementsByClassName('search__search-button');
    buttn[0].style.display = 'none';
    document.getElementById('photos__folders').style.display = 'none';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const kword = document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'block';
    // document.getElementById('searchKw').style.display = 'block';
        btt.onclick = function () {
        backToTree();
    };
}

function initSearchInputs(){
    'use strict';
    document.getElementsByClassName('search__key-words')[0].value = '';
    document.getElementById('search__year-start').value='start';
    document.getElementById('search__year-end').value='end';
    document.getElementById('search__radio-exact').checked = false;
    document.getElementById('search__radio-partial').checked = true;
    document.getElementById('search__keys').checked = true;
    document.getElementById('search__titles').checked = true;
    document.getElementById('search__comments').checked = true;
    document.getElementsByClassName('search__pid')[0].value ='';
    document.getElementById('search__radio-uniq').checked = true;
    document.getElementById('search__radio-context').checked = false;
}


function getSearchInputs() {
    'use strict';
    const searchData = [];
    searchData.kwords = document.getElementsByClassName('search__key-words')[0].value;
    searchData.startYear = document.getElementById('search__year-start').value;
    searchData.endYear = document.getElementById('search__year-end').value;
    searchData.wExact = document.getElementById('search__radio-exact').checked;
    searchData.wPart = document.getElementById('search__radio-partial').checked;
    searchData.searchClefs = document.getElementById('search__keys').checked;
    searchData.searchTitres = document.getElementById('search__titles').checked;
    searchData.searchComment = document.getElementById('search__comments').checked;
    if (document.getElementsByClassName('search__pid'[0]).value === '') {
        searchData.photoId = 'nothing';
    } else {
        searchData.photoId = document.getElementsByClassName('search__pid')[0].value;
    }
    searchData.idUnique = document.getElementById('search__radio-uniq').checked;
    searchData.idContext = document.getElementById('search__radio-context').checked;
    return searchData;
}

function backToTree() {
    'use strict';
    // Bring back search and tree and hide 'back to tree(X)' button
    const imgDisplay = document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'none';
    const kword = document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'none';
    // document.getElementById('searchKw').style.display = 'none';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'none';
    const thumbTitle = document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'none';
    document.getElementById('photos__folders').style.display = 'block';
    const searchBtn = document.getElementsByClassName('search__search-button');
    searchBtn[0].style.display = 'block';
}

function backToSearch() {
    'use strict';
    const imgDisplay = document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'none';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle = document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'none';
    searchForm();
}

/* Controle of right and left keys
**********************************/
document.onkeydown = function (e) {
    'use strict';
    const evt = e ? e : window.event;
    if (evt.ctrlKey) {
        ctrlPressed = true;
    }
    switch (e.keyCode) {
        case 17:
            ctrlPressed = true;
            break;
        case 37:
            // alert('left');
            prevImage();
            break;
        case 38:
            // alert('up');
            break;
        case 39:
            // alert('right');
            nextImage();
            break;
    }
};

function cancelPid() {
    'use strict';
    const pid = document.getElementsByClassName('search__pid');
    pid[0].value = '';
    document.getElementById('search__radio-uniq').checked = true;
}

function cancelKeywords() {
    'use strict';
    const kw = document.getElementsByClassName('search__key-words');
    kw[0].value = '';
    document.getElementById('search__year-start').value = 'debut';
    document.getElementById('search__year-end').value = 'fin';
    document.getElementById('search__radio-partial').checked = true;
    document.getElementById('search__keys').checked = true;
    document.getElementById('search__titles').checked = true;
    document.getElementById('search__comments').checked = true;
}

/*********************************/

function animatePhotos() {
    'use strict';
    current = document.querySelector('#current');
    imgs = document.querySelectorAll('.photos__imgs img');
    backward = document.getElementsByClassName('photos__previous')[0];
    forward = document.getElementsByClassName('photos__next')[0];
    geneolCont = document.getElementsByClassName('photos__geneol')[0];
    photoIdCont = document.getElementsByClassName('photos__photo-id')[0];

    opacity = 0.5;

    imgs.forEach(img => img.addEventListener('click', imgModal));
    backward.addEventListener('click', prevImage);
    forward.addEventListener('click', nextImage);
}

/*** MODAL ***/
function transformImage(e) {
    'use strict';
    prev = e.target.src;
    for (let i = 0; i < imgs.length; i++) {
        if (prev === imgs[i].src) {
            imgs[i].style.transition = 'all,1s';
            imgs[i].style.transform = 'scale(2,2)';
            break;
        }
    }
    imgModal(e);
}

function imgModal(e) {
    'use strict';
    const currWin = window.location.href;
    const n = currWin.lastIndexOf('/');
    const winResult = currWin.substring(n + 1);

    const bdy = document.getElementById('bdy');
    bdy.style.overflow = 'hidden';
    modal = document.getElementsByClassName('photos__modal')[0];

    prev = e.target.src;
    maxLength = imgs.length;

    for (var i = 0; i < imgs.length; i++) {
        if (prev === imgs[i].src) {
            break;
        }
    }
    currentIdx = i;

    if (ctrlPressed && winResult === 'family_photos.html') {
        ctrlPressed = false;
        window.open('photoInfo.html?pid=' + myData[currentIdx].idpho);
        location.reload();
    } else {

        if (currentIdx === 0) {
            backward.style.backgroundColor = 'red';
        } else {
            backward.style.backgroundColor = 'green';
        }
        if (currentIdx === maxLength - 1) {
            forward.style.backgroundColor = 'red';
        } else {
            forward.style.backgroundColor = 'green';
        }

        img = prev.replace('preview', 'full');

        modalTitle = document.getElementsByClassName('photos__modal-title')[0];
        modalImg = document.getElementById('img01');
        captionText = document.getElementsByClassName('photos__caption')[0];
        modal.style.display = 'block';
        modalTitle.innerHTML = imgs[currentIdx].title;
        modalImg.src = img;
        captionText.innerHTML = imgs[currentIdx].alt;

        const idxList = myData[currentIdx].geneolidx.split(',');
        const namesList = myData[currentIdx].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
        photoIdCont.innerHTML = "<p>(pid-" + myData[currentIdx].idpho + ")</p>";

// Get the <span> element that closes the modal
        const span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = 'none';
            bdy.style.overflow = 'visible';
        };
    }
}

function prevImage() {
    'use strict';
    if (currentIdx > 0) {
        img = imgs[currentIdx - 1].src;
        img = img.replace('preview', 'full');
        const capt = imgs[currentIdx - 1].alt;
        const titl = imgs[currentIdx - 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        const idxList = myData[currentIdx - 1].geneolidx.split(',');
        const namesList = myData[currentIdx - 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
        photoIdCont.innerHTML = "<p>(pid-" + myData[currentIdx - 1].idpho + ")</p>";
        currentIdx--;
        if (currentIdx === 0) {
            backward.style.backgroundColor = 'red';
        }
        if (currentIdx < maxLength - 1) {
            forward.style.backgroundColor = 'green';
        }
    }
}

function nextImage() {
    'use strict';
    if (currentIdx < maxLength - 1) {
        img = imgs[currentIdx + 1].src;
        img = img.replace('preview', 'full');
        const capt = imgs[currentIdx + 1].alt;
        const titl = imgs[currentIdx + 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        const idxList = myData[currentIdx + 1].geneolidx.split(',');
        const namesList = myData[currentIdx + 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
        photoIdCont.innerHTML = "<p>(pid-" + myData[currentIdx + 1].idpho + ")</p>";
        currentIdx++;
        if (currentIdx === maxLength - 1) {
            forward.style.backgroundColor = 'red';
        }
        if (currentIdx > 0) {
            backward.style.backgroundColor = 'green';
        }
    }
}

function buildGeneolLine(idxList, namesList) {
    'use strict';
    var htmlLine = '';
    if (idxList !== '') {
        htmlLine = '<p>Généalogie: ';
        for (var i = 0; i < idxList.length; i++) {
            htmlLine = htmlLine + "<a href='legacy/desilets/asc_tree/" + idxList[i] + ".html' target='_blank'>" +
                namesList[i];
            if (i + 1 < idxList.length) {
                htmlLine = htmlLine + ",&nbsp </a>";
            } else {
                htmlLine = htmlLine + "</a></p>";
            }
        }
    }
    return htmlLine;
}

/*** END MODAL ***/

function imgClick(e) {
    'use strict';
    // Reset opacity
    imgs.forEach(img => (img.style.opacity = 1));

    // Change current image to src of clicked image
    prev = e.target.src;
    /*var full = prev.replace('preview', 'full');
    current.src = full;*/
    current.src = prev.replace('preview', 'full');

    //Add fade0in class
    current.classList.add('fade-in');

    //Remove fade-in class after .5 sec
    setTimeout(() => current.classList.remove('fade-in'), 500);

    //Change the opacity to opacity variable
    e.target.style.opacity = opacity;
}

/*** Reading section ***/

function assignReadingTitle() {
    'use strict';
    const menu = document.getElementsByClassName('menu1__item');
    const menu0 = menu[0];
    const menu1 = menu[1];
    menu0.innerHTML = "Lectures des Normandeau-Desilets";
    menu1.innerHTML = "Vers les <span style='font-weight:bold;'>Bernard-Normandeau</span>";
}

function getReadings() {
    'use strict';
    const menu = document.getElementsByClassName('menu1__item');
    const menu0 = menu[0];
    const menu1 = menu[1];
    const n = menu1.innerText.search('Bernard-Normandeau');
    var path;

    if (n !== -1) {
        path = 11;
        menu0.innerHTML = 'Lectures des Bernard-Normandeau';
        menu1.innerHTML = "Vers les <span style='font-weight:bold;'</span>Normandeau-Desilets";
    } else {
        path = 10;
        assignReadingTitle();
    }

    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getReadings.php?path=' + path, true);
    myRequest.onload = function () {
        var myData = JSON.parse(myRequest.responseText);
        renderReadings(myData);
    };
    myRequest.send();
}

function renderReadings(data) {
    'use strict';
    const container = document.getElementsByClassName('readings');
    const readingsContainer = container[0];

    readingsContainer.innerHTML = '';

    for (const obj of data) {
        let intro = '';
        if (obj.intro === null) {
            intro = '';
        } else {
            intro = obj.intro;
        }

        let htmlString = "<div class=\"clearfix\">" + "<a href=\"" + obj.address + "\" target=\"_blank\">" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"readings__books\">" +
            "<p class=\"readings__title\">" + obj.title + "</p></a>" +
            "<p class=\"readings__summary\">" + intro + "</p>" + "<p class=\"readings__summary\">" +
            obj.sumary + "</p ><br></div>";

        readingsContainer.insertAdjacentHTML('beforeend', htmlString);
    }
}

/*** OBJECT SECTION
 ******************/

function getObjects() {
    'use strict';
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getObjects.php?path=' + 12, true);
    myRequest.onload = function () {
        var myData = JSON.parse(myRequest.responseText);
        renderObjects(myData);
    };
    myRequest.send();
}

function renderObjects(data) {
    'use strict';
    const container = document.getElementsByClassName('objects__container');
    const objectsContainer = container[0];

    for (const obj of data) {

        const htmlString = "<div class=\"clearfix\">\n" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"objects__img\" title='Cliquer pour agrandir la photo'>\n" +
            "<p class=\"objects__description\" >" + obj.description + "\n</p >\n<br>\n</div>\n";

        objectsContainer.insertAdjacentHTML('beforeend', htmlString);
    }
    animateObjects();
}

function animateObjects() {
    'use strict';
    const objs = document.querySelectorAll('.objects');
    opacity = 0.5;
    objs.forEach(obj => obj.addEventListener('click', objModal));
}

function objModal(e) {
    'use strict';
    const bdy = document.getElementById('bdy');
    modal = document.getElementsByClassName('objects__modal')[0];

    prev = e.target.src;
    obj = prev.replace('preview', 'full');

    modalObj = document.getElementsByClassName('objects__modal-content')[0];
    modal.style.display = 'block';
    modalObj.src = obj;

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = 'none';
        bdy.style.overflow = 'visible';
    };
}

/*** ADD REPOSITORY
 ********************************/
function getRepositInputs() {
    'use strict';
    const repositoryDoc = document.getElementsByClassName('data-box__select');

    repositoryData.levels = repositoryDoc[0].value;
    repositoryData.type = repositoryDoc[1].value;
    repositoryData.author = repositoryDoc[2].value;
    repositoryData.decade = repositoryDoc[3].value;
    repositoryData.year = repositoryDoc[4].value;
    repositoryData.title = repositoryDoc[5].value;
}

function getRepositInputsPhotos() {
    'use strict';
    const repositoryDoc = document.getElementsByClassName('data-box__select');

    repositoryData.type = repositoryDoc[0].value;
    repositoryData.author = repositoryDoc[1].value;
    repositoryData.decade = repositoryDoc[2].value;
    repositoryData.year = repositoryDoc[3].value;
    repositoryData.title = repositoryDoc[4].value;
}

function addRepository() {
    'use strict';
    getRepositInputs();
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/addRepository.php?type=' + repositoryData.type +
        '&author=' + repositoryData.author + '&decade=' + repositoryData.decade + '&year=' + repositoryData.year +
        '&title=' + repositoryData.title + '&levels=' + repositoryData.levels + '&function=addRepository', true);

    myRequest.send();

    addRepositoryMysql();
}

function addRepositoryMysql() {
    'use strict';
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/addRepository.php?type=' + repositoryData.type +
        '&author=' + repositoryData.author + '&decade=' + repositoryData.decade + '&year=' + repositoryData.year +
        '&title=' + repositoryData.title + '&levels=' + repositoryData.levels + '&function=addRepositoryMysql', true);

    myRequest.send();
}

function uploadPhotos() {
    'use strict';
    getRepositInputsPhotos();

    const url = 'php/upload.php';
    const files = document.getElementById('data-box__input--photos').files;
    const formData = new FormData();

    /*    for (let i = 0; i < repositoryData.preview.length; i++) {
            let file = repositoryData.preview[i];
            formData.append('files[]', file);
        }*/

    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        formData.append('files[]', file);
    }

    formData.append('type', repositoryData.type);
    formData.append('author', repositoryData.author);
    formData.append('decade', repositoryData.decade);
    formData.append('year', repositoryData.year);
    formData.append('title', repositoryData.title);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => {
        console.log(response);
    });
}

function getYearsSelected() {
    'use strict';
    const deca = document.getElementsByClassName('data-box__select--add-repo-photo-decade');
    const decade = deca[0].value;
    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getYears.php?decade=' + decade, true);
    myRequest.onload = function () {
        const myYearsData = JSON.parse(myRequest.responseText);
        renderYears(myYearsData);
    };

    myRequest.send();
}

function getYearsSelectedPhotos() {
    'use strict';
    const deca = document.getElementsByClassName('data-box__select--add-repo-photo-decade');
    const decade = deca[0].value;
    const myRequest = new XMLHttpRequest();

    myRequest.open('GET', 'php/getYears.php?decade=' + decade, true);
    myRequest.onload = function () {
        const myYearsData = JSON.parse(myRequest.responseText);
        renderYears(myYearsData);

        const firstYear = myYearsData[0].idxValue;
        getReposits(firstYear);
    };

    myRequest.send();
}

function renderYears(data) {
    'use strict';
    const yearContainer = document.getElementsByClassName('data-box__select--add-repo-photo-year');
    yearContainer[0].innerHTML = '';

    for (const obj of data) {
        const htmlString = "<option value=\"" + obj.idxValue + "\">" + obj.year + "</option>";
        yearContainer[0].insertAdjacentHTML("beforeend", htmlString);
    }
    const htmlString = "<option value=\"1\">NA</option>";
    yearContainer[0].insertAdjacentHTML('beforeend', htmlString);
}

function getReposits(fisrtYear) {
    'use strict';
    var year;
    if (fisrtYear === undefined) {
        const y = document.getElementsByClassName('data-box__select--add-repo-photo-year');
        year = y[0].value;
    } else {
        year = fisrtYear;
    }

    const myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getReposits.php?year=' + year, true);
    myRequest.onload = function () {
        const myRepositData = JSON.parse(myRequest.responseText);
        renderReposits(myRepositData);
    };

    myRequest.send();
}

function renderReposits(myData) {
    'use strict';
    var repositContainer = document.getElementsByClassName('data-box__select--add-ph-title');

    repositContainer[0].innerHTML = '';

    for (const obj of myData) {
        const htmlString = "<option value=\"" + obj.idrpt + "\">" + obj.title + "</option>";
        repositContainer[0].insertAdjacentHTML('beforeend', htmlString);
    }
}

function renderSelectedPhotos() {
    'use strict';
    document.getElementById('data__box--text-input').value = createFileList(document.getElementById('data-box__input--photos').files);
}

function createFileList(files) {
    'use strict';
    var fileList = '';
    let i = 0;
    while (i < files.length) {
        if (fileList.length === 0) {
            fileList = files[i].name;
        } else {
            fileList = fileList + ' , ' + files[i].name;
        }
        i++;
    }
    return fileList;
}

function closeWindow() {
    'use strict';
    window.close(window.location.href);
}
