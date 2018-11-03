/*** Photos section ***/

function assignArchivesTitle() {
    document.getElementById("family-left").innerHTML = "Photos d'archives des Marchand-Desilets";
    document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'>Bernard-Normandeau</span> ";
}

function getFolderTree() {
    var folderRequest = new XMLHttpRequest();
    folderRequest.open('GET', 'php/getFolderTree.php', true);
    folderRequest.onload = function () {
        var folderData = JSON.parse(folderRequest.responseText);
        buildFolderTree(folderData);
    }
    folderRequest.send();
}

var searchChoice = false;
var searchItmCont;
var folderTitle;
var myData;

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

var author = "";
var decade = "";
var year = 0;
var itm = 0;
var repoId = 0;
var sbitm = 0;
var htmlString = "";
var j = -1;
var level = 0;

function buildFolderTree(data) {
    var folderContainer = document.getElementById("photosFolders");

    for (const branch of data) {
        if (author != branch.author && branch.author.length > 0) {
            if (author != "" && level == 4) {
                htmlString = htmlString +
                    "</ul>\n" +
                    "</li>\n" +
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n";
            }
            if (author != "" && level == 2) {
                htmlString = htmlString +
                    "</ul>\n" +
                    "</div>\n" +
                    "</div>\n";
            }
            j++;

            htmlString = htmlString +
                "<input type=\"checkbox\" id=\"menu" + j + "\"/>\n" +
                "<label for=\"menu" + j + "\" class=\"names\">" + branch.author + "</label>\n" +
                "<div class=\"multi-level" + j + "\">\n";
            author = branch.author;
            decade = "";
        }

        level = branch.levels;
        switch (level) {
            case '2':
                folderLevel2(branch);
                break;
            case '4':
                folderLevel4(branch);
                break;
        }
    }

    if (level == 4) {
        htmlString = htmlString +
            "</ul>\n" +
            "</div>\n" +
            "</li>\n" +
            "</ul>\n" +
            "</div>\n" +
            "</div>\n";
    }

    folderContainer.insertAdjacentHTML('beforeend', htmlString);
}

function folderLevel2(branch) {
    if (decade != branch.decade && branch.decade.length > 0) {
        // itm++;
        // sbitm = 0;
        htmlString = htmlString +
            "<div class=\"itemL2\">\n" +
            "<ul>\n";
        // predecade = decade;
        decade = branch.decade;
        // year = 0;
    }

    if ((author == branch.author && branch.author.length > 0) &&
        (repoId != branch.repository)) {

        htmlString = htmlString +
            "<li class='photofolder L2' onclick='javascript:getFamilyPhotos(this," + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
        repoId = branch.repository;
    }
}

// TODO Add folder level 3

function folderLevel4(branch) {
    if (decade != branch.decade && branch.decade.length > 0) {
        if (decade != "") {
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
            "<div class=\"item\">\n" +
            "<input type=\"checkbox\" id=\"IT" + itm + "\"/>\n" +
            "<img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
            "<label for=\"IT" + itm + "\">" + branch.decade + "</label>\n" +
            "<ul>\n";
        predecade = decade;
        decade = branch.decade;
        year = 0;
    }

    if (year != branch.year && branch.year.length > 0) {
        if (year != 0) {
            htmlString = htmlString +
                "</ul>\n" +
                "</div>\n" +
                "</li>\n";
        }
        sbitm++;
        htmlString = htmlString +
            "<li>\n" +
            "<div class=\"sub-item\">\n" +
            "<input type=\"checkbox\" id=\"SIT" + itm + "-" + sbitm + "\"/>\n" +
            "<img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
            "<label for=\"SIT" + itm + "-" + sbitm + "\">" + branch.year + "</label>\n" +
            "<ul>\n";
        year = branch.year;
    }
    if ((author == branch.author && branch.author.length > 0) &&
        (decade == branch.decade && branch.decade.length > 0) &&
        (year == branch.year && branch.year.length > 0) &&
        (repoId != branch.repository)) {
        htmlString = htmlString +
            "<li class='photofolder' value='0' onclick='javascript:getFamilyPhotos(this," + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
        repoId = branch.repository;
    }
}

function getFamilyPhotos(obj, path, type) {
    folderTitle = obj.innerHTML;
    getPhotos(path, type);
}


function getPhotos(path, type) {
    searchChoice = false;
    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
    myRequest.onload = function () {
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
    };
    myRequest.send();
}

function searchInputs() {
    searchChoice = true;
    folderTitle = "";
    var searchFormData = [];
    searchFormData = getSearchInputs();

    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getSearchedPhotos.php?kwrd=' + searchFormData.kwords + '&startYear=' + searchFormData.startYear + '&endYear=' + searchFormData.endYear +
        '&wExact=' + searchFormData.wExact.toString() + '&wPart=' + searchFormData.wPart.toString() +
        '&searchKw=' + searchFormData.searchClefs.toString() + '&searchTitles=' + searchFormData.searchTitres.toString() + '&searchComments=' + searchFormData.searchComment.toString() + '&photoId=' + searchFormData.photoId +
        '&idUnique=' + searchFormData.idUnique.toString() + '&idContext=' + searchFormData.idContext.toString(), true);
    myRequest.onload = function () {
        myData = JSON.parse(myRequest.responseText);
        turnOffSearchFolders();
        renderFamilyPhotos();
    };
    myRequest.send();
}

function renderHomePhoto() {
    var archivesContainer = document.getElementById("homePhoto");
    var htmlString = "";
    var imageURL = "";
    var thumb = "";

    for (const obj of myData) {
        imageURL = obj.path + obj.filename;
        thumb = obj.prev_path + obj.filename;

        htmlString = "<img src=\"" + imageURL + "\" alt=\"" + obj.title + "\">";

        archivesContainer.insertAdjacentHTML('beforeend', htmlString)
    }
}

function renderFamilyPhotos() {
    document.getElementById('imgs').style.display = 'block';
    var familyContainer = document.getElementById("imgs");
    var htmlString = "";
    var imageURL = "";
    var thumb = "";
    var geneology = [];

    document.getElementById("imgs").innerHTML = "";

    for (const obj of myData) {
        imageURL = obj.path + obj.filename;
        thumb = obj.prev_path + obj.filename;

        htmlString = "<div><img src=\"" + thumb + "\" alt=\"" + obj.caption + "\" title=\"" + obj.title + "\" class='thumbimg'></div>"

        familyContainer.insertAdjacentHTML('beforeend', htmlString);
    }
    animatePhotos();
}

function closeFolders() {
    // var cFolder = document.getElementsByClassName('menu')[0].id
    var i = 0;
    var j = 0;
    var checkedValue = null;
    var inputElements = document.getElementsByClassName('menu');
    for (i = 0; inputElements[i]; ++i) {
        if (inputElements[i].checked) {
            checkedValue = inputElements[i].value;
            break;
        }
    }
    while (j < 10) {
        if (document.getElementById("checkbox").checked = true) {

        }
    }
}

function turnOffFolders() {
    var titleContainer = document.getElementById('thumbTitle');
    // Hide search and tree and bring up back to tree button
    document.getElementById('photosFolders').style.display = 'none';
    document.getElementById('searchKw').style.display = 'none';
    document.getElementById('searchFormButton').style.display = 'none';
    document.getElementById('backToTree').style.display = 'block';
    document.getElementById('thumbTitle').style.display = 'block';
    if (!searchChoice) {
        titleContainer.innerText = folderTitle;
    } else {
        titleContainer.innerText = "";
    }
    return;
}

function turnOffSearchFolders() {
    var titleContainer = document.getElementById('thumbTitle');
    // Hide search and tree and bring up back to tree button
    document.getElementById('photosFolders').style.display = 'none';
    document.getElementById('searchKw').style.display = 'none';
    document.getElementById('searchFormButton').style.display = 'none';
    document.getElementById('backToTree').style.display = 'block';
    // document.getElementById('backToSearch').style.display = 'none';
    document.getElementById('thumbTitle').style.display = 'block';
    if (document.getElementById('searchKw').elements['idContext'].checked != true) {
        titleContainer.innerText = '';
    } else {
        titleContainer.innerText = myData[0].rptTitle;
    }
    document.getElementById("backToTree").onclick = function () {
        backToSearch()
    };
    return;
}


/*** SEARCH
 **********************************/
function searchForm() {
    prepareSearchScreen();
}

function prepareSearchScreen() {
    document.getElementById('searchFormButton').style.display = 'none';
    document.getElementById('photosFolders').style.display = 'none';
    document.getElementById('backToTree').style.display = 'block';
    // document.getElementById('backToSearch').style.display = 'none';
    document.getElementById('searchKw').style.display = 'block';
    document.getElementById("backToTree").onclick = function () {
        backToTree()
    };
}

function getSearchInputs() {
    var searchData = [];
    var searchDoc = document.getElementById('searchKw');
    if (searchDoc.elements["keywrds"].value == "") {
        searchData['kwords'] = "nothingness";
    } else {
        searchData['kwords'] = searchDoc.elements["keywrds"].value;
    }
    searchData['startYear'] = searchDoc.elements['anneeDeb'].value;
    searchData['endYear'] = searchDoc.elements['anneeFin'].value;
    searchData['wExact'] = searchDoc.elements['wExact'].checked;
    searchData['wPart'] = searchDoc.elements['wPart'].checked;
    searchData['searchClefs'] = searchDoc.elements['clefs'].checked;
    searchData['searchTitres'] = searchDoc.elements['titres'].checked;
    searchData['searchComment'] = searchDoc.elements['commentaires'].checked;
    if (searchDoc.elements['pid'].value == "") {
        searchData['photoId'] = "nothing";
    } else {
        searchData['photoId'] = searchDoc.elements['pid'].value;
    }
    searchData['idUnique'] = searchDoc.elements['idUnique'].checked;
    searchData['idContext'] = searchDoc.elements['idContext'].checked;
    return searchData;
}

function backToTree() {
    // Bring back search and tree and hide 'back to tree(X)' button
    document.getElementById('imgs').style.display = 'none';
    document.getElementById('searchKw').style.display = 'none';
    document.getElementById('backToTree').style.display = 'none';
    document.getElementById('thumbTitle').style.display = 'none';
    document.getElementById('photosFolders').style.display = 'block';
    document.getElementById('searchFormButton').style.display = 'block';
}

function backToSearch() {
    // Bring back search and tree and hide 'back to tree(X)' button
    // document.getElementById('photosFolders').style.display = 'block';
    // document.getElementById('searchKw').style.display = 'block';
    document.getElementById('imgs').style.display = 'none';
    // document.getElementById('backToSearch').style.display = 'block';
    document.getElementById('backToTree').style.display = 'block';
    document.getElementById('thumbTitle').style.display = 'none';
    searchForm();
}

/* Controle of right and left keys
**********************************/
document.onkeydown = function (e) {
    switch (e.keyCode) {
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
        /*    case 40:
              alert('down');
              break;
            case 45:
              alert('down');
              break;*/
    }
}

function cancelPid() {
    document.getElementById('pid').value = "";
    document.getElementById('idUnique').checked = true;
}

function cancelKeywords() {
    document.getElementById('keywrds').value = "";
    document.getElementById('debut').value = "debut";
    document.getElementById('fin').value = "fin";
    document.getElementById('wPart').checked = true;
    document.getElementById('clefs').checked = true;
    document.getElementById('titres').checked = true;
    document.getElementById('commentaires').checked = true;
}

/*var input = document.getElementById("goButton");
input.addEventListener("keyup", function (event) {
  event.preventDefault();
  if (event.keyCode === 13) {
    getFamilyPhotos();
    // document.getElementById("myBtn").click();
  }
});*/

/*********************************/

function animatePhotos() {
    current = document.querySelector('#current');
    imgs = document.querySelectorAll('#imgs img');
    backward = document.getElementById('previous');
    forward = document.getElementById('next');
    geneolCont = document.getElementById('geneol');
    photoIdCont = document.getElementById('photoId');

    opacity = 0.5;

    imgs.forEach(img => img.addEventListener('click', imgModal));
    backward.addEventListener('click', prevImage);
    forward.addEventListener('click', nextImage);
}

/*** MODAL ***/
function transformImage(e) {
    prev = e.target.src;
    for (i = 0; i < imgs.length; i++) {
        if (prev == imgs[i].src) {
            imgs[i].style.transition = "all,1s";
            imgs[i].style.transform = "scale(2,2)";
            break;
        }
    }
    imgModal(e)
}

var modal;

function imgModal(e) {
    var htmlGeneol = "";
    var htmlPhotoId = "";
    bdy = document.getElementById('bdy');
    bdy.style.overflow = 'hidden';
    modal = document.getElementById('myModal');

    prev = e.target.src;
    maxLength = imgs.length;

    for (i = 0; i < imgs.length; i++) {
        if (prev == imgs[i].src) {
            break;
        }
    }
    currentIdx = i;

    if (currentIdx == 0) {
        backward.style.backgroundColor = 'red';
    } else {
        backward.style.backgroundColor = 'green';
    }
    if (currentIdx == maxLength - 1) {
        forward.style.backgroundColor = 'red';
    } else {
        forward.style.backgroundColor = 'green';
    }

    img = prev.replace('preview', 'full');

    modalTitle = document.getElementById('modalTitle');
    modalImg = document.getElementById("img01");
    captionText = document.getElementById("caption");
    // geneolCont = document.getElementById("geneol");
    modal.style.display = "block";
    modalTitle.innerHTML = this.title;
    modalImg.src = img;
    captionText.innerHTML = this.alt;

    var idxList = myData[currentIdx].geneolidx.split(',');
    var namesList = myData[currentIdx].geneolnames.split(',');

    htmlGeneol = buildGeneolLine(idxList, namesList);

    geneolCont.innerHTML = htmlGeneol;
    photoId.innerHTML = '<p>(pid-' + myData[currentIdx].idpho + ')</p>';

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
        bdy.style.overflow = 'visible';
    }
}

function prevImage() {
    var htmlGeneol = "";
    if (currentIdx > 0) {
        img = imgs[currentIdx - 1].src;
        img = img.replace('preview', 'full');
        capt = imgs[currentIdx - 1].alt;
        titl = imgs[currentIdx - 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        var idxList = myData[currentIdx - 1].geneolidx.split(',');
        var namesList = myData[currentIdx - 1].geneolnames.split(',');

        htmlGeneol = buildGeneolLine(idxList, namesList);

        geneolCont.innerHTML = htmlGeneol;
        photoId.innerHTML = '<p>(pid-' + myData[currentIdx - 1].idpho + ')</p>';
        currentIdx--;
        if (currentIdx == 0) {
            backward.style.backgroundColor = "red";
        }
        if (currentIdx < maxLength - 1) {
            forward.style.backgroundColor = "green";
        }
    }
}

function nextImage() {
    var htmlGeneol = "";
    if (currentIdx < maxLength - 1) {
        img = imgs[currentIdx + 1].src;
        img = img.replace('preview', 'full');
        capt = imgs[currentIdx + 1].alt;
        titl = imgs[currentIdx + 1].title;
        modalImg.src = img;
        modalTitle.innerHTML = titl;
        captionText.innerHTML = capt;

        var idxList = myData[currentIdx + 1].geneolidx.split(',');
        var namesList = myData[currentIdx + 1].geneolnames.split(',');

        htmlGeneol = buildGeneolLine(idxList, namesList);

        geneol.innerHTML = htmlGeneol;
        photoId.innerHTML = '<p>(pid-' + myData[currentIdx + 1].idpho + ')</p>';
        currentIdx++;
        if (currentIdx == maxLength - 1) {
            forward.style.backgroundColor = "red";
        }
        if (currentIdx > 0) {
            backward.style.backgroundColor = "green";
        }
    }
}

function buildGeneolLine(idxList, namesList) {
    var htmlLine = '';
    if (idxList != "") {
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
    // Reset opacity
    imgs.forEach(img => (img.style.opacity = 1));

    // Change current image to src of clicked image
    prev = e.target.src;
    var full = prev.replace('preview', 'full');
    current.src = full;

    //Add fade0in class
    current.classList.add('fade-in');

    //Remove fade-in class after .5 sec
    setTimeout(() => current.classList.remove('fade-in'), 500)

    //Change the opacity to opacity variable
    e.target.style.opacity = opacity;
}

/*** Reading section ***/

function assignReadingTitle() {
    document.getElementById("family-left").innerHTML = "Lectures des Normandeau-Desilets";
    document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'>Bernard-Normandeau</span> ";
}

function getReadings() {
    var family = document.getElementById("family-right").innerHTML;
    n = family.search("Bernard-Normandeau");
    if (n != -1) {
        path = 11
        document.getElementById("family-left").innerHTML = "Lectures des Bernard-Normandeau";
        document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'</span>Normandeau-Desilets";
    } else {
        path = 10;
        assignReadingTitle();
    }

    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getReadings.php?path=' + path, true);
    myRequest.onload = function () {
        var myData = JSON.parse(myRequest.responseText);
        renderReadings(myData);
    };
    myRequest.send();
}

function renderReadings(data) {
    var readingsContainer = document.getElementById("readings-list");
    var htmlString = "";
    var intro = "";

    document.getElementById("readings-list").innerHTML = "";

    for (const obj of data) {
        if (obj.intro == null) {
            intro = "";
        }

        htmlString = "<div class=\"clearfix\">" + "<a href=\"" + obj.address + "\" target=\"_blank\">" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"books\">" +
            "<p class=\"title\">" + obj.title + "</p></a>" +
            "<p class=\"sumary\">" + intro + "</p>" + "<p class=\"summary\">" + obj.sumary + "</p ><br></div>";

        readingsContainer.insertAdjacentHTML('beforeend', htmlString)
    }
}

/*** OBJECT SECTION
 ******************/

function getObjects() {
    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getObjects.php?path=' + 12, true);
    myRequest.onload = function () {
        var myData = JSON.parse(myRequest.responseText);
        renderObjects(myData);
    };
    myRequest.send();
}

function renderObjects(data) {
    var objectsContainer = document.getElementById("objContainer");
    var htmlString = "";

    for (const obj of data) {

        htmlString = "<div class=\"clearfix\">\n" +
            "<img src=\"" + obj.file + "\" alt=\"\" class=\"objects\" title='Cliquer pour agrandir la photo'>\n" +
            "<p class=\"description\" >" + obj.description + "\n</p >\n<br>\n</div>\n";

        objectsContainer.insertAdjacentHTML('beforeend', htmlString)
    }
    animateObjects()
}

function animateObjects() {
    var objs = document.querySelectorAll('.objects');

    opacity = 0.5;

    objs.forEach(obj => obj.addEventListener('click', objModal));

}

var obj;
var modalObj;

function objModal(e) {
    bdy = document.getElementById('bdy');
    modal = document.getElementById('myObjModal');

    prev = e.target.src;
    obj = prev.replace('preview', 'full');

    modalObj = document.getElementById("obj01");
    modal.style.display = "block";
    modalObj.src = obj;

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
        bdy.style.overflow = 'visible';
    }
}

/*** ADD REPOSITORY
 ********************************/
var repositoryData = [];

function getRepositInputs() {
    var repositoryDoc = document.getElementById('addReposit');

    repositoryData['levels'] = repositoryDoc.elements['repositSelectLevel'].value;
    repositoryData['type'] = repositoryDoc.elements['repositSelectType'].value;
    repositoryData['author'] = repositoryDoc.elements['repositSelectAuthor'].value;
    repositoryData['decade'] = repositoryDoc.elements['repositSelectDecade'].value;
    repositoryData['year'] = repositoryDoc.elements['repositSelectYear'].value;
    repositoryData['title'] = repositoryDoc.elements['repositTitle'].value;
    repositoryData['preview'] = repositoryDoc.elements['repositPreviewInput'].files;
    repositoryData['full'] = repositoryDoc.elements['repositFullInput'].files;
    repositoryData['orig'] = repositoryDoc.elements['repositOrigInput'].files;
    repositoryData['meta'] = repositoryDoc.elements['repositMetaInput'].files;
}

function getRepositInputsPhotos() {
    var repositoryDoc = document.getElementById('addReposit');

    repositoryData['type'] = repositoryDoc.elements['repositSelectType'].value;
    repositoryData['author'] = repositoryDoc.elements['repositSelectAuthor'].value;
    repositoryData['decade'] = repositoryDoc.elements['repositSelectDecade'].value;
    repositoryData['year'] = repositoryDoc.elements['repositSelectYear'].value;
    repositoryData['title'] = repositoryDoc.elements['repositSelectTitle'].value;
    repositoryData['preview'] = repositoryDoc.elements['repositPreviewInput'].files;
    repositoryData['full'] = repositoryDoc.elements['repositFullInput'].files;
    repositoryData['orig'] = repositoryDoc.elements['repositOrigInput'].files;
    repositoryData['meta'] = repositoryDoc.elements['repositMetaInput'].files;
}

function addRepository() {
    getRepositInputs();
    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/addRepository.php?type=' + repositoryData.type +
        '&author=' + repositoryData.author + '&decade=' + repositoryData.decade + '&year=' + repositoryData.year +
        '&title=' + repositoryData.title + '&levels=' + repositoryData.levels + '&function=addRepository', true);

    myRequest.send();

    addRepositoryMysql();
}

function addRepositoryMysql() {
    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/addRepository.php?type=' + repositoryData.type +
        '&author=' + repositoryData.author + '&decade=' + repositoryData.decade + '&year=' + repositoryData.year +
        '&title=' + repositoryData.title + '&levels=' + repositoryData.levels + '&function=addRepositoryMysql', true);

    myRequest.send();

    alert("Success");
    addMetadataToDB();
}

/*** Add Lightroom data to database
 *********************************/
function addMetadataToDB() {
    /*    var fList = [];
        for (var i = 0; i < repositoryData.meta.length; i++) {
            if (fList.length === 0) {
                fList = repositoryData.meta[i].name;
            } else {
                fList = fList + ',' + repositoryData.meta[i].name;
            }
        }
        jList = JSON.stringify(fList);
        var myRequest = new XMLHttpRequest();
        myRequest.open('GET', 'php/addRepository.php?meta=' + jList + '&function=addMetadataToMysql', true);

        myRequest.send();*/
}

function uploadPreview() {
    getRepositInputsPhotos();

    const url = 'php/upload.php';
    const files = document.getElementById('repositPreviewInput').files;
    /*    const path = {type:repositoryData['type'][0], author:repositoryData['author'][0], decade:repositoryData['decade'][0],
            year:repositoryData['year'][0], title:repositoryData['title']};*/
    const formData = new FormData();

    for (let i = 0; i < repositoryData['preview'].length; i++) {
        let file = repositoryData['preview'][i];
        formData.append('files[]', file);
    }

    formData.append('type', repositoryData['type'][0]);
    formData.append('author', repositoryData['author'][0]);
    formData.append('decade', repositoryData['decade'][0]);
    formData.append('year', repositoryData['year'][0]);
    formData.append('title', repositoryData['title'][0]);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => {
        console.log(response);
    });
}

function getYearsSelected() {
    var myYearsData;
    var firstYear;

    var decade = document.getElementById("repositSelectDecade").value;

    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getYears.php?decade=' + decade, true);
    myRequest.onload = function () {
        myYearsData = JSON.parse(myRequest.responseText);
        renderYears(myYearsData);
    };

    myRequest.send();
}

function getYearsSelectedPhotos() {
    var myYearsData;
    var firstYear;

    var decade = document.getElementById("repositSelectDecade").value;

    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getYears.php?decade=' + decade, true);
    myRequest.onload = function () {
        myYearsData = JSON.parse(myRequest.responseText);
        renderYears(myYearsData);

        firstYear = myYearsData[0].idxvalue;
        getReposits(firstYear);
    };

    myRequest.send();
}

function renderYears(data) {
    var yearContainer = document.getElementById('repositSelectYear');
    var htmlString = "";

    document.getElementById("repositSelectYear").innerHTML = "";

    for (const obj of data) {
        htmlString = "<option value=\"" + obj.idxValue + "\">" + obj.year + "</option>";
        yearContainer.insertAdjacentHTML('beforeend', htmlString);
    }
    htmlString = "<option value=\"1\">NA</option>";
    yearContainer.insertAdjacentHTML('beforeend', htmlString);
}

function getReposits(fisrtYear) {
    var myRepositData;
    if (fisrtYear === undefined) {
        var year = document.getElementById("repositSelectYear").value;
    } else {
        year = fisrtYear;
    }

    var myRequest = new XMLHttpRequest();
    myRequest.open('GET', 'php/getReposits.php?year=' + year, true);
    myRequest.onload = function () {
        // console.log(myRequest.responseText);
        myRepositData = JSON.parse(myRequest.responseText);
        renderReposits(myRepositData);
    };

    myRequest.send();
}

function renderReposits(myData) {
    var repositContainer = document.getElementById('repositSelectTitle');
    var htmlString = "";

    document.getElementById("repositSelectTitle").innerHTML = "";

    for (const obj of myData) {
        htmlString = "<option value=\"" + obj.idrpt + "\">" + obj.title + "</option>";
        repositContainer.insertAdjacentHTML('beforeend', htmlString);
    }
}

function renderPreviewText() {
    var files = document.getElementById('repositPreviewInput').files;
    var fileList = createFileList(files);
    document.getElementById("repositPreviewText").value = fileList;
}

function renderFullText() {
    var files = document.getElementById('repositFullInput').files;
    var fileList = createFileList(files);
    document.getElementById("repositFullText").value = fileList;
}

function renderOrigText() {
    var files = document.getElementById('repositOrigInput').files;
    var fileList = createFileList(files);
    document.getElementById("repositOrigText").value = fileList;
}

function renderMetadataText() {
    var files = document.getElementById('repositMetaInput').files;
    var fileList = createFileList(files);
    document.getElementById("repositMetaText").value = fileList;
}

function createFileList(files) {
    var fileList = "";
    i = 0
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
