<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019-01-29
 * Time: 10:34
 */

namespace priv\php\classes\business;

class FolderLevels implements \JsonSerializable
{
    private $id;
    private $idParent;
    private $name;

    public function __construct($id, $idParent, $name)
    {
        $this->id = $id;
        $this->idParent = $idParent;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdParent()
    {
        return $this->idParent;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'idParent' => $this->getIdParent(),
            'name' => $this->getName()
        ];
    }
}