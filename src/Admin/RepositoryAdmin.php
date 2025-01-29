<?php

namespace App\Admin;

use App\ValueObject\IssueStatus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RepositoryAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_repository';

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
            ->add('name', TextType::class, [
                'label' => 'admin.name',
                'required' => true,
            ])
            ->add('gitlabId', null, [
                'label' => 'admin.gitlabId',
                'required' => false,
            ])
            ->add('project', null, [
                'label' => 'admin.project',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'admin.description',
                'required' => false,
            ])

        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, ['label' => 'admin.name'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('project', null, ['label' => 'admin.project'])
            ->add('gitlabId', null, ['label' => 'admin.gitlabId'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('createdAt', null, ['label' => 'admin.createdAt'])
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
            ->add('project', null, ['label' => 'admin.project'])
            ->add('gitlabId', null, ['label' => 'admin.gitlabId'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('description', null, ['label' => 'admin.description'])
            ->add('createdAt', null, ['label' => 'admin.createdAt'])
        ;
    }
}
