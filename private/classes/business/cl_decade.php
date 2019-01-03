<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-20
 * Time: 15:38
 */

class decade implements JsonSerializable
{
    private $m_iddeca;
    private $m_decade;
    private $m_fromYear;
    private $m_toYear;

    /**
     * @param mixed $m_iddeca
     */
    public function set_Iddeca($m_iddeca): void
    {
        $this->m_iddeca = $m_iddeca;
    }

    /**
     * @return mixed
     */
    public function get_Iddeca()
    {
        return $this->m_iddeca;
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
     * @param mixed $m_fromYear
     */
    public function set_FromYear($m_fromYear): void
    {
        $this->m_fromYear = $m_fromYear;
    }

    /**
     * @return mixed
     */
    public function get_FromYear()
    {
        return $this->m_fromYear;
    }

    /**
     * @param mixed $m_toYear
     */
    public function set_ToYear($m_toYear): void
    {
        $this->m_toYear = $m_toYear;
    }

    /**
     * @return mixed
     */
    public function get_ToYear()
    {
        return $this->m_toYear;
    }

    public function jsonSerialize()
    {
        return [
            'idDecade' => $this->get_Iddeca(),
            'decade' => $this->get_Decade(),
            'fromYear' => $this->get_FromYear(),
            'toYear' => $this->get_ToYear(),
        ];
    }
}
