<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var type Team
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="users")
     */
    protected $team;
    

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles=["ROLE_USER"];
        
    }
    
    

    /**
     * Set team
     *
     * @param \AppBundle\Entity\Team $team
     * @return User
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
}
