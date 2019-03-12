<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-02
 * Time: 14:07
 */

include_once '../../priv/initialize.php';

echo htmlspecialchars($_SERVER["PHP_SELF"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $errors = [];
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];

        $all_files = count($_FILES['files']['tmp_name']);

        $type = $_POST['type'];
        $member = $_POST['member'];
        $decade = $_POST['decade'];
        $year = $_POST['year'];
        $title = $_POST['title'];

        $folderData = array($type, $member, $decade, $year, $title);
        require_once CLASSES_PATH . '/database/FoldersDB.php';
        $db = new FoldersDB();
        $pathSql = $db->getPath($folderData);
        $path = $pathSql[0];
        $idRpt = $pathSql[1];
        $curr = getcwd();
        for ($i = 0; $i < $all_files; $i++) {
            $file_name = $_FILES['files']['name'][$i];
            $file_name = str_replace(' ', '_', $file_name);
            $file_tmp = $_FILES['files']['tmp_name'][$i];
            $file_type = $_FILES['files']['type'][$i];
            $file_size = $_FILES['files']['size'][$i];
            $file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));


            $file_name = str_replace(' ', '_', $file_name);
            $file_name = str_replace('é', 'e', $file_name);
            $file_name = str_replace('è', 'e', $file_name);
            $file_name = str_replace('ê', 'e', $file_name);
            $file_name = str_replace('ä', 'a', $file_name);
            $file_name = str_replace('û', 'u', $file_name);
            $file_name = str_replace('à', 'a', $file_name);
            $file_name = str_replace('É', 'E', $file_name);
            $file_name = str_replace('ô', 'o', $file_name);
            $file_name = str_replace('ç', 'c', $file_name);
            $file_name = str_replace('ë', 'e', $file_name);

//            $file = $path . $file_name;

            if (!in_array($file_ext, $extensions)) {
                $errors[] = 'Extension not allowed: ' . $file_name . ' ' .
                    $file_type;
            }

            if ($file_size > 10485760) {
                $errors[] = 'File size exceeds limit: ' . $file_name . ' ' .
                    $file_type;
            }

            if (empty($errors)) {
                $file = PUBLIC_PATH . '/uploads/' . $file_name;
//                $file = $path . 'full/' . $file_name;
                $moveResult = move_uploaded_file($file_tmp, $file);
                if ($moveResult != true) {
                    echo "ERROR: File not uploaded. Try again.";
                    exit();
                }
                // Include the file that houses all of our custom image functions
                include_once(PRIVATE_PATH . "/php/ImgLibrary.php");
// ---------- Start Universal Image Resizing Function --------
                $target_file = PUBLIC_PATH . "/uploads/" . $file_name;
                $file = $path . 'full/' . $file_name;
//                $resized_file = "public/uploads/resized_$fileName";
                $resized_file = $file;
                $wmax = 800;
                $hmax = 800;
                img_resize($target_file, $resized_file, $wmax, $hmax, $file_ext);
// ----------- End Universal Image Resizing Function ----------
// ------ Start Universal Image Thumbnail(Crop) Function ------
//                $target_file = "public/uploads/resized_$fileName";
                $target_file = $resized_file;
//                $thumbnail = "public/uploads/thumb_$fileName";
                $thumbnail = $path . 'preview/' . $file_name;
                $random = rand(0, 6);
                switch ($random) {
                    case 0;
                        $wthumb = 125;
                        $hthumb = 125;
                        break;
                    case 1;
                        $wthumb = 150;
                        $hthumb = 125;
                        break;
                    case 2;
                        $wthumb = 100;
                        $hthumb = 125;
                        break;
                    case 3;
                        $wthumb = 125;
                        $hthumb = 150;
                        break;
                    case 4;
                        $wthumb = 125;
                        $hthumb = 100;
                        break;
                    case 5;
                        $wthumb = 100;
                        $hthumb = 150;
                        break;
                    case 6;
                        $wthumb = 150;
                        $hthumb = 150;
                        break;
                }
                img_thumb($target_file, $thumbnail, $wthumb, $hthumb, $file_ext);
// ------- End Universal Image Thumbnail(Crop) Function -------
// ---------- Start Convert to JPG Function --------
                if (strtolower($file_ext) != "jpg") {
                    $target_file = PUBLIC_PATH . "/uploads/resized_$fileName";
                    $new_jpg = PUBLIC_PATH . "/uploads/resized_" .
                        $kaboom[0] . ".jpg";
                    img_convert_to_jpg($target_file, $new_jpg, $file_ext);
                }
// ----------- End Convert to JPG Function -----------
                unlink(PUBLIC_PATH . "/uploads/$file_name");

                /*** Write info to mysql ***/
                require_once PRIVATE_PHP_PATH . '/programs/UpdatePhotoMetadata.php';
                $db = new addMetadataToMysql($idRpt, $file_name);
                //         $db->addMetadataToMysql($idRpt, $file_name);
            }
        }
        if ($errors) print_r($errors);
    }
    echo('hehe');
}
