<?php

namespace Make\CourtBundle\Filter\Type;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\NumberRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Make\CourtBundle\Entity\CaseStatus;
use Make\CustomersBundle\Entity\Customer;
use Make\DepartmentBundle\Entity\Department;
use Make\OrdersBundle\Entity\FaultKind;
use Make\OrdersBundle\Form\Type\TextFilterType;
use Make\WorkersBundle\Entity\Worker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourtCaseFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('signature', TextFilterType::class, [
                'label' => 'make.court_case.signature',
                'required' => false,
            ])
            ->add('order',
                TextFilterType::class,
                [
                    'label' => 'make.sale.buyer',
                    'required' => false,
                    'data' => null,
                    'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                        if ($values['value'] == null) {
                            return;
                        }

                        $rootAlias = $filterQuery->getQueryBuilder()->getRootAlias();

                        $expression = $filterQuery->getExpr()->like('order.number', ':number');
                        $parameters = ['number' => '%' . $values['value'] . '%'];
                        $filterQuery->getQueryBuilder()->leftJoin($rootAlias . '.order', 'order');

                        return $filterQuery->createCondition($expression, $parameters);
                    },
                ]
            )
            ->add('faultNumber',
                TextFilterType::class,
                [
                    'label' => 'make.court_case.faults_number',
                    'required' => false,
                    'data' => null,
                    'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                        if ($values['value'] == null) {
                            return;
                        }

                        $rootAlias = $filterQuery->getQueryBuilder()->getRootAlias();

                        $expression = $filterQuery->getExpr()->like('insurer.faultNumber', ':faultNumber');
                        $parameters = ['faultNumber' => '%' . $values['value'] . '%'];
                        $filterQuery->getQueryBuilder()->leftJoin($rootAlias . '.insurerPosition', 'insurer');

                        return $filterQuery->createCondition($expression, $parameters);
                    },
                ])
            ->add('faultsKind', EntityType::class, [
                'label' => 'make.court_case.faults_kind',
                'class' => FaultKind::class,
                'required' => false,
                'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                    if ($values['value'] == null) {
                        return;
                    }

                    $rootAlias = $filterQuery->getQueryBuilder()->getRootAlias();

                    $expression = $filterQuery->getExpr()->eq('faultKind.id', ':faultKind');
                    $parameters = ['faultKind' => $values['value']];
                    $filterQuery->getQueryBuilder()->leftJoin($rootAlias . '.insurerPosition', 'insurer1');
                    $filterQuery->getQueryBuilder()->leftJoin('insurer1.faultKind', 'faultKind');

                    return $filterQuery->createCondition($expression, $parameters);
                },
            ])
            ->add(
                'insurer',
                EntityFilterType::class,
                [
                    'label' => 'make.court_case.insurer',
                    'class' => Customer::class,
                    'required' => false,
                    'mapped' => false,
                    'placeholder' => 'name.empty',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->where('u.insurer = true')
                            ->addOrderBy('u.name', 'ASC');
                    },
                    'attr' => [
                        'class' => '[ chosen-select ]',
                    ],
                    'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                        if ($values['value'] == null) {
                            return;
                        }

                        $rootAlias = $filterQuery->getQueryBuilder()->getRootAlias();

                        $expression = $filterQuery->getExpr()->eq('insurerC.id', ':insurer');
                        $parameters = ['insurer' => $values['value']];
                        $filterQuery->getQueryBuilder()->leftJoin($rootAlias . '.insurerPosition', 'insurerPos');
                        $filterQuery->getQueryBuilder()->leftJoin('insurerPos.insurer', 'insurerC');

                        return $filterQuery->createCondition($expression, $parameters);
                    },
                ]
            )
            ->add(
                'faultsDate',
                DateRangeFilterType::class,
                [
                    'label' => 'make.court_case.faultsDate',
                    'required' => false,
                    'left_date_options' => ['widget' => 'single_text', 'label' => ' '],
                    'right_date_options' => ['widget' => 'single_text', 'label' => ' '],
                ]
            )
            ->add(
                'faultsReceivedDate',
                DateRangeFilterType::class,
                [
                    'label' => 'make.court_case.faultsReceivedDate',
                    'required' => false,
                    'left_date_options' => ['widget' => 'single_text', 'label' => ' '],
                    'right_date_options' => ['widget' => 'single_text', 'label' => ' '],
                ]
            )
            ->add(
                'appealDate',
                DateRangeFilterType::class,
                [
                    'label' => 'make.court_case.appealDate',
                    'required' => false,
                    'left_date_options' => ['widget' => 'single_text', 'label' => ' '],
                    'right_date_options' => ['widget' => 'single_text', 'label' => ' '],
                ]
            )
            ->add(
                'caseDate',
                DateRangeFilterType::class,
                [
                    'label' => 'make.court_case.caseDate',
                    'required' => false,
                    'left_date_options' => ['widget' => 'single_text', 'label' => ' '],
                    'right_date_options' => ['widget' => 'single_text', 'label' => ' '],
                ]
            )
            ->add('worker', EntityType::class, [
                'label' => 'make.report.label.worker_name',
                'class' => Worker::class,
                'attr' => [
                    'class' => '[ insurer chosen-select ]',
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('w')
                        ->where('w.enabled = 1')
                        ->orderBy('w.lastName', 'ASC')
                        ->addOrderBy('w.firstName', 'ASC');
                },
                'placeholder' => 'make.ui.choose',
                'required' => false,
            ])
            ->add('status', EntityFilterType::class, [
                'label' => 'make.court_case.caseStatus',
                'class' => CaseStatus::class,
                'required' => false,
            ])
            ->add('department', EntityType::class, [
                'label' => 'make.report.label.department_name',
                'class' => Department::class,
                'placeholder' => 'make.ui.choose',
                'required' => false,
            ])
            ->add('notices', TextFilterType::class, [
                'label' => 'make.court_case.notices',
                'required' => false,
            ])
            ->add(
                'caseValue',
                NumberRangeFilterType::class,
                [
                    'label' => false,
                    'required' => false,
                    'left_number_options' => [
                        'condition_operator' => FilterOperands::OPERATOR_GREATER_THAN_EQUAL,
                        'label' => 'make.payment.fields.amountFrom',
                    ],
                    'right_number_options' => [
                        'condition_operator' => FilterOperands::OPERATOR_LOWER_THAN_EQUAL,
                        'label' => 'make.payment.fields.amountTo',
                    ],
                ]
            )
        ;
    }

    public function getBlockPrefix()
    {
        return 'court_case_filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'validation_groups' => ['filtering'] // avoid NotBlank() constraint-related message
        ]);
    }

}
