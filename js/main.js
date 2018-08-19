// var animalContainer = document.getElementById("animal-info");

function showAnimal() {
  var pageCounter = 1;

// var btn = document.getElementById("btn");


// btn.addEventListener("click", function() {
  var ourRequest = new XMLHttpRequest();
  ourRequest.open('GET', 'https://learnwebcode.github.io/json-example/animals-1.json');
  ourRequest.onload = function () {
    var ourData = JSON.parse(ourRequest.responseText);
    // renderHtml(ourData);
    console.log(ourData[0]);
  };
  ourRequest.send();
// pageCounter++;
// if (pageCounter >3) {
//   btn.classList.add("hide-me");
// }
// });
}

function renderHtml(data) {
  var htmlString = "";

  for (i = 0; i < data.length; i++) {
    htmlString += "<p>" + data[i].name + " is a " + data[i].species + ".</p>";
  }

  // animalContainer.insertAdjacentHTML('beforeend',htmlString)
}

function toCelsius(f) {
  var htmlString = -1;
  var celsiusContainer = document.getElementById("demo");
  var result = (5 / 9) * (f - 32);
  htmlString = "<p>" + result + "</p>";
  celsiusContainer.insertAdjacentHTML('beforeend', htmlString);
  // return (5 / 9) * (f - 32);
}

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
  var imageURL;
  var thumb;
  for (i = 0; i < data.length; i++) {
    $imageURL = data[i].path + data[i].filename;
    $thumb = data[i].prev_path + data[i].filename;

    htmlString = "<a data-fancybox=\"images\" data-caption=\"" + data[i].caption + "\" href=\"" + $imageURL + "\">" +
      "<img id=\"boxshadow\" src=\"" + $thumb + "\" title=\"" + data[i].title + "\" alt=\"" + data[i].title + "\" / ></a>";

    archivesContainer.insertAdjacentHTML('beforeend', htmlString)
  }
}


