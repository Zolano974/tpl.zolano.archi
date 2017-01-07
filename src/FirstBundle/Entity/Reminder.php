<?php

namespace FirstBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tour
 *
 * @ORM\Table(name="reminder")
 * @ORM\Entity(repositoryClass="FirstBundle\Repository\ReminderRepository")
 */
class Reminder
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="workset_id", type="integer")
     */
    private $worksetId;


    /**
     * @var string
     *
     * @ORM\Column(name="xcoord", type="string", length=1)
     */
    private $xCoord;

    /**
     * @var int
     *
     * @ORM\Column(name="ycoord", type="integer")
     */
    private $yCoord;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=2000)
     */
    private $text;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Reminder
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set worksetId
     *
     * @param integer $worksetId
     *
     * @return Reminder
     */
    public function setWorksetId($worksetId)
    {
        $this->worksetId = $worksetId;

        return $this;
    }

    /**
     * Get worksetId
     *
     * @return int
     */
    public function getWorksetId()
    {
        return $this->worksetId;
    }

    /**
     * Set xCoord
     *
     * @param string $xCoord
     *
     * @return Reminder
     */
    public function setXCoord($xCoord)
    {
        $this->xCoord = $xCoord;

        return $this;
    }

    /**
     * Get xCoord
     *
     * @return string
     */
    public function getXCoord()
    {
        return $this->xCoord;
    }

    /**
     * Set yCoord
     *
     * @param integer $yCoord
     *
     * @return Reminder
     */
    public function setYCoord($yCoord)
    {
        $this->yCoord = $yCoord;

        return $this;
    }

    /**
     * Get yCoord
     *
     * @return int
     */
    public function getYCoord()
    {
        return $this->yCoord;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Reminder
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}

