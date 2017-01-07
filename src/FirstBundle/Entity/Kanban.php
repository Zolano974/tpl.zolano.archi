<?php

namespace FirstBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Field
 *
 * @ORM\Table(name="kanban_item_step")
 * @ORM\Entity(repositoryClass="FirstBundle\Repository\KanbanRepository")
 */
class Kanban
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
     * @var int
     *
     * @ORM\Column(name="item_id", type="integer")
     */
    private $item_id;

    /**
     * @var int
     *
     * @ORM\Column(name="iteration", type="integer")
     */
    private $iteration;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;

    /**
     * @var int
     *
     * @ORM\Column(name="step", type="integer")
     */
    private $step;
    
    
    function getId() {
        return $this->id;
    }

    function getItem_id() {
        return $this->item_id;
    }

    function getIteration() {
        return $this->iteration;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getStep() {
        return $this->step;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setItem_id($item_id) {
        $this->item_id = $item_id;
    }

    function setIteration($iteration) {
        $this->iteration = $iteration;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setStep($step) {
        $this->step = $step;
    }


    
}
