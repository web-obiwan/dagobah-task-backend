<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_user';

    /**
     * @param RouteCollectionInterface $collection
     */
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('delete');
    }

    /**
     * @param FormMapper $form
     * @return void
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('username', TextType::class, [
                'label' => 'admin.username',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'admin.email',
                'required' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'admin.password',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'admin.enabled'
            ])
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username', null, ['label' => 'admin.username'])
            ->add('email', null, ['label' => 'admin.email'])
            ->add('enabled', null, ['label' => 'admin.enabled'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('username', null, ['label' => 'admin.username'])
            ->add('email', null, ['label' => 'admin.email'])
            ->add('roles', null, ['label' => 'admin.roles'])
            ->add('enabled', null, ['editable' => true, 'label' => 'admin.enabled'])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ])
        ;
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id', null, ['label' => 'admin.id'])
            ->add('username', null, ['label' => 'admin.username'])
            ->add('email', null, ['label' => 'admin.email'])
            ->add('roles', null, ['label' => 'admin.roles'])
            ->add('enabled', null, ['label' => 'admin.enabled'])
            ->add('password', null, ['label' => 'admin.password'])
        ;
    }
}
