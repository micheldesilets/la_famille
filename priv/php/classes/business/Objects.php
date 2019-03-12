<?php

declare(strict_types=1); // strict mode

namespace priv\php\classes\business;

class Objects implements \JsonSerializable
{
  private $m_idobj;
  private $m_description;
  private $m_file;

  public function set_idpdo($idobj)
  {
    $this->m_idpdo = $idobj;
  }

  function get_idobj()
  {
    return $this->m_idobj;
  }

  /**
   * @param mixed $m_name
   */
  public function set_description($m_description)
  {
    $this->m_description = utf8_encode($m_description);
  }

  /**
   * @return mixed
   */
  public function get_description()
  {
    return $this->m_description;
  }

  /**
   * @param mixed $m_address
   */
  public function set_Address($m_address)
  {
    $this->m_address = $m_address;
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

  public function jsonSerialize()
  {
    return [
      'description' => $this->get_description(),
      'file' => $this->get_File()
    ];
  }
}
