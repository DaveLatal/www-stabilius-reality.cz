<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

#[ORM\Entity]
#[ORM\Table(name: 'admin_user')]
class SonataAdminUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id = null;

    #[ORM\Column(type: 'json')]
    protected array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }
}

