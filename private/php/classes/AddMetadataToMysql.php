<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-02-27
 * Time: 11:25
 */

class AddMetadataToMysql
{
    private $rpt;
    private $filename;

    public function __construct($idRpt,$file_name)
    {
        $this->rpt=$idRpt;
        $this->filename=$file_name;
        $this->addToMysql();
    }

   private function addToMysql()
    {
        try {
            include INCLUDES_PATH . 'db_connect.php';
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            $u = PrivilegedUser::getByUsername($_SESSION["username"]);
            $email = $u->getEmail();

            $sql = "INSERT INTO photos_pho (
                              idfol_pho, filename_pho,owner_pho)
                       VALUES (?,?,?)";

            $stmt = $con->prepare($sql);
            $stmt->bind_param("iss", $this->rpt, utf8_encode($this->filename),
                $email);
            $stmt->execute();
            $stmt->close();
            return;
        } catch (Exception $e) {
            error_log($e->getMessage());
            exit(); //Should be a message a typical user could understand
        }
    }
}