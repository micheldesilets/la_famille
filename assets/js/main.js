/*global describe:true*/
/*global console:true*/
/*jshint loopfunc: true */

var searchChoice = new searchPhotos(false);
var folderTitle = new directoryTitle();
var selectedPhotos = new photos();

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
var photoInfoList = {};
var selectedPhotoIdx = {};
var currentShiftingFolder = {};
var jsShiftingFolders = new listShiftingFolders();
var obj;
var modalObj;
var modal;
var infoPhotoData;
var folderData = [];
var allYearsData = {};
var namesList = [];
var geneolListDone = new geneologyList(false);
var inFolders = new inFoldersState(true);

var author = '';
var decade = '';
var year = 0;
var itm = 0;
var folderId = 0;
var sbitm = 0;
var htmlString = '';
var j = -1;
var level;

function getFolderTree() {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/folders.php?function=getFolders', true);
    xhr.onload = function () {
        const folderData = JSON.parse(xhr.responseText);
        buildFolderTree(folderData);
    };
    xhr.send();
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
        (folderId !== branch.folder)) {

        htmlString = htmlString +
            "<li class='folders__photofolder L2' onclick='getFamilyPhotos(this," + branch.folder + "," + branch.type + ")'>" + branch.title + "</li>\n";
        folderId = branch.folder;
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
        (folderId !== branch.folder)) {
        htmlString = htmlString +
            "<li class=\"folders__photofolder\" value=\"0\" onclick=\"getFamilyPhotos(this," + branch.folder + "," + branch.type + ")\">" + branch.title + "</li>\n";
        folderId = branch.folder;
    }
}

function getFamilyPhotos(obj, path, type) {
    'use strict';
    inFolders.setState(true);
    const folders = jsShiftingFolders.getShiftingFolders();
    for (let i = 0; i < folders.length; ++i) {
        if (path === parseInt(folders[i].folder)) {
            currentShiftingFolder = new shiftingFoldersIdx(i);
            break;
        }
    }
    folderTitle.setTitle(obj.innerHTML);
    getPhotos(path, type);
}

function getPhotos(path, type) {
    'use strict';
    searchChoice.setSearchPageStatus(false);
    // searchChoice.setSearchPageStatus = false;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "php/getPhotos.php?path=" + path, true);
    xhr.onload = function () {
        if (xhr.readyState === 4) {
            selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
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
    xhr.send();
}

function searchInputs() {
    'use strict';
    searchChoice.setSearchPageStatus(true);
    // searchChoice = true;
    folderTitle.setTitle("");
    const searchFormData = getSearchInputs();

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "php/getSearchedPhotos.php?kwrd=" + searchFormData.kwords + "&startYear=" + searchFormData.startYear + "&endYear=" + searchFormData.endYear +
        "&wExact=" + searchFormData.wExact.toString() + "&wPart=" + searchFormData.wPart.toString() +
        "&searchKw=" + searchFormData.searchClefs.toString() + "&searchTitles=" + searchFormData.searchTitres.toString() + "&searchComments=" + searchFormData.searchComment.toString() + "&photoId=" + searchFormData.photoId +
        "&idUnique=" + searchFormData.idUnique.toString() + "&idContext=" + searchFormData.idContext.toString(), true);
    xhr.onload = function () {
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        turnOffSearchFolders();
        renderFamilyPhotos();
    };
    xhr.send();
}

function getSelectedInfoPhoto() {
    'use strict';
    photoInfoList = new lisPhotoInfo(JSON.parse(localStorage.getItem("photoInfoList")));
    searchChoice.setSearchPageStatus(false);

    const url = new URL(window.location.href);
    const selectedPhotoId = parseInt(url.searchParams.get('pid'), 10);
    selectedPhotoIdx = new photoInfoIdxIncrementer(parseInt(url.searchParams.get('currIdx'), 10));
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/photoInfo.php?pid=' + selectedPhotoId +
        '&function=getInfo', true);
    xhr.onload = function () {
        const myInfoPhoto = JSON.parse(xhr.responseText);
        renderInfoPhoto(myInfoPhoto);
    };
    xhr.send();
}

function getPhotoInfoPrevious() {
    'use strict';
    if (selectedPhotoIdx.currentIdx() > 0) {
        const idx = selectedPhotoIdx.subtract();
        const xhr = new XMLHttpRequest();
        const infoList = photoInfoList.getPhotoInfoList();
        xhr.open('GET', 'php/photoInfo.php?pid=' + infoList[idx].idpho +
            '&function=getInfo', true);
        xhr.onload = function () {
            const myInfoPhoto = JSON.parse(xhr.responseText);
            renderInfoPhoto(myInfoPhoto);
        };
        xhr.send();
    }
}

function getPhotoInfoNext() {
    'use strict';
    const infoList = photoInfoList.getPhotoInfoList();
    if (selectedPhotoIdx.currentIdx() < infoList.length - 1) {
        const idx = selectedPhotoIdx.add();
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/photoInfo.php?pid=' + infoList[idx].idpho +
            '&function=getInfo', true);
        xhr.onload = function () {
            const myInfoPhoto = JSON.parse(xhr.responseText);
            renderInfoPhoto(myInfoPhoto);
        };
        xhr.send();
    }
}

function renderInfoPhoto(data) {
    'use strict';
    const infoContain = document.getElementsByClassName('data-box__photo');
    var infoInputs = document.getElementsByClassName('data-box__input');
    const infoContainer = infoContain[0];
    var htmlString = "";

    infoContainer.innerText = '';

    for (const obj of data) {
        const thumb = obj.prev_path + obj.filename;
        htmlString += "<div><img src=\"" + thumb + "\" ></div>";

        infoInputs[0].value = obj.idpho;
        infoInputs[1].value = obj.title;
        infoInputs[2].value = obj.keywords;
        infoInputs[3].value = obj.caption;
        infoInputs[4].value = obj.year;
        infoInputs[5].value = obj.geneolnames;
    }
    infoContainer.insertAdjacentHTML('beforeend', htmlString);

    if (geneolListDone.getState() === false) {
        getGeneologyList();
    }

}

function renderHomePhoto() {
    'use strict';
    const container = document.getElementsByClassName('home__photo');
    const archivesContainer = container[0];
    var htmlString = "";
    const listPhotos = selectedPhotos.getPhotos();

    for (const obj of listPhotos) {
        const imageURL = obj.path + obj.filename;

        htmlString += "<img class='home__img' src=\"" + imageURL + "\" alt=\"" + obj.title + "\">";
    }
    archivesContainer.insertAdjacentHTML('beforeend', htmlString);
}

function renderFamilyPhotos() {
    'use strict';
    const imgDisplay = document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'block';

    const familyContainer = document.getElementsByClassName('photos__imgs')[0];
    var htmlString = "";
    const listPhotos = selectedPhotos.getPhotos();

    imgDisplay.innerHTML = '';

    for (const obj of listPhotos) {
        const thumb = obj.prev_path + obj.filename;
        htmlString += "<div><img src=\"" + thumb + "\" alt=\"" + obj.caption + "\" title=\"" + obj.title + "\" class=\"thumbimg\"></div>\n";
    }
    familyContainer.insertAdjacentHTML('beforeend', htmlString);
    document.getElementsByClassName('photos__previous-folder')[0].disabled = false;
    document.getElementsByClassName('photos__next-folder')[0].disabled = false;
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
    const nextFolder = document.getElementsByClassName('photos__next-folder')[0];
    nextFolder.style.display = 'block';
    const previousFolder = document.getElementsByClassName('photos__previous-folder')[0];
    previousFolder.style.display = 'block';
    const btt = document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle = document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'block';
    const choice = searchChoice.getSearchPageStatus();
    if (!choice) {
        titleContainer.innerText = folderTitle.getTitle();
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
    const listPhotos = selectedPhotos.getPhotos();
    if (document.getElementById('search__radio-context').checked !== true) {
        titleContainer.innerText = '';
    } else {
        titleContainer.innerText = listPhotos[0].rptTitle;
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
    initAllYears();
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

function initSearchInputs() {
    'use strict';
    document.getElementsByClassName('search__key-words')[0].value = '';
    /* document.getElementById('search__year-start').value='start';
     document.getElementById('search__year-end').value='end';*/
    document.getElementById('search__radio-exact').checked = false;
    document.getElementById('search__radio-partial').checked = true;
    document.getElementById('search__keys').checked = true;
    document.getElementById('search__titles').checked = true;
    document.getElementById('search__comments').checked = true;
    document.getElementsByClassName('search__pid')[0].value = '';
    document.getElementById('search__radio-uniq').checked = true;
    document.getElementById('search__radio-context').checked = false;
}

function initAllYears() {
    'use strict';
    if (Object.keys(allYearsData).length === 0) {
        const xhr = new XMLHttpRequest();

        xhr.open('GET', 'php/getAllYears.php', true);
        xhr.onload = function () {
            allYearsData = new listOfAllYears(JSON.parse(xhr.responseText));
            renderAllYears();
        };
        xhr.send();
    }
}

function renderAllYears() {
    'use strict';
    var yearsFromContainer = document.getElementsByClassName('search__from-year')[0];
    var yearsToContainer = document.getElementsByClassName('search__to-year')[0];
    var optGroup = '';

    var htmlString = '<label for=\"search__year-start\" class=\"search__year-start\">De </label>\n' +
        '    <select onchange="getFollowingYears()" id=\"search__year-start\" class=\"search__select\" >\n' +
        '    <option selected value=\"start\">1839\n' +
        '    </option>\n';

    const years = allYearsData.listOfYears();

    for (const obj of years) {
        if (optGroup !== obj.decade) {
            if (optGroup !== '') {
                htmlString += '</optgroup>\n';
            }
            htmlString += '<optgroup label = \"' + obj.decade + '\">\n';
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
            optGroup = obj.decade;
        } else {
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsFromContainer.insertAdjacentHTML('beforeend', htmlString);

    optGroup = '';
    htmlString = '';
    htmlString += '<label for=\"search__year-end\" class=\"search__year-end\">À </label>\n' +
        '    <select id=\"search__year-end\" class=\"search__select\" >\n' +
        '    <option selected value=\"end\">2038\n' +
        '    </option>\n';

    for (const obj of years) {
        if (optGroup !== obj.decade) {
            if (optGroup !== '') {
                htmlString += '</optgroup>\n';
            }
            htmlString += '<optgroup label = \"' + obj.decade + '\">\n';
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
            optGroup = obj.decade;
        } else {
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsToContainer.insertAdjacentHTML('beforeend', htmlString);
}

function getFollowingYears() {
    'use strict';
    var optGroup = '';
    const fromYear = document.getElementsByClassName('search__select')[0].value;
    var yearsToContainer = document.getElementsByClassName('search__to-year')[0];
    var htmlString = '';

    yearsToContainer.innerText = "";

    optGroup = '';
    htmlString += '<label for=\"search__year-end\" class=\"search__year-end\">À </label>\n' +
        '    <select id=\"search__year-end\" class=\"search__select\" >\n' +
        '    <option selected value=\"end\">2038\n' +
        '    </option>\n';

    const years = allYearsData.listOfYears();

    for (const obj of years) {
        if (obj.year >= fromYear) {
            if (optGroup !== obj.decade) {
                if (optGroup !== '') {
                    htmlString += '</optgroup>\n';
                }
                htmlString += '<optgroup label = \"' + obj.decade + '\">\n';
                htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
                optGroup = obj.decade;
            } else {
                htmlString += '<option value=\"' + obj.year + '\">' + obj.year + '</option>\n';
            }
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsToContainer.insertAdjacentHTML('beforeend', htmlString);
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
    const nextFolder = document.getElementsByClassName('photos__next-folder')[0];
    nextFolder.style.display = 'none';
    const previousFolder = document.getElementsByClassName('photos__previous-folder')[0];
    previousFolder.style.display = 'none';
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
    var currWin = currentWindow();
    const evt = e ? e : window.event;
    // console.log(evt.key);
    switch (true) {
        case evt.key === 17:
            // ctrlPressed = true;
            break;
        case evt.key === 'ArrowLeft':
            if (currWin==='photoInfo.html'){
                getPhotoInfoPrevious();
            }else {
                if (!inFolders.getState()) {
                    prevImage();
                } else {
                    showPreviousFolder();
                }
            }
            break;
        case evt.key === 'ArrowRight':
            if (currWin==='photoInfo.html'){
                getPhotoInfoNext();
            }else {
                if (!inFolders.getState()) {
                    nextImage();
                } else {
                    showNextFolder();
                }
            }
            break;
        case evt.key >= 'a' && evt.key <= 'z':
            if (currWin === 'addFolder.html') {
                const butt = document.getElementsByClassName('data-box__go-button')[0];
                butt.disabled = false;
            }
            if (currWin === 'addPhotos.html') {
                const butt = document.getElementsByClassName('data-box__go-button')[0];
                butt.disabled = false;
            }
            break;
        case evt.key === 'Backspace':
            if (currWin === 'addFolder.html') {
                const titleLength = document.getElementsByClassName('data-box__text--photos');
                if (titleLength[0].value.length === 1) {
                    const butt = document.getElementsByClassName('data-box__go-button')[0];
                    butt.disabled = true;
                }
            }
            if (currWin === 'addPhotos.html') {
                const inputLength = document.getElementsByClassName('data-box__text--photos');
                if (inputLength[0].value.length === 0) {
                    const butt = document.getElementsByClassName('data-box__go-button')[0];
                    butt.disabled = true;
                }
            }
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
    inFolders.setState(false);
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
    img = prev.replace('preview', 'full');

    modalTitle = document.getElementsByClassName('photos__modal-title')[0];
    modalImg = document.getElementById('img01');
    captionText = document.getElementsByClassName('photos__caption')[0];
    modal.style.display = 'block';
    modalTitle.innerHTML = imgs[currentIdx].title;
    modalImg.src = img;
    captionText.innerHTML = imgs[currentIdx].alt;
    const listPhotos = selectedPhotos.getPhotos();
    const idxList = listPhotos[currentIdx].geneolidx.split(',');
    const namesList = listPhotos[currentIdx].geneolnames.split(',');

    geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
    photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx].idpho + ")</p>";

// Get the <span> element that closes the modal
    const span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = 'none';
        bdy.style.overflow = 'visible';
        inFolders.setState(true);
    };
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

        const listPhotos = selectedPhotos.getPhotos();
        const idxList = listPhotos[currentIdx - 1].geneolidx.split(',');
        const namesList = listPhotos[currentIdx - 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
        photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx - 1].idpho + ")</p>";
        currentIdx--;
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

        const listPhotos = selectedPhotos.getPhotos();
        const idxList = listPhotos[currentIdx + 1].geneolidx.split(',');
        const namesList = listPhotos[currentIdx + 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, namesList);
        photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx + 1].idpho + ")</p>";
        currentIdx++;
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

function editPhoto() {
    'use strict';
    const listPhotos = selectedPhotos.getPhotos();
    localStorage.setItem("photoInfoList", JSON.stringify(listPhotos));
    window.open('photoInfo.html?pid=' + listPhotos[currentIdx].idpho + '&currIdx=' + currentIdx);
    location.reload();
}

function rotatePhotoNegative() {
    'use strict';
    console.log(selectedPhotoIdx.currentIdx());
    const infoList = photoInfoList.getPhotoInfoList();
    const thumb = infoList[selectedPhotoIdx.currentIdx()].prev_path + infoList[selectedPhotoIdx.currentIdx()].filename;
    const full = infoList[selectedPhotoIdx.currentIdx()].path + infoList[selectedPhotoIdx.currentIdx()].filename;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/rotatePhoto.php?thumb=' + thumb + '&full=' + full + '&direction=90', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // document.getElementById("data-box__photo").reload();
            // var content = container.innerHTML;
            window.location.reload(true);
        }
    };
    xhr.send();
}

function rotatePhotoPositive() {
    'use strict';
    const infoList = photoInfoList.getPhotoInfoList();
    const thumb = infoList[selectedPhotoIdx.currentIdx()].prev_path + infoList[selectedPhotoIdx.currentIdx()].filename;
    const full = infoList[selectedPhotoIdx.currentIdx()].path + infoList[selectedPhotoIdx.currentIdx()].filename;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/rotatePhoto.php?thumb=' + thumb + '&full=' + full + '&direction=-90', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            // document.getElementById("data-box__photo").reload();
            // var content = container.innerHTML;
            window.location.reload(true);
        }
    };
    xhr.send();
}

function imgClick(e) {
    'use strict';
    // Reset opacity
    imgs.forEach(img => (img.style.opacity = 1));

    // Change current image to src of clicked image
    prev = e.target.src;
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

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/getReadings.php?path=' + path, true);
    xhr.onload = function () {
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        const listPhotos = selectedPhotos.getPhotos();
        renderReadings(listPhotos);
    };
    xhr.send();
}

function renderReadings(data) {
    'use strict';
    const container = document.getElementsByClassName('readings');
    const readingsContainer = container[0];
    var htmlString = '';

    readingsContainer.innerHTML = '';

    for (const obj of data) {
        let intro = '';
        if (obj.intro === null) {
            intro = '';
        } else {
            intro = obj.intro;
        }

        htmlString += "<div class=\"clearfix\">" + "<a href=\"" + obj.address + "\" target=\"_blank\">" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"readings__books\">" +
            "<p class=\"readings__title\">" + obj.title + "</p></a>" +
            "<p class=\"readings__summary\">" + intro + "</p>" + "<p class=\"readings__summary\">" +
            obj.sumary + "</p ><br></div>";
    }
    readingsContainer.insertAdjacentHTML('beforeend', htmlString);
}

/*** OBJECT SECTION
 ******************/

function getObjects() {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/getObjects.php?path=' + 12, true);
    xhr.onload = function () {
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        const listPhotos = selectedPhotos.getPhotos();
        renderObjects(listPhotos);
    };
    xhr.send();
}

function renderObjects(data) {
    'use strict';
    const container = document.getElementsByClassName('objects__container');
    const objectsContainer = container[0];
    var htmlString = "";

    for (const obj of data) {
        htmlString += "<div class=\"clearfix\">\n" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"objects__img\" title='Cliquer pour agrandir la photo'>\n" +
            "<p class=\"objects__description\" >" + obj.description + "\n</p >\n<br>\n</div>\n";
    }
    objectsContainer.insertAdjacentHTML('beforeend', htmlString);
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

/*** ADD folder
 ********************************/
function getFolderInputs() {
    'use strict';
    const folderDoc = document.getElementsByClassName('data-box__select');

    folderData.levels = folderDoc[0].value;
    folderData.type = folderDoc[1].value;
    folderData.author = folderDoc[2].value;
    folderData.decade = folderDoc[3].value;
    folderData.year = folderDoc[4].value;
    folderData.title = folderDoc[5].value;
}

function getFolderInputsPhotos() {
    'use strict';
    const folderDoc = document.getElementsByClassName('data-box__select');

    folderData.type = folderDoc[0].value;
    folderData.author = folderDoc[1].value;
    folderData.decade = folderDoc[2].value;
    folderData.year = folderDoc[3].value;
    folderData.title = folderDoc[4].value;
}

function addFolder() {
    'use strict';
    getFolderInputs();
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/folders.php?type=' + folderData.type +
        '&author=' + folderData.author + '&decade=' + folderData.decade + '&year=' + folderData.year +
        '&title=' + folderData.title + '&levels=' + folderData.levels + '&function=addFolder', true);
    xhr.onload = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const xhr1 = new XMLHttpRequest();
                xhr1.open('GET', 'php/folders.php?type=' + folderData.type +
                    '&author=' + folderData.author + '&decade=' + folderData.decade + '&year=' + folderData.year +
                    '&title=' + folderData.title + '&levels=' + folderData.levels + '&function=addFolderMysql', true);
                xhr1.onload = function () {
                    if (xhr1.readyState === 4) {
                        if (xhr1.status === 200) {
                            document.getElementsByClassName('data-box__message')[0].style.display = 'block';
                        }
                    }
                };
                xhr1.send();
            }
        }
    };
    xhr.send();
}

function uploadPhotos() {
    'use strict';
    getFolderInputsPhotos();

    const url = 'php/upload.php';
    const files = document.getElementById('data-box__input--photos').files;
    const formData = new FormData();

    if (files.length > 0) {
        const butt = document.getElementsByClassName('data-box__go-button')[0];
        butt.disabled = false;

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            formData.append('files[]', file);
        }

        formData.append('type', folderData.type);
        formData.append('author', folderData.author);
        formData.append('decade', folderData.decade);
        formData.append('year', folderData.year);
        formData.append('title', folderData.title);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => {
        });
    } else {
        const butt = document.getElementsByClassName('data-box__go-button')[0];
        butt.disabled = false;
        document.getElementsByClassName('data-box__message')[0].style.display = 'block';
    }
}

function getDecades() {
    'use strict';
    const req = new XMLHttpRequest();
    req.open('GET', 'php/getDecades.php', true);
    req.onload = function () {
        const decadesData = JSON.parse(req.responseText);
        renderDecades(decadesData);
        getYearsSelected();
        disableSubmitButton();
    };
    req.send();
}

function getYearsSelected() {
    'use strict';
    const url = currentWindow();
    const deca = document.getElementsByClassName('data-box__select--add-folder-photo-decade');
    const decade = deca[0].value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/getYears.php?decade=' + decade, true);
    xhr.onload = function () {
        const yearsData = JSON.parse(xhr.responseText);
        renderYears(yearsData);
        if (url === 'addPhotos.html') {
            const firstYear = yearsData[0].idxYear;
            getFolders(firstYear);
        }
    };
    xhr.send();
}

function renderDecades(decades) {
    'use strict';
    const decadeContainer = document.getElementsByClassName('data-box__select--add-folder-photo-decade')[0];
    var htmlString = "";

    for (obj of decades) {
        htmlString += "<option value=\"" + obj.idDecade + "\">" + obj.decade + "</option>\n";
    }
    decadeContainer.insertAdjacentHTML("beforeend", htmlString);
}

function renderYears(data) {
    'use strict';
    const yearContainer = document.getElementsByClassName('data-box__select--add-folder-photo-year');
    yearContainer[0].innerHTML = '';
    var htmlString = "";

    for (const obj of data) {
        htmlString += "<option value=\"" + obj.idxYear + "\">" + obj.year + "</option>\n";
    }
    htmlString += "<option value=\"1\">NA</option>";
    yearContainer[0].insertAdjacentHTML('beforeend', htmlString);
}

function getFolders(fisrtYear) {
    'use strict';
    var year;
    if (fisrtYear === undefined) {
        const y = document.getElementsByClassName('data-box__select--add-folder-photo-year');
        year = y[0].value;
    } else {
        year = fisrtYear;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/getFolders.php?year=' + year, true);
    xhr.onload = function () {
        const folderData = JSON.parse(xhr.responseText);
        renderFolders(folderData);
    };

    xhr.send();
}

function renderFolders(folderData) {
    'use strict';
    var folderContainer = document.getElementsByClassName('data-box__select--add-ph-title');
    var htmlString = "";
    folderContainer[0].innerHTML = '';

    for (const obj of folderData) {
        htmlString += "<option value=\"" + obj.folder + "\">" + obj.title + "</option>";
    }
    folderContainer[0].insertAdjacentHTML('beforeend', htmlString);
}

function renderSelectedPhotos() {
    'use strict';
    document.getElementsByClassName('data-box__message')[0].style.display = 'none';
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

function insertPhotoInfo() {
    'use strict';
    var req = new XMLHttpRequest();
    var inputs = getPhotoInfoInputs();

    req.open('POST', 'php/photoInfo.php?photoId=' + inputs.photoId + '&title=' + inputs.title + '&keyWords=' + inputs.keyWords +
        '&caption=' + inputs.caption + '&year=' + inputs.year + '&geneologyIdxs=' + inputs.geneologyIdxs +
        '&function=insertPhotoInfo', true);
    req.onload = function () {
        let success = true;
    };
    req.send();
}

function getPhotoInfoInputs() {
    'use strict';
    var infoInputs = [];
    infoInputs.photoId = document.getElementsByClassName('data-box__photo-id')[0].value;
    infoInputs.title = document.getElementsByClassName('data-box__input--info-title')[0].value;
    infoInputs.keyWords = document.getElementsByClassName('data-box__input--info-keywords')[0].value;
    infoInputs.caption = document.getElementsByClassName('data-box__input--info-caption')[0].value;
    infoInputs.year = document.getElementsByClassName('data-box__input--info-year')[0].value;
    infoInputs.geneologyIdxs = document.getElementsByClassName('data-box__input--info-geneol')[0].value;
    infoInputs.geneologyIdxs = validatePhotoInfoIndexes(infoInputs.geneologyIdxs);
    return infoInputs;
}

function validatePhotoInfoIndexes(listOfIndexes) {
    'use strict';
    const listIdx = listOfIndexes.split(/\s,\s*/);
    var indexes = '';
    var index = '';
    var names = '';

    for (var i = 0; i < listIdx.length; i++) {
        for (const obj of namesList) {
            if (listIdx[i] === obj.name) {
                index = obj.idx;
                if (indexes.length !== 0) {
                    indexes += ' , ' + index;
                } else {
                    indexes = index;
                }
                break;
            }
        }
    }
    return indexes;
}

function getGeneologyList() {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/getGeneologyList.php', true);
    xhr.responseType = 'JSON';
    xhr.onload = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const jsGeneolList = JSON.parse(xhr.responseText);
                renderGeneologyList(jsGeneolList);
                geneolListDone.setState(true);
            }
        }
    };
    xhr.send();
}

function renderGeneologyList(rawData) {
    'use strict';
    var listContainer = document.getElementsByClassName('data-box__geneol-list')[0];
    var optGroup = '';
    var htmlString = "";
    var names = [];

    htmlString = '<select onchange="addGeneolNames()" id=\"data-box__geneol-list\" class=\"data-box__ select data-box__select--geneol\" >\n' +
        '    <option selected value=\"choice\">Faites un choix\n' +
        '    </option>\n';

    for (const obj of rawData) {
        htmlString += '<option value=\"' + obj.idgen + '\">' + obj.name + '</option>\n';
        const names = {'idx': obj.idgen, 'name': obj.name};
        namesList.push(names);
    }

    htmlString += '</select><br>';

    listContainer.insertAdjacentHTML('beforeend', htmlString);
}

function addGeneolNames() {
    'use strict';
    let geneolList = document.getElementsByClassName('data-box__input--info-geneol');
    var names = geneolList[0].value;
    var selectGeneol = document.getElementsByClassName('select data-box__select--geneol')[0].value;
    var name = '';

    for (const obj of namesList) {
        if (selectGeneol === obj.idx) {
            name = obj.name;
            break;
        }
    }
    if (names.length !== 0) {
        names += ' , ' + name;

    } else {
        names = name;
    }
    geneolList[0].value = names;
}

function currentWindow() {
    'use strict';
    const currWin = window.location.href.match(/^[^\#\?]+/)[0];
    const n = currWin.lastIndexOf('/');
    return currWin.substring(n + 1);
}

function getShiftingFolders() {
    'use strict';
    const xhr = new XMLHttpRequest();

    xhr.open('GET', 'php/folders.php?function=getShiftingFolders');
    xhr.onload = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                jsShiftingFolders = new listShiftingFolders(JSON.parse(xhr.responseText));
            }
        }
    };
    xhr.send();
}

function showNextFolder() {
    'use strict';
    document.getElementsByClassName('photos__previous-folder')[0].disabled = true;
    document.getElementsByClassName('photos__next-folder')[0].disabled = true;
    const folders = jsShiftingFolders.getShiftingFolders();
    if (currentShiftingFolder.currentIdx() < folders.length) {
        const idx = currentShiftingFolder.addOne();
        folderTitle.setTitle(folders[idx].title);
        const path = folders[idx].folder;
        getPhotos(path, 2);
    }
}

function showPreviousFolder() {
    'use strict';
    document.getElementsByClassName('photos__previous-folder')[0].disabled = true;
    document.getElementsByClassName('photos__next-folder')[0].disabled = true;
    const folders = jsShiftingFolders.getShiftingFolders();
    if (currentShiftingFolder.currentIdx() >= 0) {
        const idx = currentShiftingFolder.subtractOne();
        const path = folders[idx].folder;
        folderTitle.setTitle(folders[idx].title);
        getPhotos(path, 2);
    }
}

function disableSubmitButton() {
    'use strict';
    const currWin = currentWindow();
    if (currWin === 'addFolder.html;') {
        const butt = document.getElementsByClassName('data-box__go-button')[0];
        butt.disabled = true;
    }
}

/*** Closures ***/
function photoInfoIdxIncrementer(pIndex) {
    'use strict';
    var _index = pIndex;

    this.add = function () {
        _index += 1;
        return _index;
    };
    this.subtract = function () {
        _index -= 1;
        return _index;
    };
    this.currentIdx = function () {
        return _index;
    };
}

function lisPhotoInfo(pList) {
    'use strict';
    var _list = pList;

    this.getPhotoInfoList = function () {
        return _list;
    }
}

function listOfAllYears(pYears) {
    'use strict';
    var _years = pYears;

    this.listOfYears = function () {
        return _years;
    };
}

function searchPhotos(pPage) {
    'use strict';
    var _search = pPage;

    this.setSearchPageStatus = function (status) {
        _search = status;
    };
    this.getSearchPageStatus = function () {
        return _search;
    };
}

function directoryTitle() {
    'use strict';
    var _title = "";

    this.setTitle = function (title) {
        _title = title;
    };
    this.getTitle = function () {
        return _title;
    };
}

function photos() {
    'use strict';
    var _photos = "";

    this.setPhotos = function (list) {
        _photos = list;
    };
    this.getPhotos = function () {
        return _photos;
    };
}

function images() {
    'use strict';
    var _images = "";

    this.setImages = function (img) {
        _images = img;
    };
    this.getImages = function () {
        return _images;
    };
}

function shiftingFoldersIdx(pIdx) {
    'use strict';
    var _folderIdx = pIdx;

    this.addOne = function () {
        _folderIdx += 1;
        return _folderIdx;
    };
    this.subtractOne = function () {
        _folderIdx -= 1;
        return _folderIdx;
    };
    this.currentIdx = function () {
        return _folderIdx;
    };
}

function listShiftingFolders(pFolders) {
    'use strict';
    var _shiftingFolders = pFolders;

    this.getShiftingFolders = function () {
        return _shiftingFolders;
    };
}

function geneologyList(pState) {
    'use strict';
    var _state = pState;

    this.setState = function(state){
        _state=state;
    };
    this.getState = function () {
        return _state;
    };
}

function inFoldersState(pState) {
    'use strict';
    var _state = pState;

    this.setState = function (state) {
        _state = state;
    }
    this.getState = function () {
        return _state;
    }
}