<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-17
 * Time: 13:20
 */

class authors implements JsonSerializable
{
    private $m_idaut;
    private $m_firstname;
    private $m_lastname;
    private $m_prefix;

    public function __construct($idaut, $firstname, $lastname, $prefix)
    {
        $this->m_idaut = $idaut;
        $this->m_firstname = utf8_encode($firstname);
        $this->m_lastname = utf8_encode($lastname);
        $this->m_prefix = utf8_encode($prefix);
    }

    /**
     * @return mixed
     */
    public function get_Idaut()
    {
        return $this->m_idaut;
    }

    /**
     * @return mixed
     */
    public function get_Firstname()
    {
        return $this->m_firstname;
    }

    /**
     * @return mixed
     */
    public function get_Lastname()
    {
        return $this->m_lastname;
    }

    /**
     * @return mixed
     */
    public function get_Prefix()
    {
        return $this->m_prefix;
    }

    public function jsonSerialize()
    {
        return [
            'idaut' => $this->get_Idaut(),
            'firstName' => $this->get_Firstname(),
            'lastName' => $this->get_Lastname(),
            'prefix' => $this->get_Prefix(),
        ];
    }

}

