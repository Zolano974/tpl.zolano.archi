<?php

namespace FirstBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Field
 *
 * @ORM\Table(name="field")
 * @ORM\Entity(repositoryClass="FirstBundle\Repository\FieldRepository")
 */
class Field
{
    
    /**
     * @ORM\ManyToOne(targetEntity="FirstBundle\Entity\Workset", inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */    
    private $workset;    
    
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
     * @ORM\Column(name="color", type="string", length=7)
     */
    private $color;
    
    /**
     * @ORM\OneToMany(targetEntity="FirstBundle\Entity\Item", mappedBy="field", cascade={"remove"})
     */      
    private $items;

    /**
     * @var int
     *
     * @ORM\Column(name="order_custom", type="integer")
     */
    private $order;    

    
    

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
     * @return Field
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
     * Set color
     *
     * @param string $color
     * @return Field
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set Workset
     *
     * @param string FirstBundle\Entity\Workset $workset
     * @return Field
     */
    public function setWorkset($workset)
    {
        $this->workset = $workset;

        return $this;
    }

    /**
     * Get color
     *
     * @return FirstBundle\Entity\Workset 
     */
    public function getWorkset()
    {
        return $this->workset;
    }
    
    
    function getItems() {
        return $this->items;
    }

    function setItems($items) {
        $this->items = $items;
    }
    
    
    function getOrder() {
        return $this->order;
    }

    function setOrder($order) {
        $this->order = $order;
    }


    
}
