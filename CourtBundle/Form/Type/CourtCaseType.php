<?php

namespace Make\CourtBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Make\CourtBundle\Entity\CaseStatus;
use Make\DepartmentBundle\Entity\Department;
use Make\OrdersBundle\Entity\InsurerPosition;
use Make\OrdersBundle\Entity\ServiceOrder;
use Make\WorkersBundle\Entity\Worker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CourtCaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'order',
                SearchType::class,
                [
                    'label' => 'make.court_case.order',
                    'required' => true,
                    'attr' => [
                        'readonly' => true,
                        'class' => 'serviceOrder'
                    ],
                    'searcher' => 'WarehouseDocumentServiceOrderRW',
                    'searchService' => 'make_search_warehouse_document_rw',
                    'class' => ServiceOrder::class,
                    'callback' => 'setDepartmentFromOrder(this.value)',
                    'reverse_path' => 'id',
                    'repository' => 'make.repository.service_order',
                ]
            )
            ->add(
                'insurerPosition',
                SearchType::class,
                [
                    'label' => 'make.court_case.insurerPosition',
                    'required' => true,
                    'attr' => [
                        'readonly' => true,
                    ],
                    'searcher' => 'InsurerPosition',
                    'searchService' => 'make_search_insurer_position',
                    'class' => InsurerPosition::class,
                    'callback' => 'setServiceOrder(this.value)',
                    'reverse_path' => 'id',
                    'repository' => 'make.repository.insurer_position',
                ]
            )
            ->add(
                'notices',
                TextareaType::class,
                [
                    'label' => 'make.court_case.notices',
                    'required' => false,
                ]
            )
            ->add(
                'signature',
                TextType::class,
                [
                    'label' => 'make.court_case.signature',
                    'required' => false,
                ]
            )
            ->add(
                'court',
                TextType::class,
                [
                    'label' => 'make.court_case.court',
                    'required' => false,
                ]
            )
            ->add(
                'lawyer',
                TextType::class,
                [
                    'label' => 'make.court_case.lawyer',
                    'required' => false,
                ]
            )
            ->add(
                'caseValue',
                NumberType::class,
                [
                    'label' => 'make.court_case.caseValue'
                ]
            )
            ->add(
                'department',
                EntityType::class,
                [
                    'label' => 'make.ui.department',
                    'class' => Department::class,
                    'required' => true,
                    'attr'  =>
                        [ 'class' => 'court-department']
                ])
            ->add(
                'status',
                EntityType::class,
                [
                    'label' => 'make.court_case.caseStatus',
                    'class' => CaseStatus::class,
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.sort', 'ASC');
                    },
                ]
            )
            ->add(
                'faultsDate',
                DateType::class,
                [
                    'label' => 'make.court_case.faultsDate',
                    'widget' => 'single_text',
                    'required' => true,
                    'attr' => [
                        'class' => 'date-picker court-faults-date'
                    ]
                ]
            )
            ->add(
                'faultsReceivedDate',
                DateType::class,
                [
                    'label' => 'make.court_case.faultsReceivedDate',
                    'widget' => 'single_text',
                    'required' => false,
                ]
            )
            ->add(
                'appealDate',
                DateType::class,
                [
                    'label' => 'make.court_case.appealDate',
                    'widget' => 'single_text',
                    'required' => false,
                ]
            )
            ->add(
                'caseDate',
                DateType::class,
                [
                    'label' => 'make.court_case.caseDate',
                    'widget' => 'single_text',
                    'required' => false,
                ]
            )
            ->add(
                'worker',
                EntityType::class,
                [
                    'label' => 'name.worker',
                    'required' => false,
                    'class' => Worker::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('w')
                            ->where('w.enabled = 1')
                            ->orderBy('w.lastName', 'ASC')
                            ->addOrderBy('w.firstName', 'ASC');
                    },
                ]
            );
    }

    public function getBlockPrefix()
    {
        return 'make_case_status';
    }
}
