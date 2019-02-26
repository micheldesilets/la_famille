/*** Michel Desilets 2018 ***/

/*global describe:true*/
/*global console:true*/
/*jshint loopfunc: true */

var searchChoice = new searchPhotos(false);
var folderTitle = new directoryTitle();
var selectedPhotos = new photos();
var photoInfoList = {};
var selectedPhotoIdx = {};
var currentShiftingFolder = {};
var allYearsData = {};
var folderTree = new folderHierarchy();
var listPhotosDownload = new photosForDownload();
var memberInfo = new Member();
var FolderFirstLevel = new FolderLevel1();
var FolderSecondLevel = new FolderLevel2();
var FolderThirdLevel = new FolderLevel3();

var getUserPerm = () => {
    'use strict';
    const perm = document.getElementById('userPerm').value;
    user.setPerm(perm);
    console.log(perm);
};

var getFolderTree = () => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/FoldersController.php?function=getFoldersTree',
        true);
    xhr.onload = () => {
        const folderData = JSON.parse(xhr.responseText);
        buildFolderTree(folderData);
    };
    xhr.send();
};

/*** Family Photos ***/
var buildFolderTree = (data) => {
    'use strict';
    const folderContainer = document.getElementById("photos__folders");

    for (const branch of data) {
        if (folderTree.getMember() !== branch.member &&
            branch.member.length > 0) {
            if (folderTree.getMember() !== "" &&
                folderTree.getLevel() === "4") {
                folderTree.addToHtmlString(
                    "</ul>\n" +
                    "</li>\n" +
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n");
            }
            if (folderTree.getMember() !== "" &&
                folderTree.getLevel() === "2") {
                folderTree.addToHtmlString(
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n");
            }
            folderTree.addJcounter();
            const j = folderTree.getJcounter();
            folderTree.addToHtmlString(
                "<input type=\"checkbox\" id=\"folders__menu" + j +
                "\"/>\n" + "<label for=\"folders__menu" + j +
                "\" class=\"folders__names\">" + branch.member + "</label>\n" +
                "<div class=\"folders__multi-level" + j + "\">\n");
            folderTree.setMember(branch.member);
            folderTree.setDecade("");
        }

        folderTree.setLevel(branch.levels);
        switch (folderTree.getLevel()) {
            case "2":
                folderLevel2(branch);
                break;
            case "4":
                folderLevel4(branch);
                break;
        }
    }

    if (folderTree.getLevel() === "4") {
        folderTree.addToHtmlString(
            "</ul>\n" +
            "</div>\n" +
            "</li>\n" +
            "</ul>\n" +
            "</div>\n" +
            "</div>\n");
    }

    folderContainer.insertAdjacentHTML("beforeend",
        folderTree.getHtmlString());
};

var folderLevel2 = (branch) => {
    'use strict';
    if (folderTree.getDecade() !== branch.decade &&
        branch.decade.length > 0) {
        folderTree.addToHtmlString(
            "<div class=\"folders__itemL2\">\n" +
            "<ul>\n");
        folderTree.setDecade(branch.decade);
    }

    if ((folderTree.getMember() === branch.member &&
        branch.member.length > 0) &&
        (folderTree.getFolderId() !== branch.folder)) {

        folderTree.addToHtmlString(
            "<li class='folders__photofolder L2' " +
            "onclick='getFamilyPhotos(this," + branch.folder + "," +
            branch.type + ")'>" + branch.title + "</li>\n");
    }
};

// TODO Add folder level 3

var folderLevel4 = (branch) => {
    'use strict';
    if (folderTree.getDecade() !== branch.decade &&
        branch.decade.length > 0) {
        if (folderTree.getDecade() !== "") {
            folderTree.addToHtmlString(
                "</ul>\n" +
                "</div>\n" +
                "</li>\n" +
                "</ul>\n" +
                "</div>\n");
        }
        folderTree.addItem();
        const itm = folderTree.getItem();
        folderTree.setSubItem(0);
        folderTree.addToHtmlString(
            "<div class=\"folders__item\">\n" +
            "<input type=\"checkbox\" id=\"IT" + itm + "\"/>\n" +
            "<img src=\"../../public/img/icons/arrow.png\" " +
            "class=\"folders__arrow\">\n" +
            "<label for=\"IT" + itm + "\">" + branch.decade + "</label>\n" +
            "<ul>\n");
        folderTree.setDecade(branch.decade);
        folderTree.setYear(0);
    }

    if (folderTree.getYear() !== branch.year && branch.year.length > 0) {
        if (folderTree.getYear() !== 0) {
            folderTree.addToHtmlString(
                "</ul>\n" +
                "</div>\n" +
                "</li>\n");
        }
        const itm = folderTree.getItem();
        folderTree.addSubItem();
        const sbitm = folderTree.getSubItem();
        folderTree.addToHtmlString(
            "<li>\n" +
            "<div class=\"folders__sub-item\">\n" +
            "<input type=\"checkbox\" id=\"SIT" + itm + "-" + sbitm + "\"/>\n" +
            "<img src=\"../../public/img/icons/arrow.png\" " +
            "class=\"folders__arrow\">\n" +
            "<label for=\"SIT" + itm + "-" + sbitm + "\">" + branch.year +
            "</label>\n" +
            "<ul>\n");
        folderTree.setYear(branch.year);
    }
    if ((folderTree.getMember() === branch.member &&
        branch.member.length > 0) &&
        (folderTree.getDecade() === branch.decade &&
            branch.decade.length > 0) &&
        (folderTree.getYear() === branch.year && branch.year.length > 0) &&
        (folderTree.getFolderId() !== branch.folder)) {
        folderTree.addToHtmlString(
            "<li class=\"folders__photofolder\" value=\"0\" " +
            "onclick=\"getFamilyPhotos(this," + branch.folder + "," +
            branch.type + ")\">" + branch.title + "</li>\n");
    }
};

var getFamilyPhotos = (obj, path, type) => {
    'use strict';
    folderTree.setFolderId(path);
    inFoldersState.setState(true);
    const folders = listShiftingFolders.getShiftingFolders();
    for (let i = 0; i < folders.length; ++i) {
        if (path === parseInt(folders[i].folder)) {
            currentShiftingFolder = new shiftingFoldersIdx(i);
            break;
        }
    }
    folderTitle.setTitle(obj.innerHTML);
    getPhotos(path, type);
};

var getPhotos = (path, type) => {
    'use strict';
    searchChoice.setSearchPageStatus(false);
    try {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "../../private/php/controllers/PhotosController.php?path=" + path +
            '&function=getPhotos', true);
        xhr.onload = () => {
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
    } catch (err) {
        alert(err.message);
    }
};

var searchInputs = () => {
    'use strict';
    searchChoice.setSearchPageStatus(true);
    // searchChoice = true;
    folderTitle.setTitle("");
    const searchFormData = getSearchInputs();

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "../../private/php/controllers/PhotosController.php?kwrd=" +
        searchFormData.kwords + "&startYear=" + searchFormData.startYear +
        "&endYear=" + searchFormData.endYear + "&wExact=" +
        searchFormData.wExact.toString() + "&wPart=" +
        searchFormData.wPart.toString() + "&searchKw=" +
        searchFormData.searchClefs.toString() + "&searchTitles=" +
        searchFormData.searchTitres.toString() + "&searchComments=" +
        searchFormData.searchComment.toString() + "&photoId=" +
        searchFormData.photoId + "&idUnique=" +
        searchFormData.idUnique.toString() + "&idContext=" +
        searchFormData.idContext.toString() + '&function=getSearchPhotos', true);
    xhr.onload = () => {
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        turnOffSearchFolders();
        renderFamilyPhotos();
    };
    xhr.send();
};

var getSelectedInfoPhoto = () => {
    'use strict';
    geneologyList.setState(false);
    photoInfoList =
        new listPhotoInfo(JSON.parse(localStorage.getItem("photoInfoList")));
    searchChoice.setSearchPageStatus(false);

    const url = new URL(window.location.href);
    const selectedPhotoId = parseInt(url.searchParams.get('pid'), 10);
    selectedPhotoIdx =
        new photoInfoIdxIncrementer(parseInt(url.searchParams.get('currIdx'), 10));
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/controllers/PhotosController.php?pid=' +
        selectedPhotoId + '&function=getInfo', true);
    xhr.onload = () => {
        const myInfoPhoto = JSON.parse(xhr.responseText);
        renderInfoPhoto(myInfoPhoto);
    };
    xhr.send();
};

var getPhotoInfoPrevious = () => {
    'use strict';
    if (selectedPhotoIdx.currentIdx() > 0) {
        const idx = selectedPhotoIdx.subtract();
        const xhr = new XMLHttpRequest();
        const infoList = photoInfoList.getPhotoInfoList();
        xhr.open('GET', '../../private/php/PhotosController.php?pid=' +
            infoList[idx].idpho +
            '&function=getInfo', true);
        xhr.onload = () => {
            const myInfoPhoto = JSON.parse(xhr.responseText);
            renderInfoPhoto(myInfoPhoto);
        };
        xhr.send();
    }
};

var getPhotoInfoNext = () => {
    'use strict';
    const infoList = photoInfoList.getPhotoInfoList();
    if (selectedPhotoIdx.currentIdx() < infoList.length - 1) {
        const idx = selectedPhotoIdx.add();
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../../private/php/PhotosController.php?pid=' +
            infoList[idx].idpho +
            '&function=getInfo', true);
        xhr.onload = () => {
            const myInfoPhoto = JSON.parse(xhr.responseText);
            renderInfoPhoto(myInfoPhoto);
        };
        xhr.send();
    }
};

var renderInfoPhoto = (data) => {
    'use strict';
    const infoContain =
        document.getElementsByClassName('data-box__photo');
    var infoInputs =
        document.getElementsByClassName('data-box__input');
    const infoContainer = infoContain[0];
    var htmlString = "";

    infoContainer.innerText = '';

    for (const obj of data) {
        const thumb = obj.prev_path + obj.filename;
        htmlString += "<div><img src=\"../../" + thumb + "\" ></div>";

        infoInputs[0].value = obj.idpho;
        infoInputs[1].value = obj.title;
        infoInputs[2].value = obj.keywords;
        infoInputs[3].value = obj.caption;
        infoInputs[4].value = obj.year;
        infoInputs[5].value = obj.geneolnames;
    }
    infoContainer.insertAdjacentHTML('beforeend', htmlString);

    if (geneologyList.getState() === false) {
        getGeneologyList();
    }
};

var renderHomePhoto = () => {
    'use strict';
    const container = document.getElementsByClassName('home__photo');
    const archivesContainer = container[0];
    var htmlString = "";
    const listPhotos = selectedPhotos.getPhotos();

    for (const obj of listPhotos) {
        const imageURL = obj.path + obj.filename;

        htmlString += "<img class='home__img' src=\"../../" + imageURL +
            "\" alt=\"" + obj.title + "\">";
    }
    archivesContainer.insertAdjacentHTML('beforeend', htmlString);
};

var renderFamilyPhotos = () => {
    'use strict';
    const imgDisplay =
        document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'block';

    const familyContainer =
        document.getElementsByClassName('photos__imgs')[0];
    var htmlString = "";
    const listPhotos = selectedPhotos.getPhotos();

    imgDisplay.innerHTML = '';

    for (const obj of listPhotos) {
        const thumb = obj.prev_path + obj.filename;
        htmlString += "<div><img src=\"../../" + thumb + "\" alt=\"" +
            obj.caption + "\" title=\"" + obj.title +
            "\" class=\"thumbimg\"></div>\n";
    }
    familyContainer.insertAdjacentHTML('beforeend', htmlString);
    document.getElementsByClassName('photos__previous-folder')[0].disabled =
        false;
    document.getElementsByClassName('photos__next-folder')[0].disabled = false;
    animatePhotos();
};

var turnOffFolders = () => {
    'use strict';
    const titleContainer =
        document.getElementsByClassName('photos__thumb-title')[0];
    // Hide search and tree and bring up back to tree button
    document.getElementById('photos__folders').style.display = 'none';
    const kw = document.getElementsByClassName('search__keyword')[0];
    kw.style.display = 'none';
    const element =
        document.getElementsByClassName('search__search-button');
    element[0].style.display = 'none';
    const nextFolder =
        document.getElementsByClassName('photos__next-folder')[0];
    nextFolder.style.display = 'block';
    const previousFolder =
        document.getElementsByClassName('photos__previous-folder')[0];
    previousFolder.style.display = 'block';

    if (user.getPerm() === '1') {
        document.getElementsByClassName('photos__delete-photos')[0].style.display =
            'none';
    }
    document.getElementsByClassName('photos__download-photos')[0].style.display =
        'none';
    const btt =
        document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle =
        document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'block';
    const choice = searchChoice.getSearchPageStatus();
    if (!choice) {
        titleContainer.innerText = folderTitle.getTitle();
    } else {
        titleContainer.innerText = '';
    }
};

var turnOffSearchFolders = () => {
    'use strict';
    const titleContainer =
        document.getElementsByClassName('photos__thumb-title')[0];
    // Hide search and tree and bring up back to tree button
    document.getElementById('photos__folders').style.display = 'none';
    const kword =
        document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'none';
    const fmButton =
        document.getElementsByClassName('search__search-button')[0];
    fmButton.style.display = 'none';
    const btt =
        document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle =
        document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'block';
    const listPhotos = selectedPhotos.getPhotos();
    if (document.getElementById('search__radio-context').checked !== true) {
        titleContainer.innerText =
            listPhotos.length + ' photo(s) trouvée(s)';
    } else {
        titleContainer.innerText = listPhotos[0].rptTitle;
    }
    btt.onclick = () => {
        backToSearch();
    };
};

/*** SEARCH
 **********************************/
var initSearchForm = () => {
    'use strict';
    prepareSearchScreen();
    initAllYears();
    initSearchInputs();
};

var searchForm = () => {
    'use strict';
    prepareSearchScreen();
};

var prepareSearchScreen = () => {
    'use strict';
    const buttn =
        document.getElementsByClassName('search__search-button');
    buttn[0].style.display = 'none';
    document.getElementById('photos__folders').style.display = 'none';
    const btt =
        document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const kword =
        document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'block';
    // document.getElementById('searchKw').style.display = 'block';
    btt.onclick = () => {
        backToTree();
    };
};

var initSearchInputs = () => {
    'use strict';
    document.getElementsByClassName('search__key-words')[0].value = '';
    document.getElementById('search__radio-exact').checked = false;
    document.getElementById('search__radio-partial').checked = true;
    document.getElementById('search__keys').checked = true;
    document.getElementById('search__titles').checked = true;
    document.getElementById('search__comments').checked = true;
    document.getElementsByClassName('search__pid')[0].value = '';
    document.getElementById('search__radio-uniq').checked = true;
    document.getElementById('search__radio-context').checked = false;
};

var initAllYears = () => {
    'use strict';
    if (Object.keys(allYearsData).length === 0) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '../../private/php/YearsController.php?function=getAllYears', true);
        xhr.onload = () => {
            allYearsData = new listOfAllYears(JSON.parse(xhr.responseText));
            renderAllYears();
        };
        xhr.send();
    }
};

var renderAllYears = () => {
    'use strict';
    var yearsFromContainer =
        document.getElementsByClassName('search__from-year')[0];
    var yearsToContainer =
        document.getElementsByClassName('search__to-year')[0];
    var optGroup = '';

    var htmlString = '<label for=\"search__year-start\" ' +
        'class=\"search__year-start\">De </label>\n' +
        '    <select onchange="getFollowingYears()" ' +
        'id=\"search__year-start\" class=\"search__select\" >\n' +
        '    <option selected value=\"start\">1839\n' +
        '    </option>\n';

    const years = allYearsData.listOfYears();

    for (const obj of years) {
        if (optGroup !== obj.decade) {
            if (optGroup !== '') {
                htmlString += '</optgroup>\n';
            }
            htmlString += '<optgroup label = \"' + obj.decade + '\">\n';
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                '</option>\n';
            optGroup = obj.decade;
        } else {
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                '</option>\n';
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsFromContainer.insertAdjacentHTML('beforeend', htmlString);

    optGroup = '';
    htmlString = '';
    htmlString += '<label for=\"search__year-end\" class=\"search__year-end\">À ' +
        '</label>\n' + '<select id=\"search__year-end\" ' +
        'class=\"search__select\" >\n' +
        '    <option selected value=\"end\">2038\n' +
        '    </option>\n';

    for (const obj of years) {
        if (optGroup !== obj.decade) {
            if (optGroup !== '') {
                htmlString += '</optgroup>\n';
            }
            htmlString += '<optgroup label = \"' + obj.decade + '\">\n';
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                '</option>\n';
            optGroup = obj.decade;
        } else {
            htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                '</option>\n';
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsToContainer.insertAdjacentHTML('beforeend', htmlString);
};

var getFollowingYears = () => {
    'use strict';
    var optGroup = '';
    const fromYear =
        document.getElementsByClassName('search__select')[0].value;
    var yearsToContainer =
        document.getElementsByClassName('search__to-year')[0];
    var htmlString = '';

    yearsToContainer.innerText = "";

    optGroup = '';
    htmlString += '<label for=\"search__year-end\" class=\"search__year-end\">' +
        '    À </label>\n' +
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
                htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                    '</option>\n';
                optGroup = obj.decade;
            } else {
                htmlString += '<option value=\"' + obj.year + '\">' + obj.year +
                    '</option>\n';
            }
        }
    }
    htmlString += '</optgroup>\n' +
        '</select><br>';

    yearsToContainer.insertAdjacentHTML('beforeend', htmlString);
};

var getSearchInputs = () => {
    'use strict';
    const searchData = [];
    searchData.kwords =
        document.getElementsByClassName('search__key-words')[0].value;
    searchData.startYear =
        document.getElementById('search__year-start').value;
    searchData.endYear =
        document.getElementById('search__year-end').value;
    searchData.wExact =
        document.getElementById('search__radio-exact').checked;
    searchData.wPart =
        document.getElementById('search__radio-partial').checked;
    searchData.searchClefs =
        document.getElementById('search__keys').checked;
    searchData.searchTitres =
        document.getElementById('search__titles').checked;
    searchData.searchComment =
        document.getElementById('search__comments').checked;
    if (document.getElementsByClassName('search__pid'[0]).value === '') {
        searchData.photoId = 'nothing';
    } else {
        searchData.photoId =
            document.getElementsByClassName('search__pid')[0].value;
    }
    searchData.idUnique =
        document.getElementById('search__radio-uniq').checked;
    searchData.idContext =
        document.getElementById('search__radio-context').checked;
    return searchData;
};

var backToTree = () => {
    'use strict';
    // Bring back search and tree and hide 'back to tree(X)' button
    const imgDisplay =
        document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'none';
    const kword =
        document.getElementsByClassName('search__keyword')[0];
    kword.style.display = 'none';
    const nextFolder =
        document.getElementsByClassName('photos__next-folder')[0];
    nextFolder.style.display = 'none';
    const previousFolder =
        document.getElementsByClassName('photos__previous-folder')[0];
    previousFolder.style.display = 'none';
    if (user.getPerm() === '1') {
        document.getElementsByClassName('photos__delete-photos')[0].style.display =
            'none';
    }
    document.getElementsByClassName('photos__download-photos')[0].style.display =
        'none';
    const btt =
        document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'none';
    const thumbTitle =
        document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'none';
    document.getElementById('photos__folders').style.display = 'block';
    const searchBtn = document.getElementsByClassName('search__search-button');
    searchBtn[0].style.display = 'block';
};

var backToSearch = () => {
    'use strict';
    const imgDisplay =
        document.getElementsByClassName('photos__imgs')[0];
    imgDisplay.style.display = 'none';
    const btt =
        document.getElementsByClassName('search__back-to-tree')[0];
    btt.style.display = 'block';
    const thumbTitle =
        document.getElementsByClassName('photos__thumb-title')[0];
    thumbTitle.style.display = 'none';
    searchForm();
};

var isKeyPressed = (event) => {
    'use strict';
    if (event.shiftKey) {
        // alert("The SHIFT key was pressed!");
        listPhotosDownload.setShiftKey(true);
    } else {
        listPhotosDownload.setShiftKey(false);
        // alert("The SHIFT key was NOT pressed!");
    }
};

/* Controle of right and left keys
**********************************/
document.onkeydown = (e) => {
    'use strict';
    var currWin = currentWindow();
    const evt = e ? e : window.event;
    switch (true) {
        case evt.key === 'Shift':
            // listPhotosDownload.setShiftKey(true);
            break;
        case evt.key === 17:
            // ctrlPressed = true;
            break;
        case evt.key === 'ArrowLeft':
            if (currWin === 'photoInfo.php') {
                getPhotoInfoPrevious();
            } else {
                if (!inFoldersState.getState()) {
                    prevImage();
                } else {
                    showPreviousFolder();
                }
            }
            break;
        case evt.key === 'ArrowRight':
            if (currWin === 'photoInfo.php') {
                getPhotoInfoNext();
            } else {
                if (!inFoldersState.getState()) {
                    nextImage();
                } else {
                    showNextFolder();
                }
            }
            break;
        case evt.key >= 'a' && evt.key <= 'z':
            if (currWin === 'addFolder.html') {
                const butt =
                    cument.getElementsByClassName('data-box__go-button')[0];
                butt.disabled = false;
            }
            if (currWin === 'addPhotos.php') {
                const butt =
                    document.getElementsByClassName('data-box__go-button')[0];
                butt.disabled = false;
            }
            break;
        case evt.key === 'Backspace':
            if (currWin === 'addFolder.html') {
                const titleLength =
                    document.getElementsByClassName('data-box__text--photos');
                if (titleLength[0].value.length === 1) {
                    const butt =
                        document.getElementsByClassName('data-box__go-button')[0];
                    butt.disabled = true;
                }
            }
            if (currWin === 'addPhotos.php') {
                const inputLength =
                    document.getElementsByClassName('data-box__text--photos');
                if (inputLength[0].value.length === 0) {
                    const butt =
                        document.getElementsByClassName('data-box__go-button')[0];
                    butt.disabled = false;
                }
            }
            break;
    }
};

var cancelPid = () => {
    'use strict';
    const pid = document.getElementsByClassName('search__pid');
    pid[0].value = '';
    document.getElementById('search__radio-uniq').checked = true;
};

var cancelKeywords = () => {
    'use strict';
    const kw = document.getElementsByClassName('search__key-words');
    kw[0].value = '';
    document.getElementById('search__year-start').value = 'debut';
    document.getElementById('search__year-end').value = 'fin';
    document.getElementById('search__radio-partial').checked = true;
    document.getElementById('search__keys').checked = true;
    document.getElementById('search__titles').checked = true;
    document.getElementById('search__comments').checked = true;
};

/*********************************/

var animatePhotos = () => {
    'use strict';
    const imgs = document.querySelectorAll('.photos__imgs img');
    const backward = document.getElementsByClassName('photos__previous')[0];
    const forward = document.getElementsByClassName('photos__next')[0];

    imgs.forEach(img => img.addEventListener('click', imgModal));
    backward.addEventListener('click', prevImage);
    forward.addEventListener('click', nextImage);
};

/*** MODAL ***/
var transformImage = (e) => {
    'use strict';
    const imgs = document.querySelectorAll('.photos__imgs img');
    const prev = e.target.src;
    for (let i = 0; i < imgs.length; i++) {
        if (prev === imgs[i].src) {
            imgs[i].style.transition = 'all,1s';
            imgs[i].style.transform = 'scale(2,2)';
            break;
        }
    }
    imgModal(e);
};

var imgModal = (e) => {
    'use strict';
    const imgs = document.querySelectorAll('.photos__imgs img');
    const geneolCont = document.getElementsByClassName('photos__geneol')[0];
    const photoIdCont = document.getElementsByClassName('photos__photo-id')[0];
    inFoldersState.setState(false);
    const currWin = window.location.href;
    const n = currWin.lastIndexOf('/');
    const winResult = currWin.substring(n + 1);
    const bdy = document.getElementById('bdy');

    bdy.style.overflow = 'hidden';
    var modal = document.getElementsByClassName('photos__modal')[0];

    const prev = e.target.src;

    for (var i = 0; i < imgs.length; i++) {
        if (prev === imgs[i].src) {
            break;
        }
    }
    modalCurrentIdx.setIndex(i);

    const shift = listPhotosDownload.getShiftKey();
    if (listPhotosDownload.getShiftKey()) {
        prepareDownload(e);
        listPhotosDownload.setShiftKey(false);
    } else {
        const currentIdx = modalCurrentIdx.getIndex();
        const img = prev.replace('preview', 'full');

        const modalTitle = document.getElementsByClassName('photos__modal-title')[0];
        const modalImg = document.getElementById('img01');
        const captionText = document.getElementsByClassName('photos__caption')[0];
        modal.style.display = 'block';
        modalTitle.innerHTML = imgs[currentIdx].title;
        modalImg.src = img;
        captionText.innerHTML = imgs[currentIdx].alt;
        const listPhotos = selectedPhotos.getPhotos();
        const idxList = listPhotos[currentIdx].geneolidx.split(',');
        const listGeneologyNames = listPhotos[currentIdx].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, listGeneologyNames);
        photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx].idpho +
            ")</p>";

// Get the <span> element that closes the modal
        const span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
        span.onclick = () => {
            modal.style.display = 'none';
            bdy.style.overflow = 'visible';
            inFoldersState.setState(true);
        };
    }
};

var prepareDownload = (e) => {
    'use strict';
    const redPrev =
        document.getElementsByClassName(e.target.classList.value)
            [modalCurrentIdx.getIndex()];
    const listPhotos = selectedPhotos.getPhotos();
    const pid = listPhotos[modalCurrentIdx.getIndex()].idpho;
    const list = listPhotosDownload.getList();
    const isPidIdxInList = listPhotosDownload.getList().indexOf(pid);
    if (isPidIdxInList === -1) {
        listPhotosDownload.addphoto(pid);
        redPrev.style.border = '2px solid #DD1C1A';
        document.getElementsByClassName('photos__previous-folder')[0].style.display = 'none';
        document.getElementsByClassName('photos__next-folder')[0].style.display = 'none';
        if (user.getPerm() === '1') {
            document.getElementsByClassName('photos__delete-photos')[0].style.display = 'block';
            document.getElementsByClassName('photos__download-photos')[0].style.display = 'block';
        } else {
            document.getElementsByClassName('photos__download-photos')[0].setAttribute('style', 'display:block;left:300px');
        }
    } else {
        listPhotosDownload.removePhoto(isPidIdxInList);
        redPrev.style.border = '2px solid  #fdfaf6';
        if (isPidIdxInList === 0) {
            if (!searchChoice.getSearchPageStatus()) {
                document.getElementsByClassName('photos__previous-folder')[0].style.display = 'block';
                document.getElementsByClassName('photos__next-folder')[0].style.display = 'block';
            }
            if (user.getPerm() === '1') {
                document.getElementsByClassName('photos__delete-photos')[0].style.display =
                    'none';
            }
            document.getElementsByClassName('photos__download-photos')[0].style.display = 'none';
        }
    }
};

var postDownload = () => {
    'use strict';
    const listPid = listPhotosDownload.getList();
    const listPhotos = selectedPhotos.getPhotos();
    const imgs = document.querySelectorAll('.photos__imgs img');

    for (let i = 0; i < imgs.length; i++) {
        imgs[i].style.border = '2px solid  #fdfaf6';
    }

    while (listPid.length !== 0) {
        listPhotosDownload.initPid();
    }

    document.getElementsByClassName('photos__previous-folder')[0].style.display =
        'block';
    document.getElementsByClassName('photos__next-folder')[0].style.display =
        'block';
    if (user.getPerm() === '1') {
        document.getElementsByClassName('photos__delete-photos')[0].style.display =
            'none';
    }
    document.getElementsByClassName('photos__download-photos')[0].style.display =
        'none';
    const nextFolder =
        document.getElementsByClassName('photos__next-folder')[0];
    nextFolder.style.display = 'none';
    const previousFolder =
        document.getElementsByClassName('photos__previous-folder')[0];
    previousFolder.style.display = 'none';
};

var deletePhotos = () => {
    'use strict';
    var path = folderTree.getFolderId();
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../private/php/PhotosController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;' +
        ' charset=UTF-8');
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const listPid = listPhotosDownload.getList();
            while (listPid.length !== 0) {
                listPhotosDownload.initPid();
            }
            getPhotos(path, 2);
        }
    };
    let jsonList = JSON.stringify(listPhotosDownload.getList());
    xhr.send('pids=' + jsonList + '&function=deletePhotos');
};

var prevImage = () => {
    'use strict';
    const imgs = document.querySelectorAll('.photos__imgs img');
    const photoIdCont = document.getElementsByClassName('photos__photo-id')[0];
    const geneolCont = document.getElementsByClassName('photos__geneol')[0];
    const modalImg = document.getElementById('img01');
    const modalTitle = document.getElementsByClassName('photos__modal-title')[0];
    const captionText = document.getElementsByClassName('photos__caption')[0];
    const currentIdx = modalCurrentIdx.getIndex();
    if (currentIdx > 0) {
        var img = imgs[currentIdx - 1].src;
        img = img.replace('preview', 'full');
        const capt = imgs[currentIdx - 1].alt;
        const titl = imgs[currentIdx - 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        const listPhotos = selectedPhotos.getPhotos();
        const idxList = listPhotos[currentIdx - 1].geneolidx.split(',');
        const listGeneologyNames = listPhotos[currentIdx - 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, listGeneologyNames);
        photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx - 1].idpho +
            ")</p>";
        modalCurrentIdx.subtractOne();
    }
};

var nextImage = () => {
    'use strict';
    const imgs = document.querySelectorAll('.photos__imgs img');
    const photoIdCont = document.getElementsByClassName('photos__photo-id')[0];
    const geneolCont = document.getElementsByClassName('photos__geneol')[0];
    const modalImg = document.getElementById('img01');
    const modalTitle = document.getElementsByClassName('photos__modal-title')[0];
    const captionText = document.getElementsByClassName('photos__caption')[0];
    const currentIdx = modalCurrentIdx.getIndex();
    var maxLength = imgs.length;

    if (currentIdx < maxLength - 1) {
        var img = imgs[currentIdx + 1].src;
        img = img.replace('preview', 'full');
        const capt = imgs[currentIdx + 1].alt;
        const titl = imgs[currentIdx + 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        const listPhotos = selectedPhotos.getPhotos();
        const idxList = listPhotos[currentIdx + 1].geneolidx.split(',');
        const listGeneologyNames = listPhotos[currentIdx + 1].geneolnames.split(',');

        geneolCont.innerHTML = buildGeneolLine(idxList, listGeneologyNames);
        photoIdCont.innerHTML = "<p>(pid-" + listPhotos[currentIdx + 1].idpho +
            ")</p>";
        modalCurrentIdx.addOne();
    }
};

var buildGeneolLine = (idxList, listGeneologyNames) => {
    'use strict';
    var htmlLine = '';
    if (idxList !== '') {
        htmlLine = '<p>Généalogie: ';
        for (var i = 0; i < idxList.length; i++) {
            htmlLine = htmlLine + "<a href='../legacy/desilets/asc_tree/" +
                idxList[i] + ".html' target='_blank'>" +
                listGeneologyNames[i];
            if (i + 1 < idxList.length) {
                htmlLine = htmlLine + ",&nbsp </a>";
            } else {
                htmlLine = htmlLine + "</a></p>";
            }
        }
    }
    return htmlLine;
};

/*** END MODAL ***/

var editPhoto = () => {
    'use strict';
    const currentIdx = modalCurrentIdx.getIndex();
    const listPhotos = selectedPhotos.getPhotos();
    localStorage.setItem("photoInfoList", JSON.stringify(listPhotos));
    const newWindow = window.open('photoInfo.php?pid=' + listPhotos[currentIdx].idpho +
        '&currIdx=' + currentIdx);
    // location.reload();
    newWindow.onbeforeunload = function () {
        // processing event here
        const bdy = document.getElementById('bdy');
        var modal = document.getElementsByClassName('photos__modal')[0];

        modal.style.display = 'none';
        bdy.style.overflow = 'visible';
        inFoldersState.setState(true);

        var path = folderTree.getFolderId();
        getPhotos(path, 2);
    };
};

var rotatePhotoNegative = () => {
    'use strict';
    const infoList = photoInfoList.getPhotoInfoList();
    const thumb = infoList[selectedPhotoIdx.currentIdx()].prev_path +
        infoList[selectedPhotoIdx.currentIdx()].filename;
    const full = infoList[selectedPhotoIdx.currentIdx()].path +
        infoList[selectedPhotoIdx.currentIdx()].filename;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/rotatePhoto.php?thumb=' + thumb +
        '&full=' + full + '&direction=90', true);
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            // document.getElementById("data-box__photo").reload();
            // var content = container.innerHTML;
            window.location.reload(true);
        }
    };
    xhr.send();
};

var rotatePhotoPositive = () => {
    'use strict';
    const infoList = photoInfoList.getPhotoInfoList();
    const thumb = infoList[selectedPhotoIdx.currentIdx()].prev_path +
        infoList[selectedPhotoIdx.currentIdx()].filename;
    const full = infoList[selectedPhotoIdx.currentIdx()].path +
        infoList[selectedPhotoIdx.currentIdx()].filename;

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/rotatePhoto.php?thumb=' + thumb +
        '&full=' + full + '&direction=-90', true);
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            // document.getElementById("data-box__photo").reload();
            // var content = container.innerHTML;
            window.location.reload(true);
        }
    };
    xhr.send();
};

var imgClick = (e) => {
    'use strict';
    const current = document.querySelector('#current');
    // Reset opacity
    const opacity = 0.5;
    const imgs = document.querySelectorAll('.photos__imgs img');
    imgs.forEach(img => (img.style.opacity = 1));

    // Change current image to src of clicked image
    const prev = e.target.src;
    current.src = prev.replace('preview', 'full');

    //Add fade0in class
    current.classList.add('fade-in');

    //Remove fade-in class after .5 sec
    setTimeout(() => current.classList.remove('fade-in'), 500);

    //Change the opacity to opacity variable
    e.target.style.opacity = opacity;
};

/*** Reading section ***/

var assignReadingTitle = () => {
    'use strict';
    const menu = document.getElementsByClassName('menu1__item');
    const menu0 = menu[0];
    const menu1 = menu[1];
    menu0.innerHTML = "Lectures des Normandeau-Desilets";
    menu1.innerHTML = "Vers les <span style='font-weight:bold;'>" +
        "Bernard-Normandeau</span>";
};

var getReadings = () => {
    'use strict';
    const menu = document.getElementsByClassName('menu1__item');
    const menu0 = menu[0];
    const menu1 = menu[1];
    const n = menu1.innerText.search('Bernard-Normandeau');
    var path;

    if (n !== -1) {
        path = 11;
        menu0.innerHTML = 'Lectures des Bernard-Normandeau';
        menu1.innerHTML = "Vers les <span style='font-weight:bold;'</span>" +
            "Normandeau-Desilets";
    } else {
        path = 10;
        assignReadingTitle();
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/controllers/ReadingsController.php?path=' + path, true);
    xhr.onload = () => {
        console.log(xhr.responseText);
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        const listPhotos = selectedPhotos.getPhotos();
        renderReadings(listPhotos);
    };
    xhr.send();
};

var renderReadings = (data) => {
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

        htmlString += "<div class=\"clearfix\">" + "<a href=\"" + obj.address +
            "\" target=\"_blank\">" + "<img src=\"../../" +
            obj.file + "\" alt=\"\" class=\"readings__books\">" +
            "<p class=\"readings__title\">" + obj.title + "</p></a>" +
            "<p class=\"readings__summary\">" + intro + "</p>" +
            "<p class=\"readings__summary\">" + obj.sumary + "</p ><br></div>";
    }
    readingsContainer.insertAdjacentHTML('beforeend', htmlString);
};

/*** OBJECT SECTION
 ******************/

var getObjects = () => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/Controllers/ObjectsController.php?path=' + 12, true);
    xhr.onload = () => {
        selectedPhotos.setPhotos(JSON.parse(xhr.responseText));
        const listPhotos = selectedPhotos.getPhotos();
        renderObjects(listPhotos);
    };
    xhr.send();
};

var renderObjects = (data) => {
    'use strict';
    const container = document.getElementsByClassName('objects__container');
    const objectsContainer = container[0];
    var htmlString = "";

    for (const obj of data) {
        htmlString += "<div class=\"clearfix\">\n" +
            "<img src=\"../../" + obj.file +
            "\" alt=\"\" class=\"objects__img\" " +
            "title='Cliquer pour agrandir la photo'>\n" +
            "<p class=\"objects__description\" >" +
            obj.description + "\n</p >\n<br>\n</div>\n";
    }
    objectsContainer.insertAdjacentHTML('beforeend', htmlString);
    animateObjects();
};

var animateObjects = () => {
    'use strict';
    const objs = document.querySelectorAll('.objects');
    const opacity = 0.5;
    objs.forEach(obj => obj.addEventListener('click', objModal));
};

var objModal = (e) => {
    'use strict';
    const bdy = document.getElementById('bdy');
    var modal = document.getElementsByClassName('objects__modal')[0];

    const prev = e.target.src;
    const obj = prev.replace('preview', 'full');

    const modalObj = document.getElementsByClassName('objects__modal-content')[0];
    modal.style.display = 'block';
    modalObj.src = obj;

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
    span.onclick = () => {
        modal.style.display = 'none';
        bdy.style.overflow = 'visible';
    };
};

/*** ADD folder
 ********************************/
var getFolderInputs = () => {
    'use strict';
    const folderDoc = document.getElementsByClassName('data-box__input');
    var folderData = [];
    folderData.level0 = folderDoc[0].value;
    folderData.level1 = folderDoc[1].value;
    folderData.level2 = folderDoc[2].value;
    folderData.level3 = folderDoc[3].value;
    /* folderData.year = folderDoc[4].value;
     folderData.title = folderDoc[5].value;*/
    return folderData;
};

var GetFolderValues = (folders) => {
    'use strict';
    folders.idLevel0 = memberInfo.GetMemberId();
    folders.member = memberInfo.GetMemberName();
    folders.idLevel1 = FolderFirstLevel.searchName(folders.level1);
    folders.idLevel2 = FolderSecondLevel.searchName(folders.level2);
    return folders;
};

var getFolderInputsPhotos = () => {
    'use strict';
    const folderDoc = document.getElementsByClassName('data-box__select');
    var folderData = [];
    folderData.type = folderDoc[0].value;
    folderData.member = folderDoc[1].value;
    folderData.decade = folderDoc[2].value;
    folderData.year = folderDoc[3].value;
    folderData.title = folderDoc[4].value;
    return folderData;
};

var addFolder = () => {
    'use strict';
    var folderData = getFolderInputs();
    folderData = GetFolderValues(folderData);
    const xhr = new XMLHttpRequest();
    /*    xhr.open('GET', '../../private/php/FoldersController.php?type=' +
            folderData.type + '&member=' + folderData.member + '&decade=' +
            folderData.decade + '&year=' + folderData.year +
            '&title=' + folderData.title + '&levels=' + folderData.levels +
            '&function=addFolder', true);*/
    xhr.open('GET', '../../private/php/FoldersController.php' +
        '?level0Id=' + folderData.idLevel0 +
        '&level0Name=' + folderData.level0 +
        '&level1Id=' + folderData.idLevel1 +
        '&level1Name=' + folderData.level1 +
        '&level2Id=' + folderData.idLevel2 +
        '&level2Name=' + folderData.level2 +
        '&level3Name=' + folderData.level3 +
        '&function=addFolder', true);
    xhr.onload = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                /*                const xhr1 = new XMLHttpRequest();
                                /!*                xhr1.open('GET', '../../private/php/FoldersController.php?type=' +
                                                    folderData.type + '&member=' + folderData.member +
                                                    '&decade=' + folderData.decade + '&year=' + folderData.year +
                                                    '&title=' + folderData.title + '&levels=' +
                                                    folderData.levels + '&function=addFolderMysql', true);*!/
                                /!*                xhr1.open('GET', '../../private/php/FoldersController.php +' +
                                                    '?level0Id=' + folderData.idLevel0 +
                                                    '&level1Id=' + folderData.idLevel1 +
                                                    '&level1Name=' + folderData.level1 +
                                                    '&level2Id=' + folderData.idLevel2 +
                                                    '&level2Name=' + folderData.level2 +
                                                    '&level3Name=' + folderData.level3 +
                                                    '&function=addFolderMysql', true);*!/
                                xhr1.onload = () => {
                                    if (xhr1.readyState === 4) {
                                        if (xhr1.status === 200) {
                                            document.getElementsByClassName('data-box__message')[0].style.display = 'block';
                                            // TODO: Rediriger l usater vers l ajout de photos
                                            //     - dans le bon repertoire
                                        }
                                    }
                                };
                                xhr1.send();*/
            }
        }
    };
    xhr.send();
};

var uploadPhotos = () => {
    'use strict';
    const folderData = getFolderInputsPhotos();

    const url = '../../private/php/upload.php';
    const files = document.getElementById('data-box__input--photos').files;
    const formData = new FormData();

    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            formData.append('files[]', file);
        }

        formData.append('type', folderData.type);
        formData.append('member', folderData.member);
        formData.append('decade', folderData.decade);
        formData.append('year', folderData.year);
        formData.append('title', folderData.title);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.status >= 200 && response.status < 300) {
                const resp = response.text();
                document.getElementsByClassName('data-box__upload-done')[0].style.display = 'block';
            }
        });
    } else {
        document.getElementsByClassName('data-box__message')[0].style.display =
            'block';
    }
};

var getDecades = () => {
    'use strict';
    const req = new XMLHttpRequest();
    req.open('GET', '../../private/php/DecadesController.php?function=getDecades', true);
    req.onload = () => {
        const decadesData = JSON.parse(req.responseText);
        renderDecades(decadesData);
        // getYearsSelected();
        disableSubmitButton();
    };
    req.send();
};

var FirstLevelOnChange = () => {
    'use strict';
    const listIdx = document.getElementsByClassName('data-box__select--level1');
    const selectIndex = listIdx[0].selectedIndex;
    const inputLevel1 =
        document.getElementsByClassName('data-box__input--level1');
    inputLevel1[0].value = FolderFirstLevel.getName(selectIndex);
    const FirstLevelId = FolderFirstLevel.getId(selectIndex);
    GetFolderLevel2(FirstLevelId);
};

var SecondLevelOnChange = () => {
    'use strict';
    const listIdx = document.getElementsByClassName('data-box__select--level2');
    const selectIndex = listIdx[0].selectedIndex;
    const inputLevel2 =
        document.getElementsByClassName('data-box__input--level2');
    inputLevel2[0].value = FolderSecondLevel.getName(selectIndex);
    // const FirstLevelId = FolderFirstLevel.getId(selectIndex);
    // GetFolderLevel2(FirstLevelId);
};

var GetFolderLevel0 = (member) => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/UsersController.php?function=getMainFolder' +
        '&member=' + member, true);
    xhr.onload = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const FolderLevel0 = JSON.parse(xhr.responseText);
                renderFolderLevel0(FolderLevel0);
                GetFolderLevel1(FolderLevel0[0].idmem);
            }
        }
    };
    xhr.send();
};

var GetFolderLevel1 = (idmem) => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/FoldersController.php?function=' +
        'GetFoldersLevel1&idmem=' + idmem, true);
    xhr.onload = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                const level1 = JSON.parse(xhr.responseText);
                if (level1.length !== 0) {
                    RenderFolderLevel1(level1);
                    GetFolderLevel2(level1[0].id);
                }
            }
        }
    };
    xhr.send();
};

var GetFolderLevel2 = (idFo1) => {
    'use strict';
    const url = currentWindow();
    /*    var lev1 =
            document.getElementsByClassName('data-box__id-value1');
        var idParent = lev1[0].value;*/
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/FoldersController.php?idParent=' + idFo1 +
        '&function=GetFoldersLevel2', true);
    xhr.onload = () => {
        const level2 = JSON.parse(xhr.responseText);
        if (level2.length !== 0) {
            RenderFolderLevel2(level2);
            GetFolderLevel3(level2[0].id);
        }
    };
    xhr.send();
};

var GetFolderLevel3 = (idFo2) => {
    'use strict';
    const url = currentWindow();
    /*    var lev2 =
            document.getElementsByClassName('data-box__id-value2');
        var idParent = lev2[0].value;*/
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/FoldersController.php?idParent=' + idFo2 +
        '&function=GetFoldersLevel3', true);
    xhr.onload = () => {
        const level3 = JSON.parse(xhr.responseText);
        if (level3.length !== 0) {
            RenderFolderLevel3(level3);
        }
    };
    xhr.send();
};

var renderFolderLevel0 = (folderLevel0) => {
    'use strict';
    var htmlString = "";
    var inputAnchor;
    var inputIdAnchor;
    var curr = currentWindow();
    if (curr === 'addFolder.php') {
        inputAnchor =
            document.getElementsByClassName('data-box__input--level0')[0];
        inputAnchor.value = folderLevel0[0].firstName;
        memberInfo.setMemberId(folderLevel0[0].idmem);
        memberInfo.setMemberName(folderLevel0[0].firstName);
    } else if (curr === 'addPhotos.php') {
        inputAnchor =
            document.getElementById('data-box__select--add-ph-member');
    }
};

var RenderFolderLevel1 = (level1) => {
    'use strict';
    var htmlString = "";
    var inputAnchor;
    var selectAnchor;
    var idAnchor;
    var curr = currentWindow();
    if (curr === 'addFolder.php') {
        inputAnchor =
            document.getElementsByClassName('data-box__input--level1')[0];
        inputAnchor.value = level1[0].name;
        selectAnchor = document.getElementsByClassName(' data-box__select--level1')[0];
    } else if (curr === 'addPhotos.php') {
        inputAnchor =
            document.getElementById('data-box__select--add-ph-member');
    }
    var i = 0;
    for (const obj of level1) {
        if (htmlString.length === 0) {
            htmlString = '<option >' + obj.name +
                '</option>\n';
        } else {
            htmlString += '<option value=>' + obj.name +
                '</option>\n';
        }
        FolderFirstLevel.setId(i, obj.id);
        FolderFirstLevel.setIdParent(i, obj.idParent);
        FolderFirstLevel.setName(i, obj.name);
        i += 1;
    }
    selectAnchor.insertAdjacentHTML("beforeend", htmlString);
};

var RenderFolderLevel2 = (level2) => {
    'use strict';
    var htmlString = "";
    var inputAnchor;
    var selectAnchor;
    var idAnchor;
    var curr = currentWindow();
    if (curr === 'addFolder.php') {
        inputAnchor =
            document.getElementsByClassName('data-box__input--level2')[0];
        inputAnchor.value = level2[0].name;
        selectAnchor = document.getElementsByClassName(' data-box__select--level2')[0];
    } else if (curr === 'addPhotos.php') {
        inputAnchor =
            document.getElementById('data-box__select--add-ph-member');
    }

    var i = 0;
    FolderSecondLevel.init();
    selectAnchor.innerHTML = "";
    for (const obj of level2) {
        if (htmlString.length === 0) {
            htmlString = '<option >' + obj.name +
                '</option>\n';
        } else {
            htmlString += '<option value=>' + obj.name +
                '</option>\n';
        }
        FolderSecondLevel.setId(i, obj.id);
        FolderSecondLevel.setIdParent(i, obj.idParent);
        FolderSecondLevel.setName(i, obj.name);
        i += 1;
    }
    selectAnchor.insertAdjacentHTML("beforeend", htmlString);
};

var RenderFolderLevel3 = (level3) => {
    'use strict';
    var htmlString = "";
    var inputAnchor;
    var selectAnchor;
    var idAnchor;
    var curr = currentWindow();
    if (curr === 'addFolder.php') {
        /*        inputAnchor =
                    document.getElementsByClassName('data-box__input--level3')[0];
                inputAnchor.value = level3[0].name;
                selectAnchor = document.getElementsByClassName(' data-box__select--level3')[0];
                idAnchor=document.getElementsByClassName('data-box__id-value3')[0];
                idAnchor.value = level3[0].id;*/
    } else if (curr === 'addPhotos.php') {
        inputAnchor =
            document.getElementById('data-box__select--add-ph-member');
    }

    var i = 0;
    for (const obj of level3) {
        if (htmlString.length === 0) {
            htmlString = '<option >' + obj.name +
                '</option>\n';
        } else {
            htmlString += '<option value=>' + obj.name +
                '</option>\n';
        }
        FolderThirdLevel.setId(i, obj.id);
        FolderThirdLevel.setIdParent(i, obj.idParent);
        FolderThirdLevel.setName(i, obj.name);
        i += 1;
    }
    if (curr === 'addPhotos.php') {
        inputAnchor.insertAdjacentHTML("beforeend", htmlString);
    }
};

var renderDecades = (decades) => {
    'use strict';
    const decadeContainer =
        document.getElementsByClassName('data-box__select--add-folder-photo-decade')[0];
    var htmlString = "";

    for (const obj of decades) {
        htmlString += "<option value=\"" + obj.idDecade + "\">" + obj.decade +
            "</option>\n";
    }
    // decadeContainer.insertAdjacentHTML("beforeend", htmlString);
};

var renderYears = (data) => {
    'use strict';
    const yearContainer =
        document.getElementsByClassName('data-box__select--add-folder-photo-year');
    yearContainer[0].innerHTML = '';
    var htmlString = "";

    for (const obj of data) {
        htmlString += "<option value=\"" + obj.idxYear + "\">" + obj.year +
            "</option>\n";
    }
    htmlString += "<option value=\"1\">NA</option>";
    yearContainer[0].insertAdjacentHTML('beforeend', htmlString);
};

var getFolders = (fisrtYear) => {
    'use strict';
    var year;
    if (fisrtYear === undefined) {
        const y =
            document.getElementsByClassName('data-box__select--add-folder-photo-year');
        year = y[0].value;
    } else {
        year = fisrtYear;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/FoldersController.php?year=' + year +
        '&function=getFolders', true);
    xhr.onload = () => {
        console.log(xhr.responseText);
        const folderData = JSON.parse(xhr.responseText);
        renderFolders(folderData);
    };

    xhr.send();
};

var renderFolders = (folderData) => {
    'use strict';
    var folderContainer =
        document.getElementsByClassName('data-box__select--add-ph-title');
    var htmlString = "";
    folderContainer[0].innerHTML = '';

    for (const obj of folderData) {
        htmlString += "<option value=\"" + obj.folder + "\">" + obj.title +
            "</option>";
    }
    folderContainer[0].insertAdjacentHTML('beforeend', htmlString);
};

var renderSelectedPhotos = () => {
    'use strict';
    const butt = document.getElementsByClassName('data-box__go-button')[0];
    butt.disabled = false;
    document.getElementsByClassName('data-box__upload-done')[0].style.display = 'none';
    document.getElementsByClassName('data-box__message')[0].style.display = 'none';
    document.getElementById('data__box--text-input').value =
        createFileList(document.getElementById('data-box__input--photos').files);
};

var createFileList = (files) => {
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
};

var closeWindow = () => {
    'use strict';
    window.close(window.location.href);
};

var insertPhotoInfo = () => {
    'use strict';
    var req = new XMLHttpRequest();
    var inputs = getPhotoInfoInputs();

    req.open('POST', '../../private/php/controllers/PhotosController.php?photoId=' +
        inputs.photoId + '&title=' + inputs.title + '&keyWords=' +
        inputs.keyWords + '&caption=' + inputs.caption + '&year=' + inputs.year +
        '&geneologyIdxs=' + inputs.geneologyIdxs +
        '&function=insertPhotoInfo', true);

    req.onload = () => {
        if (req.readyState === 4) {
            if (req.status === 200) {
                const folderData = req.responseText;
                let success = true;
            }
        }
    };
    req.send();
};

var getPhotoInfoInputs = () => {
    'use strict';
    var infoInputs = [];
    infoInputs.photoId =
        document.getElementsByClassName('data-box__photo-id')[0].value;
    infoInputs.title =
        document.getElementsByClassName('data-box__input--info-title')[0].value;
    infoInputs.keyWords =
        document.getElementsByClassName('data-box__input--info-keywords')[0].value;
    infoInputs.caption =
        document.getElementsByClassName('data-box__input--info-caption')[0].value;
    infoInputs.year =
        document.getElementsByClassName('data-box__input--info-year')[0].value;
    infoInputs.geneologyIdxs =
        document.getElementsByClassName('data-box__input--info-geneol')[0].value;
    infoInputs.geneologyIdxs =
        validatePhotoInfoIndexes(infoInputs.geneologyIdxs);
    return infoInputs;
};

var validatePhotoInfoIndexes = (listOfIndexes) => {
    'use strict';
    const listIdx = listOfIndexes.split(/\s,\s*/);
    var indexes = '';
    var index = '';
    var names = '';
    const lisOfNames = listGeneologyNames.getNames();
    for (var i = 0; i < listIdx.length; i++) {
        for (const obj of lisOfNames) {
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
};

var getGeneologyList = () => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../../private/php/GeneologyController.php?function=getGeneologyList', true);
    // xhr.responseType = 'JSON';
    xhr.onload = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const jsGeneolList = JSON.parse(xhr.responseText);
                renderGeneologyList(jsGeneolList);
                geneologyList.setState(true);
            }
        }
    };
    xhr.send();
};

var renderGeneologyList = (rawData) => {
    'use strict';
    var listContainer =
        document.getElementsByClassName('data-box__geneol-list')[0];
    var optGroup = '';
    var htmlString = "";
    var names = [];
    var listOfNames = [];

    htmlString =
        '<select onchange="addGeneolNames()" id=\"data-box__geneol-list\" ' +
        '        class=\"data-box__ select data-box__select--geneol\" >\n' +
        '<option selected value=\"choice\">Faites un choix\n' + '</option>\n';

    for (const obj of rawData) {
        htmlString += '<option value=\"' + obj.idgen + '\">' + obj.name +
            '</option>\n';
        const names = {'idx': obj.idgen, 'name': obj.name};
        listOfNames.push(names);
    }

    htmlString += '</select><br>';

    listContainer.insertAdjacentHTML('beforeend', htmlString);
    listGeneologyNames.setNames(listOfNames);
};

var addGeneolNames = () => {
    'use strict';
    let geneolList =
        document.getElementsByClassName('data-box__input--info-geneol');
    var names = geneolList[0].value;
    var selectGeneol =
        document.getElementsByClassName('select data-box__select--geneol')[0].value;
    var name = '';
    const listOfNames = listGeneologyNames.getNames();

    for (const obj of listOfNames) {
        if (selectGeneol === obj.idx.toString()) {
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
};

var currentWindow = () => {
    'use strict';
    const currWin = window.location.href.match(/^[^\#\?]+/)[0];
    const n = currWin.lastIndexOf('/');
    return currWin.substring(n + 1);
};

var getShiftingFolders = () => {
    'use strict';
    const xhr = new XMLHttpRequest();

    xhr.open('GET', '../../private/php/FoldersController.php?function=getShiftingFolders');
    xhr.onload = () => {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                listShiftingFolders.setShiftingFolders(JSON.parse(xhr.responseText));
            }
        }
    };
    xhr.send();
};

var showNextFolder = () => {
    'use strict';
    document.getElementsByClassName('photos__previous-folder')[0].disabled = true;
    document.getElementsByClassName('photos__next-folder')[0].disabled = true;
    const folders = listShiftingFolders.getShiftingFolders();
    if (currentShiftingFolder.currentIdx() < folders.length) {
        const idx = currentShiftingFolder.addOne();
        folderTitle.setTitle(folders[idx].title);
        const path = folders[idx].folder;
        getPhotos(path, 2);
    }
};

var showPreviousFolder = () => {
    'use strict';
    document.getElementsByClassName('photos__previous-folder')[0].disabled = true;
    document.getElementsByClassName('photos__next-folder')[0].disabled = true;
    const folders = listShiftingFolders.getShiftingFolders();
    if (currentShiftingFolder.currentIdx() >= 0) {
        const idx = currentShiftingFolder.subtractOne();
        const path = folders[idx].folder;
        folderTitle.setTitle(folders[idx].title);
        getPhotos(path, 2);
    }
};

var disableSubmitButton = () => {
    'use strict';
    const currWin = currentWindow();
    if (currWin === 'addFolder.html;') {
        const butt = document.getElementsByClassName('data-box__go-button')[0];
        butt.disabled = true;
    }
};

var downloadPhotos = () => {
    'use strict';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../private/php/controllers/PhotosController.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;' +
        ' charset=UTF-8');
    xhr.responseType = 'blob';
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var disposition = xhr.getResponseHeader('content-disposition');
            var matches = /"([^"]*)"/.exec(disposition);
            var filename =
                (matches !== null &&
                matches[1] ? matches[1] : 'lesnormandeaudesilets.zip');
            var blob = new Blob([xhr.response], {type: 'application/zip'});
            var link = document.createElement('a');

            link.href = window.URL.createObjectURL(blob);
            link.download = filename;

            document.body.appendChild(link);

            link.click();

            postDownload();

            const xhr1 = new XMLHttpRequest();
            xhr1.open('GET', '../../private/php/PhotosController.php?function=' +
                'removeZipFile', true);
            xhr1.send();
        }
    };
    let jsonList = JSON.stringify(listPhotosDownload.getList());
    xhr.send('pids=' + jsonList + '&function=zipAndDownload');
};

var loginForm = () => {
    'use strict';
    document.getElementsByClassName('login__login-form')[0].style.display =
        'block';
    document.getElementsByClassName('login__register-form')[0].style.display =
        'none';
};

var registerForm = () => {
    'use strict';
    document.getElementsByClassName('login__login-form')[0].style.display =
        'none';
    document.getElementsByClassName('login__register-form')[0].style.display =
        'block';
};

var logedIn = () => {
    'use strict';
    document.getElementsByClassName('masthead__connect')[0].style.display =
        'none';
    document.getElementsByClassName('masthead__disconnect')[0].style.display =
        'block';
};

var loginError = () => {
    'use strict';
    alert('Erreur');
};

/*** Closures ***/
function photoInfoIdxIncrementer(pIndex) {
    'use strict';
    var _index = pIndex;

    this.add = () => {
        _index += 1;
        return _index;
    };
    this.subtract = () => {
        _index -= 1;
        return _index;
    };
    this.currentIdx = () => {
        return _index;
    };
}

function listPhotoInfo(pList) {
    'use strict';
    var _list = pList;

    this.getPhotoInfoList = () => {
        return _list;
    };
}

function listOfAllYears(pYears) {
    'use strict';
    var _years = pYears;

    this.listOfYears = () => {
        return _years;
    };
}

function searchPhotos(pPage) {
    'use strict';
    var _search = pPage;

    this.setSearchPageStatus = (status) => {
        _search = status;
    };
    this.getSearchPageStatus = () => {
        return _search;
    };
}

function directoryTitle() {
    'use strict';
    var _title = "";

    this.setTitle = (title) => {
        _title = title;
    };
    this.getTitle = () => {
        return _title;
    };
}

function photos() {
    'use strict';
    var _photos = "";

    this.setPhotos = (list) => {
        _photos = list;
    };
    this.getPhotos = () => {
        return _photos;
    };
}

function images() {
    'use strict';
    var _images = "";

    this.setImages = (img) => {
        _images = img;
    };
    this.getImages = () => {
        return _images;
    };
}

function shiftingFoldersIdx(pIdx) {
    'use strict';
    var _folderIdx = pIdx;

    this.addOne = () => {
        _folderIdx += 1;
        return _folderIdx;
    };
    this.subtractOne = () => {
        _folderIdx -= 1;
        return _folderIdx;
    };
    this.currentIdx = () => {
        return _folderIdx;
    };
}

var listShiftingFolders = {
    _shiftingFolders: "",
    setShiftingFolders: (pFolders) => _shiftingFolders = pFolders,
    getShiftingFolders: () => _shiftingFolders
};

var geneologyList = {
    _state: false,
    setState: (state) => _state = state,
    getState: () => _state
};

var inFoldersState = {
    _state: true,
    setState: (state) => _state = state,
    getState: () => _state
};

var user = {
    _hasPerm: "",
    setPerm: (hasPerm) => _hasPerm = hasPerm,
    getPerm: () => _hasPerm
};

var listGeneologyNames = {
    _names: "",
    setNames: (names) => _names = names,
    getNames: () => _names
};

function Member() {
    'use strict';
    var _memberId = 0;
    var _memberName = "";

    this.setMemberId = (id) => {
        _memberId = id;
    };
    this.setMemberName = (name) => {
        _memberName = name;
    };
    this.GetMemberId = () => {
        return _memberId;
    };
    this.GetMemberName = () => {
        return _memberName;
    };
}

function FolderLevel1() {
    'use strict';
    var _id = [];
    var _idParent = [];
    var _name = [];

    this.setId = (idx, id) => {
        _id[idx] = id;
    };
    this.setIdParent = (idx, idParent) => {
        _idParent[idx] = idParent;
    };
    this.setName = (idx, name) => {
        _name[idx] = name;
    };
    this.getId = (idx) => {
        return _id[idx];
    };
    this.getIdParent = (idx) => {
        return _idParent[idx];
    };
    this.getName = (idx) => {
        return _name[idx];
    };
    this.searchName = (name) => {
        for (var i = 0; i < _name.length; i++) {
            if (name === _name[i]) {
                return _id[i];
            }
        }
        return 0;
    };
}

function FolderLevel2() {
    'use strict';
    var _id = [];
    var _idParent = [];
    var _name = [];

    this.setId = (idx, id) => {
        _id[idx] = id;
    };
    this.setIdParent = (idx, idParent) => {
        _idParent[idx] = idParent;
    };
    this.setName = (idx, name) => {
        _name[idx] = name;
    };
    this.init = () => {
        for (var i = _id.length; i > 0; i--) {
            _id.pop();
            _idParent.pop();
            _name.pop();
        }
    };
    this.getId = (idx) => {
        return _id[idx];
    };
    this.getIdParent = (idx) => {
        return _idParent[idx];
    };
    this.getName = (idx) => {
        return _name[idx];
    };
    this.searchName = (name) => {
        for (var i = 0; i < _name.length; i++) {
            if (name === _name[i]) {
                return _id[i];
            }
        }
        return 0;
    };
}

function FolderLevel3() {
    'use strict';
    var _id = [];
    var _idParent = [];
    var _name = [];

    this.setId = (idx, id) => {
        _id[idx] = id;
    };
    this.setIdParent = (idx, idParent) => {
        _idParent[idx] = idParent;
    };
    this.setName = (idx, name) => {
        _name[idx] = name;
    };
    this.getId = (idx) => {
        return _id[idx];
    };
    this.getIdParent = (idx) => {
        return _idParent[idx];
    };
    this.getName = (idx) => {
        return _name[idx];
    };
    this.searchName = (name) => {
        for (var i = 0; i < _name.length; i++) {
            if (name === _name[i]) {
                return _id[i];
            }
        }
        return 0;
    };
}

function folderHierarchy() {
    'use strict';
    var _author = "";
    var _decade = "";
    var _year = 0;
    var _itm = 0;
    var _folderId = 0;
    var _sbitm = 0;
    var _htmlString = '';
    var _j = -1;
    var _level = 0;

    this.setMember = function (member) {
        _author = member;
    };
    this.getMember = () => {
        return _author;
    };
    this.setDecade = function (decade) {
        _decade = decade;
    };
    this.getDecade = () => {
        return _decade;
    };
    this.setYear = function (year) {
        _year = year;
    };
    this.getYear = () => {
        return _year;
    };
    this.addItem = () => {
        _itm += 1;
    };
    this.getItem = () => {
        return _itm;
    };
    this.setFolderId = function (folderId) {
        _folderId = folderId;
    };
    this.getFolderId = () => {
        return _folderId;
    };
    this.addSubItem = () => {
        _sbitm += 1;
    };
    this.setSubItem = function (sItem) {
        _sbitm = sItem;
    };
    this.getSubItem = () => {
        return _sbitm;
    };
    this.setHtmlString = function (htmlString) {
        _htmlString = htmlString;
    };
    this.addToHtmlString = function (htmlstring) {
        _htmlString = _htmlString + htmlstring;
    };
    this.getHtmlString = () => {
        return _htmlString;
    };
    this.addJcounter = () => {
        _j += 1;
    };
    this.getJcounter = () => {
        return _j;
    };
    this.setLevel = function (level) {
        _level = level;
    };
    this.getLevel = () => {
        return _level;
    };
}

var modalCurrentIdx = {
    _index: 0,
    setIndex: (idx) => _index = idx,
    addOne: () => _index++,
    subtractOne: () => _index--,
    getIndex: () => _index
};

function photosForDownload() {
    'use strict';
    var _pid = [];
    var _shiftKey = false;

    this.setShiftKey = (shiftKey) => _shiftKey = shiftKey;
    this.getShiftKey = () => _shiftKey;
    this.addphoto = (pid) => _pid.push(pid);
    this.removePhoto = (idx) => _pid.splice(idx, 1);
    this.getList = () => _pid;
    this.initPid = () => _pid.pop();
}