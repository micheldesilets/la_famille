<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-10-20
 * Time: 15:38
 */

class cl_year implements JsonSerializable
{
    private $m_idyea;
    private $m_iddeca;
    private $m_decade;
    private $m_year;

    public function __construct($idyeaR, $iddecaR, $decadeR, $yearR)
    {
        $this->m_idyea = $idyeaR;
        $this->m_iddeca = $iddecaR;
        $this->m_decade = $decadeR;
        $this->m_year = $yearR;
    }

    /**
     * @return mixed
     */
    public function get_Idyea()
    {
        return $this->m_idyea;
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
    public function get_Year()
    {
        return $this->m_year;
    }

    public function jsonSerialize()
    {
        return [
            'idxYear' => $this->get_Idyea(),
            'idXDeca' => $this->get_Iddeca(),
            'decade' => $this->get_Decade(),
            'year' => $this->get_Year(),
        ];
    }
}
