<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;
#[ORM\Entity]
class SonataAdminUser extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;
}


//namespace App\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//use Sonata\UserBundle\Entity\BaseUser;
//
//#[ORM\Entity]
//class SonataAdminUser extends BaseUser
//{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    protected $id = null;
//
//    #[ORM\Column]
//    protected ?string $username = null;
//
//    #[ORM\Column]
//    protected ?string $password = null;
//
//    #[ORM\Column]
//    protected ?string $email = null;
//
//    #[ORM\Column]
//    protected array $roles = [];
//
//}
//
