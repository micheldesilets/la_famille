<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-09-08
 * Time: 07:24
 */

namespace priv\php\classes\business;

class Folders implements \JsonSerializable
{
    private $m_idpfo;
    private $m_idrpt;
    private $m_idtyp;
    private $m_member;
    private $m_decade;
    private $m_year;
    private $m_title;
    private $m_levels;

    public function setIdpfo($m_idpfo): void
    {
        $this->m_idpfo = $m_idpfo;
    }

    public function getIdpfo()
    {
        return $this->m_idpfo;
    }

    public function setFolderId($m_idrpt): void
    {
        $this->m_idrpt = $m_idrpt;
    }

    public function getFolderId()
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

    public function setMember($m_member): void
    {
        $this->m_member = utf8_encode($m_member);
    }

    public function getMember()
    {
        return $this->m_member;
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

    /**
     * @param mixed $m_levels
     */
    public function setLevels($m_levels): void
    {
        $this->m_levels = $m_levels;
    }

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return $this->m_levels;
    }

    public function jsonSerialize()
    {
        return [
            'folderId' => $this->getIdpfo(),
            'folder' => $this->getFolderId(),
            'type' => $this->getTypeId(),
            'member' => $this->getMember(),
            'decade' => $this->getDecade(),
            'year' => $this->getYear(),
            'title' => $this->getTitle(),
            'levels' => $this->getLevels()
        ];
    }
}
