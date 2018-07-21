<?php

declare(strict_types=1); // strict mode
class Readings
{
  private $m_idpdo;
  private $m_title;
  private $m_address;

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

}
