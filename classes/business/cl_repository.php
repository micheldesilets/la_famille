<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-19
 * Time: 12:29
 */

class cl_repository implements JsonSerializable
{
  private $m_levels;
  private $m_type;
  private $m_author;
  private $m_decade;
  private $m_year;
  private $m_title;

  /**
   * @return mixed
   */
  public function get_Levels()
  {
    return $this->m_levels;
  }

  /**
   * @param mixed $m_levels
   */
  public function set_Levels($m_levels): void
  {
    $this->m_levels = $m_levels;
  }

  /**
   * @param mixed $m_type
   */
  public function set_Type($m_type): void
  {
    $this->m_type = $m_type;
  }

  /**
   * @return mixed
   */
  public function get_Type()
  {
    return $this->m_type;
  }

  /**
   * @param mixed $m_author
   */
  public function set_Author($m_author): void
  {
    $this->m_author = $m_author;
  }

  /**
   * @return mixed
   */
  public function get_Author()
  {
    return $this->m_author;
  }

  /**
   * @param mixed $m_decade
   */
  public function set_Decade($m_decade): void
  {
    $this->m_decade = $m_decade;
  }

  /**
   * @return mixed
   */
  public function get_Decade()
  {
    return $this->m_decade;
  }

  /**
   * @param mixed $m_year
   */
  public function set_Year($m_year): void
  {
    $this->m_year = $m_year;
  }

  /**
   * @return mixed
   */
  public function get_Year()
  {
    return $this->m_year;
  }

  /**
   * @param mixed $m_title
   */
  public function set_Title($m_title): void
  {
    $this->m_title = $m_title;
  }

  /**
   * @return mixed
   */
  public function get_Title()
  {
    return $this->m_title;
  }

  public function jsonSerialize()
  {
    return [
      'type' => $this->get_Type(),
      'author' => $this->get_Author(),
      'decade' => $this->get_Decade(),
      'year' => $this->get_Type(),
      'title' => $this->get_Title(),
    ];
  }
}
