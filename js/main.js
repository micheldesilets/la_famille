function assignArchivesTitle() {

}

function getPhotos(path) {
  var myRequest = new XMLHttpRequest();
  myRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
  myRequest.onload = function () {
    var myData = JSON.parse(myRequest.responseText);
    console.log(myData.length);
    switch (path) {
      case 2:
        renderHomePhoto(myData);
        break;
      case 1:
      case 4:
        renderPhotos(myData, path);
        break
      case 6:
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
  /*
    switch (path) {
      case 1:
        document.getElementById("archives-left").innerHTML = "Photos d'archives des Marchand-Desilets";
        document.getElementById("archives-right").innerHTML = "Photos d'archives des Bernard-Normandeau";
        break
      case 4:
        document.getElementById("archives-left").innerHTML = "Photos d'archives des Bernard-Normandeau";
        document.getElementById("archives-right").innerHTML = "Photos d'archives des Marchand-Desilets";
        break
    }*/

  document.getElementById("photos").innerHTML = "";

  for (i = 0; i < data.length; i++) {
    $imageURL = data[i].path + data[i].filename;
    $thumb = data[i].prev_path + data[i].filename;

    htmlString = "<a data-fancybox=\"images\" data-caption=\"" + data[i].caption + "\" href=\"" + $imageURL + "\">" +
      "<img id=\"boxshadow\" src=\"" + $thumb + "\" title=\"" + data[i].title + "\" alt=\"" + data[i].title + "\" / ></a>";

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
}

function renderHomePhoto(data) {
  var archivesContainer = document.getElementById("homePhoto");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";
  for (i = 0; i < data.length; i++) {
    $imageURL = data[i].path + data[i].filename;
    $thumb = data[i].prev_path + data[i].filename;

    htmlString = "<img src=\"" + $imageURL + "\" alt=\"" + data[i].title + "\">";
    console.log(htmlString);

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
}

