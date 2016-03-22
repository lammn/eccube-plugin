<?php

namespace Plugin\ProductLimit\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wrapping
 */
class ProductLimit extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $num;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param int $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }





}
