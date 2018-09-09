<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-09-08
 * Time: 07:24
 */

class Folders implements JsonSerializable
{
  private $m_idrpt;
  private $m_idtyp;
  private $m_creator;
  private $m_decade;
  private $m_year;
  private $m_title;

  public function setRepositoryId($m_idrpt): void
  {
    $this->m_idrpt = $m_idrpt;
  }

  public function getRepositoryId()
  {
    return $this->m_idrpt;
  }

  public function setTypeId($m_idtyp): void
  {
    $this->m_idtyp = $m_idtyp;
  }

  public function getTypeId()
  {
    return $this->m_idtyp;
  }

  public function setCreator($m_creator): void
  {
    $this->m_creator = utf8_encode($m_creator);
  }

  public function getCreator()
  {
    return $this->m_creator;
  }

  public function setDecade($m_decade): void
  {
    $this->m_decade = $m_decade;
  }

  public function getDecade()
  {
    return $this->m_decade;
  }

  public function setYear($m_year): void
  {
    $this->m_year = $m_year;
  }

  public function getYear()
  {
    return $this->m_year;
  }

  public function setTitle($m_title): void
  {
    $this->m_title = utf8_encode($m_title);
  }

  public function getTitle()
  {
    return $this->m_title;
  }

  public function jsonSerialize()
  {
    return [
      'repository' => $this->getRepositoryId(),
      'type' => $this->getTypeId(),
      'creator' => $this->getCreator(),
      'decade' => $this->getDecade(),
      'year' => $this->getYear(),
      'title' => $this->getTitle(),
    ];
  }
}
