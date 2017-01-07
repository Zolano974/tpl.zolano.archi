<?php

namespace FirstBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workset
 *
 * @ORM\Table(name="workset")
 * @ORM\Entity(repositoryClass="FirstBundle\Repository\WorksetRepository")
 */
class Workset
{

    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="generic", type="boolean")
     */
    private $generic;
    
    /**
     * @ORM\OneToMany(targetEntity="FirstBundle\Entity\Field", mappedBy="workset")
     */    
    private $fields;    


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Workset
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Workset
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set generic
     *
     * @param integer $generic
     * @return Workset
     */
    public function setGeneric($generic)
    {
        $this->generic = $generic;

        return $this;
    }

    /**
     * Get generic
     *
     * @return integer 
     */
    public function getGeneric()
    {
        return $this->generic;
    }
    
    function getFields() {
        return $this->fields;
    }

    function setFields($fields) {
        $this->fields = $fields;
    }


    
}
