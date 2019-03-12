<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-11-29
 * Time: 09:44
 */

declare(strict_types=1); // strict mode

namespace priv\php\classes\business;

class Geneology implements \JsonSerializable
{
    private $m_idgen;
    private $m_name;
    private $m_index;

    /**
     * @param mixed $m_idgen
     */
    public function set_Idgen($m_idgen): void
    {
        $this->m_idgen = $m_idgen;
    }

    /**
     * @return mixed
     */
    public function get_Idgen()
    {
        return $this->m_idgen;
    }

    /**
     * @param mixed $m_namel
     */
    public function set_Name($m_name): void
    {
        $this->m_name = utf8_encode($m_name);
    }

    /**
     * @return mixed
     */
    public function get_Name()
    {
        return $this->m_name;
    }

    /**
     * @param mixed $m_index
     */
    public function set_Index($m_index): void
    {
        $this->m_index = $m_index;
    }

    /**
     * @return mixed
     */
    public function get_Index()
    {
        return $this->m_index;
    }

    public function jsonSerialize()
    {
        return [
            'idgen' => $this->get_Idgen(),
            'name' => $this->get_Name(),
            'index' => $this->get_Index()
        ];
    }
}