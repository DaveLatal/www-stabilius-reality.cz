<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class AdminAdminUser extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('username', TextType::class);
        $form->add('password', PasswordType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {

        $filter->add('username');
        $filter->add('email');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('username');
        $list->addIdentifier('email');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('username');
        $show->add('email');
    }
}
