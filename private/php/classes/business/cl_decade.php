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

    public function __construct($iddecaR, $decadeR, $fromYearR, $toYearR)
    {
        $this->m_iddeca = $iddecaR;
        $this->m_decade = $decadeR;
        $this->m_fromYear = $fromYearR;
        $this->m_toYear = $toYearR;
    }

    /**
     * @return mixed
     */
    public function get_Iddeca()
    {
        return $this->m_iddeca;
    }

    /**
     * @return mixed
     */
    public function get_Decade()
    {
        return $this->m_decade;
    }

    /**
     * @return mixed
     */
    public function get_FromYear()
    {
        return $this->m_fromYear;
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
