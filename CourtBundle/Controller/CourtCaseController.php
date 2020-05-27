<?php

namespace Make\CourtBundle\Controller;

use FOS\RestBundle\View\View;
use Make\CoreBundle\Controller\ResourceController;
use Make\CourtBundle\Form\Type\CaseExportType;
use Make\UploadBundle\Entity\UploadedFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CourtCaseController extends ResourceController
{
    public function indexAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        $form = $request->request->get('make_court_caseexport');
        $exportType = $form['exportType'];

        if ($configuration->getParameters()->get('filter_form') != null) {
            $filterForm = $this->get('form.factory')->create($configuration->getParameters()->get('filter_form'));
        }

        if ($request->query->has('make_' . $this->metadata->getName() . "_filter") || $request->query->has($this->metadata->getName() . "_filter")) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        } else {
            $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        }

        $view = View::create($resources);
        $exportForm = $this->createForm(CaseExportType::class);

        if (!empty($form)) {
            $exportEol = ';EOL';

            $parameters['export_cols_set'] = $form;
            $parameters['export_eol'] = $exportEol;
            $parameters['entities'] = $resources;

            $parameters['export_cols_available'] = [
                'signature',
                'order',
                'insurerPosition',
                'insurer',
                'faults_kind',
                'worker',
                'appealDate',
                'caseDate',
                'caseValue',
                'caseStatus',
                'court',
            ];

            $parameters['exportForm'] = $exportForm->createView();

            if ($exportType == 'csv') {
                $response = $this->createListCsv($parameters, $exportEol);
            } else {
                $response = $this->createListXls($parameters);
            }

            return $response;
        }

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX . '.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    'exportForm' => $exportForm->createView(),
                    $this->metadata->getPluralName() => $resources,
                    'filterForm' => (isset($filterForm)) ? $filterForm->createView() : null,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    private function createListCsv(array $parameters, $exportEol)
    {
        $content = $this->render('MakeCourtBundle:CourtCase/export:csv.html.twig', $parameters);
        $content = $content->getContent();
        $content = preg_replace('/\s{2,}/', '', $content);
        $content = str_replace($exportEol, "\n", $content);

        $date = new \DateTime();
        $filename = sprintf("faktury_%d.csv", $date->format('YmdHi'));

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set("Content-Disposition", "attachment; filename=" . $filename);

        return $response;
    }

    private function createListXls(array $parameters)
    {
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();

        $columnIndex = 1;
        $rowIndex = 1;
        foreach ($parameters['exportForm']->children as $child) {
            if (isset($parameters['export_cols_set'][$child->vars['name']])) {
                $sheet->setCellValue(Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex, $this->get('translator')->trans($child->vars['label']));
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex))->setAutoSize(true);
                $columnIndex++;
            }
        }

        $columnIndex = 1;
        $rowIndex++;

        foreach ($parameters['entities'] as $case) {
            foreach ($parameters['exportForm']->children as $child) {
                if (in_array($child->vars['name'], $parameters['export_cols_available']) && isset($parameters['export_cols_set'][$child->vars['name']])) {

                    $numberFormat = NumberFormat::FORMAT_TEXT;
                    $value = '';

                    switch ($child->vars['name']) {
                        case 'signature':
                            $value = $case->getSignature();
                            break;
                        case 'insurerPosition':

                            if ($case->getInsurerPosition() != null) {
                                $value = $case->getInsurerPosition()->getFaultNumber();
                            }
                            break;
                        case 'insurer':

                            if ($case->getInsurerPosition() != null && $case->getInsurerPosition()->getInsurer()) {
                                $value = $case->getInsurerPosition()->getInsurer()->getName();
                            }
                            break;
                        case 'faults_kind':

                            if ($case->getInsurerPosition() != null && $case->getInsurerPosition()->getFaultKind()) {
                                $value = $case->getInsurerPosition()->getFaultKind()->getName();
                            }
                            break;
                        case 'order':

                            if ($case->getOrder() != null) {
                                $value = $case->getOrder()->getNumber();
                            }
                            break;
                        case 'appealDate':
                            if ($case->getAppealDate() != null) {
                                $value = Date::dateTimeToExcel($case->getAppealDate());
                                $numberFormat = 'dd-mm-yyyy';
                            }
                            break;
                        case 'caseDate':
                            if ($case->getCaseDate() != null) {
                                $value = Date::dateTimeToExcel($case->getCaseDate());
                                $numberFormat = 'dd-mm-yyyy';
                            }
                            break;
                        case 'worker':
                            if ($case->getWorker()) {
                                $value = $case->getWorker()->getName();
                            }
                            break;
                        case 'caseValue':
                            $value = number_format($case->getCaseValue(), 2, '.', '');

                            break;
                        case 'caseStatus':
                            if ($case->getStatus() != null) {
                                $value = $case->getStatus()->getName();
                            }
                            break;
                        case 'court':
                            $value = $case->getCourt();
                            break;
                    }

                    $sheet->setCellValue(Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex, $value);
                    $sheet->getStyle(Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex)->getNumberFormat()->setFormatCode($numberFormat);

                    $columnIndex++;
                }
            }
            $columnIndex = 1;
            $rowIndex++;
        }

        $date = new \DateTime();
        $filename = sprintf("sprawy_sadowe_%d.xlsx", $date->format('YmdHi'));

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
        die;
    }

    public function showAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->findOr404($configuration);

        $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);

        $view = View::create($resource);

        $order = null;

        if ($resource->getOrder() != null) {
            $order = $resource->getOrder();
        } elseif ($resource->getInsurerPosition() != null) {
            $order = $resource->getInsurerPosition()->getServiceOrder();
        }

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::SHOW . '.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resource' => $resource,
                    'eventKinds' => $this->get('make.repository.event_kind')->findAll(),
                    'events' => ($order != null) ? $this->get('make.repository.event')->findBy(['serviceOrder' => $order], ['startDate' => 'ASC']) : [],
                    'files' => $this->get('doctrine.orm.entity_manager')->getRepository(UploadedFile::class)->findBy(['targetName' => 'court_case', 'targetId' => $resource->getId()]),
                    $this->metadata->getName() => $resource,
                ]);
        }

        return $this->viewHandler->handle($configuration, $view);
    }
}
