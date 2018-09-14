function buildFolderTree(data) {
  var folderContainer = document.getElementById("photosFolders");
  var level1 = null;
  var level2 = null;
  var level3 = null;
  var flevel1 = true;
  var flevel2 = true;
  var flevel3 = true;
  var itm = 0;
  var sbitm = 0;
  var htmlString = "";


  for (const branch of data) {
    if (flevel1) {
      flevel1 = false;
      htmlString =
        "<input type=\"checkbox\" id=\"menu\"/>\n" +
        "<label for=\"menu\" class=\"names\">" + "Photos de " + branch.author + "</label>\n" +
        "<div class=\"multi-level\">\n";
      level1 = branch.level1;
      if (flevel2) {
        flevel2 = false;
        htmlString = htmlString +
          "<div class=\"item\">\n" +
          "<input type=\"checkbox\" id=\"IT" + itm + "\"/>\n" +
          "<img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
          "<label for=\"IT" + itm + "\">" + branch.decade + "</label>\n" +
          "<ul>\n"
        level2 = branch.level2;
        if (flevel3) {
          flevel3 = false;
          htmlString = htmlString +
            "<li>\n" +
            "<div class=\"sub-item\">\n" +
            "<input type=\"checkbox\" id=\"SIT" + itm + "-" + sbitm + "\"/>\n" +
            "<img src=\"img/icons/arrow.png\" class=\"arrow\">\n" +
            "<label for=\"SIT" + itm + "-" + sbitm + "\">" + branch.year + "</label>\n" +
            "<ul>\n" +
            "<li onclick='javascript:getFamilyPhotos(" + branch.repository + "," + branch.type + ")'>" + branch.title + "</li>\n";
          level3 = branch.level3;
        }
      }
    }
  }
  htmlString = htmlString +
    "</ul>\n" +
    "</div>\n" +
    "</li>\n" +
    "</ul>\n" +
    "</div>\n" +
    "</div>";

  folderContainer.insertAdjacentHTML('beforeend', htmlString);
}
