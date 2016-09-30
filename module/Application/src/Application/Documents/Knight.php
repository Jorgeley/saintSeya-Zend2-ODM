<?php
namespace Application\Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * this is the class that represent a Zodiac Knight
 * the annotations do the job of mapping the class to mongodb
 *
 * @author Jorgeley <jorgeley@gmail.com>
 */

/** @ODM\Document */
class Knight{
    
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="string") */
    private $armor;

    public function getId(): string{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getArmor(): string{
        return $this->armor;
    }

    public function setId(string $id){
        $this->id = $id;
    }

    public function setName(string $name){
        $this->name = $name;
    }

    public function setArmor(string $armor){
        $this->armor = $armor;
    }
}