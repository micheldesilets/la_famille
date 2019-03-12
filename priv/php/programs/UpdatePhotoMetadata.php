<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-25
 * Time: 11:41
 */

namespace priv\php\programs;

use priv\php\connection as con;

class UpdatePhotoMetadata
{
    private $info;
    private $connection;

    public function __construct($photoInfo)
    {
        $this->info=$photoInfo;
        $this->insertPhotoInfo();
    }

    private function insertPhotoInfo()
    {
        $this->connection = new con\DbConnection();
        $con = $this->connection->Connect();

        $con->query('SET NAMES utf8');
        try {
            $photoId = $this->info[0];
            $title = $this->info[1];
            $keywords = $this->info[2];
            $caption = $this->info[3];
            $year = $this->info[4];
            $geneologyIdxs = $this->info[5];

            $sql = "UPDATE photos_pho pp
                       SET pp.title_pho = ?, pp.keywords_pho = ?, 
                           pp.caption_pho = ?, pp.year_pho = ?, pp.idgen_pho = ?
                     WHERE pp.id_pho = ?";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssisi", $title, $keywords, $caption, $year,
                $geneologyIdxs, $photoId);
            $stmt->execute();
            $stmt->close();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            exit();
        }
    }
}