<?php header("Content-type: text/css; charset: UTF-8");

include '../connection/connect.php';
$sql = "SELECT
  paths_pat.path_pat,
  photos_pho.filename_pho
FROM parameters_par
  INNER JOIN photos_pho
    ON parameters_par.home_idpho_par = photos_pho.id_pho
  INNER JOIN paths_pat
    ON photos_pho.idpat_pho = paths_pat.id_pat
WHERE photos_pho.id_pho = parameters_par.home_idpho_par";

if ($result = mysqli_query($con, $sql)) {
  // Return the number of rows in result set
  $rowcount = mysqli_num_rows($result);
  printf("Result set has %d rows.\n", $rowcount);
} else {
  echo("nothing");
};

// Associative array
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

$path = "../" . $row["path_pat"];
$filename = $row["filename_pho"];
?>

/* Voir Notes */
h1 {}
/* */

body {
  background-image: url("<?php printf($path . $filename) ?>");
}


<?php
// Free result set
mysqli_free_result($result);

mysqli_close($con);

?>
