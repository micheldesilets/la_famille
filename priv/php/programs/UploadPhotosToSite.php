<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-03-29
 * Time: 14:29
 */

namespace priv\php\programs;

include_once '../../../priv/initialize.php';

class UploadPhotosToSite
{
    private $path;
    private $identifier;
    private $files;
    private $fileExtension;
    private $fileName;
    private $fileSize;
    private $fileTemp;
    private $fileType;
    private $fileTarget;
    private $fileResize;

    public function __construct($path, $identifier, $files)
    {
        $this->setPath($path);
        $this->setIdentifier($identifier);
        $this->setFiles($files);
    }

    //region Set/Get Region
    private function setPath($path): void
    {
        $this->path = $path;
    }

    private function getPath()
    {
        return $this->path;
    }

    private function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
    }

    private function getIdentifier()
    {
        return $this->identifier;
    }

    private function setFiles($files): void
    {
        $this->files = $files;
    }

    private function getFiles()
    {
        return $this->files;
    }

    public function setFileExtension($fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }

    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileTemp($fileTemp): void
    {
        $this->fileTemp = $fileTemp;
    }

    public function getFileTemp()
    {
        return $this->fileTemp;
    }

    public function setFileType($fileType): void
    {
        $this->fileType = $fileType;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function setFileSize($fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileTarget($fileTarget): void
    {
        $this->fileTarget = $fileTarget;
    }

    public function getFileTarget()
    {
        return $this->fileTarget;
    }

    public function setFileResize($fileResize): void
    {
        $this->fileResize = $fileResize;
    }

    public function getFileResize()
    {
        return $this->fileResize;
    }

    //endregion

    public function upload()
    {
        echo htmlspecialchars($_SERVER["PHP_SELF"]);

        $errors = [];
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];

        $files = $this->getFiles();
        $all_files = count($files['files']['tmp_name']);

        for ($i = 0; $i < $all_files; $i++) {
            $this->setFileName($files['files']['name'][$i]);
            $this->setFileName(str_replace(' ', '_', $this->getFileName()));
            $this->setFileTemp($files['files']['tmp_name'][$i]);
            $this->setFileType($files['files']['type'][$i]);
            $this->setFileSize($files['files']['size'][$i]);
            $this->setFileExtension(
                strtolower(end(explode('.', $files['files']['name'][$i]))));

            $this->replaceSpecialCharacters();
            $errors = $this->checkForErrors($extensions);

            if (empty($errors)) {
                $file = PUBLIC_PATH . '/uploads/' . $this->getFileName();

                $moveResult = move_uploaded_file($this->getFileTemp(), $file);
                if ($moveResult != true) {
                    echo "ERROR: File not uploaded. Try again.";
                    exit();
                }

                $this->resizeFile();

                $this->imageThumbnail();

// ---------- Start Convert to JPG Function --------
                /*                if (strtolower($file_ext) != "jpg") {
                                    $target_file = PUBLIC_PATH . "/uploads/resized_$fileName";
                                    $new_jpg = PUBLIC_PATH . "/uploads/resized_" .
                                        $kaboom[0] . ".jpg";
                                    $this->img_convert_to_jpg($target_file, $new_jpg, $file_ext);
                                }*/
// ----------- End Convert to JPG Function -----------
                unlink(PUBLIC_PATH . "/uploads/" . $this->getFileName());

                /*** Write info to mysql ***/
                //require_once PRIVATE_PHP_PATH .
                // '/programs/UpdatePhotoMetadata.php';
                $db = new addMetadataToMysql($this->getIdentifier(),
                    $this->getFileName());
                $db->addToMysql();

            }
        }
        if ($errors) print_r($errors);
    }


    function img_resize($target, $newcopy, $w, $h, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
            $w = $h * $scale_ratio;
        } else {
            $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif") {
            $img = imagecreatefromgif($target);
        } else if ($ext == "png") {
            $img = imagecreatefrompng($target);
        } else {
            $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);

        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        if ($ext == "gif") {
            imagegif($tci, $newcopy);
        } else if ($ext == "png") {
            imagepng($tci, $newcopy);
        } else {
            imagejpeg($tci, $newcopy, 84);
        }
    }

// ---------------- THUMBNAIL (CROP) FUNCTION ------------------
// Function for creating a true thumbnail cropping from any jpg, gif, or png image files
    function img_thumb($target, $newcopy, $w, $h, $ext)
    {
        list($width, $height) = getimagesize($target);
        $thumb_width = $w;
        $thumb_height = $h;
        $original_aspect = $width / $height;
        $thumb_aspect = $w / $h;
        if ($original_aspect >= $thumb_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $width / ($height / $thumb_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $height / ($width / $thumb_width);
        }
        $img = imagecreatefromjpeg($target);
        $thumb = imagecreatetruecolor($thumb_width, $thumb_height);

        imagecopyresampled($thumb,
            $img,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $width, $height);
        if ($ext == "gif") {
            imagegif($thumb, $newcopy);
        } else if ($ext == "png") {
            imagepng($thumb, $newcopy);
        } else {
            imagejpeg($thumb, $newcopy, 84);
        }
    }

// ------------------ IMAGE CONVERT FUNCTION -------------------
// Function for converting GIFs and PNGs to JPG upon upload
    function img_convert_to_jpg($target, $newcopy, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif") {
            $img = imagecreatefromgif($target);
        } else if ($ext == "png") {
            $img = imagecreatefrompng($target);
        }
        $tci = imagecreatetruecolor($w_orig, $h_orig);
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig);
        imagejpeg($tci, $newcopy, 84);
    }
    //   echo('hehe');
    // }
    private function replaceSpecialCharacters()
    {
        $file_name = $this->getFileName();
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
        $this->setFileName($file_name);
    }

    private function checkForErrors($ext)
    {
        if (!in_array($this->getFileExtension(), $ext)) {
            $errors[] = 'Extension not allowed: ' . $this->getFileName() .
                ' ' . $this->getFileType();
        }

        if ($this->getFileSize() > 10485760) {
            $errors[] = 'File size exceeds limit: ' . $this->getFileName() .
                ' ' . $this->getFileType();
        }
        return $errors;
    }

    private function resizeFile()
    {
        $this->setFileTarget(PUBLIC_PATH . "/uploads/" . $this->getFileName());
        $file = $this->getPath() . '/full/' . $this->getFileName();
        $this->setFileResize($file);
        $wmax = 800;
        $hmax = 800;
        $this->img_resize($this->getFileTarget(), $this->getFileResize(),
            $wmax, $hmax, $this->getFileExtension());
    }

    private function imageThumbnail()
    {
        $this->setFileTarget($this->getFileResize());
        $thumbnail = $this->getPath() . '/preview/' . $this->getFileName();
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
        $this->img_thumb($this->getFileTarget(), $thumbnail, $wthumb, $hthumb,
            $this->getFileExtension());
    }
}