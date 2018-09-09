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

function buildFolderTree(data) {
  var folderContainer = document.getElementById("photosFolders");
  var creator = "";
  var decade = "";
  var year = 0;
  var itm = 0;
  var sbitm = 0;
  var htmlString = "";

  for (const branch of data) {
    if (creator == "") {
      htmlString =
        "<input type=\"checkbox\" id=\"menu\"/>" +
        "      <label for=\"menu\" class=\"names\">" + "Photos de " + branch.creator + "</label>" +
        "      <div class=\"multi-level\">" +
        "        <div class=\"item\">" +
        "          <input type=\"checkbox\" id=\"IT" + itm + "\"/>" +
        "          <img src=\"img/icons/arrow.png\" class=\"arrow\">" +
        "          <label for=\"IT" + itm + "\">" + branch.decade + "</label>" +
        "          <ul>\n" +
        "            <li>\n" +
        "              <div class=\"sub-item\">\n" +
        "                <input type=\"checkbox\" id=\"SIT" + itm + "-" + sbitm + "\"/>\n" +
        "                <img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
        "                <label for=\"SIT" + itm + "-" + sbitm + "\">" + branch.year + "</label>\n" +
        "                <ul>\n" +
        "                  <li onclick='javascript:getFamilyPhotos(" + branch.repository + "," + branch.type + ")'>" + branch.title + "</a></li>\n";
      creator = branch.creator;
      decade = branch.decade;
      year = branch.year;
    } else {
      if (year == branch.year && decade == branch.decade && creator == branch.creator) {
        htmlString = htmlString +
          "                  <li><a href=\"#\">" + branch.title + "</a></li>\n";
      } else {
        htmlString = htmlString +
          "                </ul>\n" +
          "              </div>\n" +
          "            </li>\n" +
          "          </ul>\n" +
          "        </div>\n"
      }
    }

  }
  folderContainer.insertAdjacentHTML('beforeend', htmlString);
}

function assignPhotosHierarchy() {
  photosFamilyContainer = document.getElementById("hierarchyPhotos");
  var htmlString =
    "<input type=\"checkbox\" id=\"menu\"/>" +
    "      <label for=\"menu\" class=\"names\">Photos Michel</label>" +
    "      <div class=\"multi-level\">" +
    "        <div class=\"item\">" +
    "          <input type=\"checkbox\" id=\"A\"/>" +
    "          <img src=\"img/icons/arrow.png\" class=\"arrow\">" +
    "          <label for=\"A\">1970-1979</label>" +
    "          <ul>\n" +
    "            <li>\n" +
    "              <div class=\"sub-item\">\n" +
    "                <input type=\"checkbox\" id=\"A-A\"/>\n" +
    "                <img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
    "                <label for=\"A-A\">1975</label>\n" +
    "                <ul>\n" +
    "                  <li><a href=\"#\">Princeville Chantal et Micha</a></li>\n" +
    "                </ul>\n" +
    "              </div>\n" +
    "            </li>\n" +
    "          </ul>\n" +
    "        </div>\n" +
    "        <div class=\"item\">\n" +
    "          <input type=\"checkbox\" id=\"B\"/>\n" +
    "          <img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
    "          <label for=\"B\">1980-1989</label>\n" +
    "          <ul>\n" +
    "            <li>\n" +
    "              <div class=\"sub-item\">\n" +
    "                <input type=\"checkbox\" id=\"B-A\"/>\n" +
    "                <img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
    "                <label for=\"B-A\">1981</label>\n" +
    "                <ul>\n" +
    // "                  <li id=\"photos\"><a href=\"javascript:getFamilyPhotos(6)\">Été vacances</a></li>\n" +
    "                  <li onclick='javascript:getFamilyPhotos(2,2)'>Été vacances</li>\n" +
    "                  <li><a href=\"#\">Ail des bois, Evelyne dans le bain (6 mois)</a></li>\n" +
    "                  <li><a href=\"#\">Temple avec Bigras, famille Provost </a></li>\n" +
    "                  <li><a href=\"#\">Maquette Josette, enfants Centre d'Achat les Rivières</a></li>\n" +
    "                </ul>\n" +
    "              </div>\n" +
    "            </li>\n" +
    "            <li>\n" +
    "              <div class=\"sub-item\">\n" +
    "                <input type=\"checkbox\" id=\"B-B\"/>\n" +
    "                <img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
    "                <label for=\"B-B\">1982</label>\n" +
    "                <ul>\n" +
    "                  <li><a href=\"#\">Printemps famille</a></li>\n" +
    "                  <li><a href=\"#\"> Anniversaire Ali, glissade Valleyfield</a></li>\n" +
    "                  <li><a href=\"#\">Bateaux, Anniversaire Do</a></li>\n" +
    "                  <li><a href=\"#\">Noel, Jour de l'An</a></li>\n" +
    "                </ul>\n" +
    "              </div>\n" +
    "            </li>\n" +
    "            <li>\n" +
    "              <div class=\"sub-item\">\n" +
    "                <input type=\"checkbox\" id=\"B-C\"/>\n" +
    "                <img src=\"img/icons/arrow.png\" class=\"arrow\"><label for=\"B-C\">1983</label>\n" +
    "                <ul>\n" +
    "                  <li><a href=\"#\">Fête Do et fraises</a></li>\n" +
    "                </ul>\n" +
    "              </div>\n" +
    "            </li>\n" +
    "          </ul>\n" +
    "        </div>\n" +
    "        <div class=\"item\">\n" +
    "          <input type=\"checkbox\" id=\"C\"/>\n" +
    "          <img src=\"img/icons/arrow.png\" class=\"arrow\"><label for=\"C\">1990-1999</label>\n" +
    "          <ul>\n" +
    "            <li>\n" +
    "              <div class=\"sub-item\">\n" +
    "                <input type=\"checkbox\" id=\"C-A\"/>\n" +
    "                <img src=\"img/icons/arrow.png\" class=\"arrow\"><label for=\"C-A\">1991</label>\n" +
    "                <ul>\n" +
    "                  <li><a href=\"#\">Été Alberta</a></li>\n" +
    "                </ul>\n" +
    "              </div>\n" +
    "            </li>\n" +
    "          </ul>\n" +
    "        </div>\n" +
    "      </div>";

  photosFamilyContainer.insertAdjacentHTML('beforeend', htmlString);
}

function getArchives() {
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
}

function getFamilyPhotos(path, type) {
  document.getElementById("photosFolders").innerHTML = "";
  getPhotos(path, type);
}

function getPhotos(path, type) {
  var myRequest = new XMLHttpRequest();
  myRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
  myRequest.onload = function () {
    var myData = JSON.parse(myRequest.responseText);
    switch (type) {
      case 4:
        renderHomePhoto(myData);
        break;
      case 1:
        /*** Archives ***/
        renderPhotos(myData, path);
        break;
      case 2:
        renderFamilyPhotos(myData);
        break;
    }
  };
  myRequest.send();
}

/*** Used only with FancyBox ***/
function renderPhotos(data, path) {
  var archivesContainer = document.getElementById("photos");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";

  document.getElementById("photos").innerHTML = "";

  for (const obj of data) {
    imageURL = obj.path + obj.filename;
    thumb = obj.prev_path + obj.filename;

    htmlString = "<a data-fancybox=\"images\" data-caption=\"" + obj.caption + "\" href=\"" + imageURL + "\">" +
      "<img id=\"boxshadow\" src=\"" + thumb + "\" title=\"" + obj.title + "\" alt=\"" + obj.title + "\" / ></a>";

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
}

function renderHomePhoto(data) {
  var archivesContainer = document.getElementById("homePhoto");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";

  for (const obj of data) {
    imageURL = obj.path + obj.filename;
    thumb = obj.prev_path + obj.filename;

    htmlString = "<img src=\"" + imageURL + "\" alt=\"" + obj.title + "\">";

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
}

function renderFamilyPhotos(data) {
  var familyContainer = document.getElementById("imgs");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";

  document.getElementById("imgs").innerHTML = "";

  for (const obj of data) {
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

function animatePhotos() {
  current = document.querySelector('#current');
  imgs = document.querySelectorAll('#imgs img');
  backward = document.getElementById('previous');
  forward = document.getElementById('next');

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

  modalImg = document.getElementById("img01");
  captionText = document.getElementById("caption");
  // img.onclick = function () {
  modal.style.display = "block";
  modalImg.src = img;
  captionText.innerHTML = this.alt;
  // }


// Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
  span.onclick = function () {
    modal.style.display = "none";
  }
}

function prevImage() {
  if (currentIdx > 0) {
    img = imgs[currentIdx - 1].src;
    img = img.replace('preview', 'full');
    capt = imgs[currentIdx - 1].alt;
    modalImg.src = img;
    captionText.innerHTML = capt;
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
  if (currentIdx < maxLength - 1) {
    img = imgs[currentIdx + 1].src;
    img = img.replace('preview', 'full');
    capt = imgs[currentIdx + 1].alt;
    modalImg.src = img;
    captionText.innerHTML = capt;
    currentIdx++;
    if (currentIdx == maxLength - 1) {
      forward.style.backgroundColor = "red";
    }
    if (currentIdx > 0) {
      backward.style.backgroundColor = "green";
    }
  }
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
    path = 5
    document.getElementById("family-left").innerHTML = "Lectures des Bernard-Normandeau";
    document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'</span>Normandeau-Desilets";
  } else {
    path = 3;
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


