<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
     /**
     * @var type Team
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="tasks")
     */
    protected $team;
    
    /**
     *@var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="task", cascade={"remove"})
     */
    private $comments;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="creationDate", type="date")
     */
    private $creationDate;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="deadline", type="date")
     */
    private $deadline;
    
    /**
     *
     * @var type string
     * 
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;
    
    /**
     *
     * @var type boolean
     * 
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = date_create();
        $this->active = true;
    }
    
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
     * Set title
     *
     * @param string $title
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
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
     * Set team
     *
     * @param \AppBundle\Entity\Team $team
     * @return Task
     */
    public function setTeam(\AppBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \AppBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Add comments
     *
     * @param \AppBundle\Entity\Comment $comment
     * @return Task
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Task
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        if($priority == "low" || $priority == "normal" || $priority == "high"){
                    $this->priority = $priority;

                    return $this;
        }

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Activate
     *
     * @return Task
     */
    public function activate()
    {
        $this->active = true;

        return $this;
    }
    
     /**
     * Deactivate
     *
     * @return Task
     */
    public function deactivate()
    {
        $this->active = false;

        return $this;
    }

    /**
     * Is active
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->active;
    }
}
