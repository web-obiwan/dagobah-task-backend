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

class IssueAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'sonata_admin_issue';

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
            ->with('infos', ['class' => 'col-md-6'])
            ->add('name', TextType::class, [
                'label' => 'admin.name',
                'required' => true,
            ])
            ->add('project', null, [
                'label' => 'admin.project',
                'required' => true,
            ])
            ->add('repositories', null, [
                'label' => 'admin.repositories',
                'required' => false,
            ])
            ->add(
                'status',
                ChoiceType::class,
                [
                    'label' => 'admin.status',
                    'choices' => IssueStatus::getStatusChoices(),
                ]
            )
            ->add('priority', null, [
                'label' => 'admin.priority',
                'required' => true,
            ])

            ->add('labels', null, [
                'label' => 'admin.labels',
                'required' => false,
            ])
            ->add('storyPoint', TextType::class, [
                'label' => 'admin.storyPoint',
                'required' => false,
            ])
            ->add('is_archived', CheckboxType::class, [
                'label' => 'admin.isArchived',
                'required' => false,
            ])
            ->end()
            ->with('Users', ['class' => 'col-md-6'])
            ->add('owner', null, [
                'label' => 'admin.owner',
                'required' => false,
            ])
            ->add('reviewer', null, [
                'label' => 'admin.reviewer',
                'required' => false,
            ])
            ->add('reporter', null, [
                'label' => 'admin.reporter',
                'required' => false,
            ])
            ->end()
            ->with('Dates', ['class' => 'col-md-6'])
            ->add('deadline', null, [
                'label' => 'admin.deadline',
                'required' => false,
            ])
            ->end()
            ->with('Sprint', ['class' => 'col-md-6'])
            ->add('sprint', null, [
                'label' => 'admin.sprint',
                'required' => false,
            ])
            ->end()
            ->add('description', TextareaType::class, [
                'label' => 'admin.description',
                'required' => false,
                'attr' => [
                    'rows' => 10,
                ]
            ])
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, ['label' => 'admin.name'])
            ->add('status', ChoiceFilter::class, [
                'label' => 'admin.status',
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => IssueStatus::getStatusChoices(),
                    'multiple' => false,
                ]
            ])
            ->add('sprint', null, ['label' => 'admin.sprint'])
            ->add('owner', null, ['label' => 'admin.owner'])
            ->add('reviewer', null, ['label' => 'admin.reviewer'])
            ->add('priority', null, ['label' => 'admin.priority'])
        ;
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'admin.id'])
            ->add('sprint', null, ['label' => 'admin.sprint'])
            ->add('project', null, ['label' => 'admin.project'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('owner', null, ['label' => 'admin.owner'])
            ->add('reviewer', null, ['label' => 'admin.reviewer'])
            ->add('status', null, ['label' => 'admin.status'])
            ->add('priority', null, ['label' => 'admin.priority'])
            ->add('createdAt', null, ['label' => 'admin.createdAt'])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'history' => [
                        'template' => 'Admin/Entity/Issue/link.history.html.twig'
                    ],
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
            ->with('infos', ['class' => 'col-md-6'])
            ->add('id', null, ['label' => 'admin.id'])
            ->add('createdAt', null, ['label' => 'admin.createdAt'])
            ->add('name', null, ['label' => 'admin.name'])
            ->add('project', null, ['label' => 'admin.project'])
            ->add('repositories', null, ['label' => 'admin.repositories'])
            ->add('status', null, ['label' => 'admin.status'])
            ->add('priority', null, ['label' => 'admin.priority'])
            ->add('labels', null, ['label' => 'admin.labels'])
            ->end()
            ->with('Users', ['class' => 'col-md-6'])
            ->add('owner', null, ['label' => 'admin.owner'])
            ->add('reviewer', null, ['label' => 'admin.reviewer'])
            ->add('reporter', null, ['label' => 'admin.reviewer'])
            ->end()
            ->add('description', null, ['label' => 'admin.description'])
        ;
    }
}
