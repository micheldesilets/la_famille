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
      case 6:
        renderPhotos(myData);
        break;
    }
  };
  myRequest.send();
}

function renderPhotos(data) {
  var archivesContainer = document.getElementById("photos");
  var htmlString = "";
  var imageURL = "";
  var thumb = "";
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

