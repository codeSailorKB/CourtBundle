<?php

namespace Make\CourtBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

class CaseExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('signature', CheckboxType::class, [
                'label' => 'make.court_case.signature',
                'required' => false,
                'data' => true,
            ])
            ->add('order', CheckboxType::class, [
                'label' => 'make.court_case.order',
                'required' => false,
                'data' => true,
            ])
            ->add('insurerPosition', CheckboxType::class, [
                'label' => 'make.court_case.insurerPosition',
                'required' => false,
                'data' => true,
            ])
            ->add('insurer', CheckboxType::class, [
                'label' => 'make.court_case.insurer',
                'required' => false,
                'data' => true,
            ])
            ->add('faults_kind', CheckboxType::class, [
                'label' => 'make.court_case.faults_kind',
                'required' => false,
                'data' => true,
            ])
            ->add('worker', CheckboxType::class, [
                'label' => 'make.court_case.worker',
                'required' => false,
                'data' => true,
            ])
            ->add('appealDate', CheckboxType::class, [
                'label' => 'make.court_case.appealDate',
                'required' => false,
                'data' => true,
            ])
            ->add('caseDate', CheckboxType::class, [
                'label' => 'make.court_case.caseDate',
                'required' => false,
                'data' => true,
            ])
            ->add('caseValue', CheckboxType::class, [
                'label' => 'make.court_case.caseValue',
                'required' => false,
                'data' => true,
            ])
            ->add('caseStatus', CheckboxType::class, [
                'label' => 'make.court_case.caseStatus',
                'required' => false,
                'data' => true,
            ])
            ->add('court', CheckboxType::class, [
                'label' => 'make.court_case.court',
                'required' => false,
                'data' => true,
            ])
            ->add('exportType', HiddenType::class, [
                'label' => '',
                'required' => true,
                'data' => true,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'make_court_caseexport';
    }
}
