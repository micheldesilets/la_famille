<?php

declare(strict_types=1); // strict mode
class Readings
{
  private $m_idpdo;
  private $m_title;
  private $m_address;
  private $m_intro;
  private $m_resume;
  private $m_file;

  public function set_idpdo($idpdo)
  {
    $this->m_idpdo = $idpdo;
  }

  function get_idpdo()
  {
    return $this->m_idpdo;
  }

  /**
   * @param mixed $m_name
   */
  public function set_Title($m_title)
  {
    $this->m_title = utf8_encode($m_title);
  }

  /**
   * @return mixed
   */
  public function get_Title()
  {
    return $this->m_title;
  }

  /**
   * @param mixed $m_address
   */
  public function set_Address($m_address)
  {
    $this->m_address = $m_address;
  }

  /**
   * @return mixed
   */
  public function get_Address()
  {
    return $this->m_address;
  }

  /**
   * @param mixed $m_intro
   */
  public function set_Intro($m_intro): void
  {
    $this->m_intro = utf8_encode($m_intro);
  }

  /**
   * @return mixed
   */
  public function get_Intro()
  {
    return $this->m_intro;
  }
  /**
   * @param mixed $m_resume
   */
  public function set_Resume($m_resume)
  {
    $this->m_resume = utf8_encode($m_resume);
  }

  /**
   * @return mixed
   */
  public function get_Resume()
  {
    return $this->m_resume;
  }

  /**
   * @param mixed $m_file
   */
  public function set_File($m_file): void
  {
    $this->m_file = $m_file;
  }

  /**
   * @return mixed
   */
  public function get_File()
  {
    return $this->m_file;
  }

}
