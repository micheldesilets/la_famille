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
      "<li class='photofolder L2' onclick='javascript:getFamilyPhotos(" + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
    repoId = branch.repository;
  }
}

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
      "<li class='photofolder' onclick='javascript:getFamilyPhotos(" + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
    repoId = branch.repository;
  }
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

/*function getArchives() {
  var family = document.getElementById("family-right").innerHTML;
  n = family.search("Bernard-Normandeau");
  if (n != -1) {
    path = 4
    document.getElementById("family-left").innerHTML = "Photos d'archives des Bernard-Normandeau";
    document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'</span>Marchand-Desilets";
  } else {
    path = 3;
    assignArchivesTitle()
  }
  getPhotos(path, 1)
}*/

function getFamilyPhotos(path, type) {
  getPhotos(path, type);
}

function turnOffFolders() {
  // Hide search and tree and bring up back to tree button
  document.getElementById('photosFolders').style.display = 'none';
  document.getElementById('searchKw').style.display = 'none';
  document.getElementById('backToTree').style.display = 'block';
  return;
}

var myData;

function getPhotos(path, type) {
  var myRequest = new XMLHttpRequest();
  myRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
  myRequest.onload = function () {
    myData = JSON.parse(myRequest.responseText);
    switch (type) {
      case 4:
        renderHomePhoto();
        break;
      case 1:
        /*** Archives ***/
        renderPhotos(path);
        break;
      case 2:
        renderFamilyPhotos();
        break;
    }
  };
  myRequest.send();
}

function backToTree() {
  // Bring back search and tree and hide 'back to tree' button
  document.getElementById('photosFolders').style.display = 'block';
  document.getElementById('searchKw').style.display = 'block';
  document.getElementById('imgs').style.display = 'none';
  document.getElementById('backToTree').style.display = 'none';
}

/*** Used only with FancyBox ***/
function renderPhotos(path) {
  var archivesContainer = document.getElementById("photos");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";

  document.getElementById("photos").innerHTML = "";

  for (const obj of myData) {
    imageURL = obj.path + obj.filename;
    thumb = obj.prev_path + obj.filename;

    htmlString = "<a data-fancybox=\"images\" data-caption=\"" + obj.caption + "\" href=\"" + imageURL + "\">" +
      "<img id=\"boxshadow\" src=\"" + thumb + "\" title=\"" + obj.title + "\" alt=\"" + obj.title + "\" / ></a>";

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
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
  turnOffFolders();
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

    htmlString = "<div><img src=\"" + thumb + "\" alt=\"" + obj.caption + "\" title=\"" + obj.title + "\"></div>"

    familyContainer.insertAdjacentHTML('beforeend', htmlString);
  }
  animatePhotos();
}

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

function animatePhotos() {
  current = document.querySelector('#current');
  imgs = document.querySelectorAll('#imgs img');
  backward = document.getElementById('previous');
  forward = document.getElementById('next');
  geneolCont = document.getElementById('geneol');

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
  geneolCont = document.getElementById("geneol");
  modal.style.display = "block";
  modalTitle.innerHTML = this.title;
  modalImg.src = img;
  captionText.innerHTML = this.alt;

  var idxList = myData[currentIdx].geneolidx.split(',');
  var namesList = myData[currentIdx].geneolnames.split(',');

  htmlGeneol = buildGeneolLine(idxList, namesList);

  geneolCont.innerHTML = htmlGeneol;

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

/*** SEARCH ***/
function getPhotosKeywords() {
  const frm = document.getElementById("searchKw");
  const kw = frm.elements["keywrds"].value;
  console.log(kw);

  var myRequest = new XMLHttpRequest();
  myRequest.open('GET', 'php/getSearchedPhotos.php?kwrd=' + kw, true);
  myRequest.onload = function () {
    var myData = JSON.parse(myRequest.responseText);
    turnOffFolders();
    renderFamilyPhotos(myData);
  };
  myRequest.send();
}

/*** END SEARCH ***/

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
