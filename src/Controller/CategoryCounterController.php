<?php

namespace App\Controller;

use App\DTO\CategoryCountingsDTO;
use App\Repository\PropertyFetcherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryCounterController extends AbstractController
{
    private PropertyFetcherRepository $propertyFetcherRepository;

    public function __construct(
        PropertyFetcherRepository $propertyFetcherRepository
    )
    {
        $this->propertyFetcherRepository =$propertyFetcherRepository;
    }



}
