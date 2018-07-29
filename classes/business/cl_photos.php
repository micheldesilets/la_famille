<?php

declare(strict_types=1); // strict mode
class Photos
{
  private $m_idpho;
  private $m_title;
  private $m_keywords;
  private $m_height;
  private $m_width;
  private $m_caption;
  private $m_f_path;
  private $m_p_path;
  private $m_filename;
  private $m_pdf;

  public function set_Idpho($idpho){
    $this->m_idpho = $idpho;
  }

  function get_idpho(){
    return $this->m_idpho;
  }

  public function set_Title($title){
    $this->m_title = utf8_encode($title);
  }

  function get_Title(){
    return $this->m_title;
  }

  public function set_Keywords($keywords){
    $this->m_keywords = utf8_encode($keywords);
  }

  function get_keywords(){
    return $this->m_keywords;
  }

  public function set_Height($height){
    $this->m_height = $height;
  }

  function get_Height(){
    return $this->m_height;
  }

  public function set_Width($width){
    $this->m_width = $width;
  }

  function get_Width(){
    return $this->m_width;
  }

  public function set_Caption($caption){
    $this->m_caption = utf8_encode($caption);
  }

  function get_Caption() {
    return $this->m_caption;
  }

  public function set_F_Path($path){
  $this->m_f_path = utf8_encode($path);
}

  function get_F_Path(){
    return $this->m_f_path;
  }

  public function set_P_Path($path){
    $this->m_p_path = utf8_encode($path);
  }

  function get_P_Path(){
    return $this->m_p_path;
  }

  /**
   * @param $filename
   */
  public function set_Filename($filename){
    $this->m_filename = str_replace(' ','_', utf8_encode($filename));
  }

  function get_Filename(){
    return $this->m_filename;
  }

  /**
   * @param mixed $m_pdf
   */
  public function set_Pdf($m_pdf): void
  {
    $this->m_pdf = $m_pdf;
  }

  /**
   * @return mixed
   */
  public function get_Pdf()
  {
    return $this->m_pdf;
  }

}


