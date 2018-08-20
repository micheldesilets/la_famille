
function getPhotos(path) {
  console.log(path);
  var ourRequest = new XMLHttpRequest();
  ourRequest.open('GET', 'php/getPhotos.php?path=' + path, true);
  ourRequest.onload = function () {
    var ourData = JSON.parse(ourRequest.responseText);
    console.log(ourData.length);
    renderPhotos(ourData);
  };
  ourRequest.send();
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


