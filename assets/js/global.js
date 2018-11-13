var animalContainer = document.getElementById("animal-info");

var that = $(this),
  contents = that.serialize();

$.ajax({
  url: '../php/getPhotos.php',
  dataType: 'json',
  type: 'post',
  data: contents,
  success: function (data) {
    console.log(data[0]);
    if (data.length > 0) {
      renderHtml(data[0].m_title);
    }
  }
});

function renderHtml(data) {
  var htmlString = "";
  htmlString += "<p>" + data + "</p>";
  animalContainer.insertAdjacentHTML('beforeend', htmlString)
}
