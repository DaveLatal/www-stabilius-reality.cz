<?php

namespace App\Controller;

use App\Entity\SonataAdminUser;
use App\Repository\SonataAdminUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController4 extends AbstractController
{

    private SonataAdminUserRepository $sonataAdminUserRepository;
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(
        SonataAdminUserRepository $sonataAdminUserRepository,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->sonataAdminUserRepository = $sonataAdminUserRepository;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/create-first-admin', name: 'createadminuser_o')]
    public function createadminuser_o(): JsonResponse
    {
        $userCreated = false;

        if ($this->sonataAdminUserRepository->count([]) === 0) {

            $user = new SonataAdminUser();

            $user->setUsername('admin');
            $user->setUsernameCanonical('admin');
            $user->setEmail('admin@admin.com');
            $user->setEmailCanonical('admin@admin.com');
            $user->setEnabled(true);
            $user->setRoles(['ROLE_SUPER_ADMIN']);

            $hashed = $this->passwordHasher->hashPassword($user, 'Password123');
            $user->setPassword($hashed);

            $this->sonataAdminUserRepository->save($user, true);

            $userCreated = true;
        }else{
            $userOld = $this->sonataAdminUserRepository->findOneBy(['id' => 1]);

            $userOld->setEmail('admin@admin.com');
            $userOld->setEmailCanonical('admin@admin.com');
            $userOld->setEnabled(true);
            $userOld->setRoles(['ROLE_SUPER_ADMIN']);

            $hashed = $this->passwordHasher->hashPassword($userOld, 'Password123');
            $userOld->setPassword($hashed);

            $this->sonataAdminUserRepository->save($userOld, true);
        }

        return new JsonResponse(['userCreated' => $userCreated]);
    }

    #[Route('/generate-admin-pw', name: 'gntPwd_o')]
    public function generateAdminPW(): JsonResponse
    {

        if ($this->sonataAdminUserRepository->count([]) === 0) {

            $userOld = $this->sonataAdminUserRepository->findOneBy(['id' => 1]);
            $hashed = $this->passwordHasher->hashPassword($userOld, 'Password123');
            //$salt = $this->passwordHasher->hashPassword($userOld, 'Password123');

            $this->sonataAdminUserRepository->save($userOld, true);
            return new JsonResponse(['password' => $hashed,'salt' => ""]);
        }else{
            return new JsonResponse(['error' => "user not found"]);
        }
    }
}
