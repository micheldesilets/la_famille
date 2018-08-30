/*** Photos section ***/

function assignArchivesTitle() {
  document.getElementById("family-left").innerHTML = "Photos d'archives des Marchand-Desilets";
  document.getElementById("family-right").innerHTML = "Vers les <span style='font-weight:bold;'>Bernard-Normandeau</span> ";
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
    "                  <li id=\"photos\"><a href=\"javascript:getFamilyPhotos(6)\">Été vacances</a></li>\n" +
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
    path = 1;
    assignArchivesTitle()
  }
  getPhotos(path)
}

function getFamilyPhotos(path) {
  document.getElementById("hierarchyPhotos").innerHTML = "";
  getPhotos(path);
}

function getPhotos(path) {
  var myRequest = new XMLHttpRequest();
  myRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
  myRequest.onload = function () {
    var myData = JSON.parse(myRequest.responseText);
    switch (path) {
      case 2:
        renderHomePhoto(myData);
        break;
      case 1:
      case 4:
        renderPhotos(myData, path);
        break;
      case 6:
        renderFamilyPhotos(myData);
        break;
    }
  };
  myRequest.send();
}

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
    // for (i = 0; i < data.length; i++) {
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

function animatePhotos() {
  current = document.querySelector('#current');
  imgs = document.querySelectorAll('#imgs img');
  opacity = 0.5;

//Set first image opacity
//   imgs[0].style.opacity = opacity;

  imgs.forEach(img => img.addEventListener('click', imgModal));

}

/*** MODEL ***/
var modal;

function imgModal(e) {
  modal = document.getElementById('myModal');

  var prev = e.target.src;
  var img = prev.replace('preview', 'full');

  var modalImg = document.getElementById("img01");
  var captionText = document.getElementById("caption");
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

/*** END MODAL ***/

function imgClick(e) {
  // Reset opacity
  imgs.forEach(img => (img.style.opacity = 1));

  // Change current image to src of clicked image
  var prev = e.target.src;
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

