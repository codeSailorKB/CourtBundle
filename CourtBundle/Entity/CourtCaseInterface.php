<?php

namespace Make\CourtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Make\DepartmentBundle\Entity\Department;
use Make\OrdersBundle\Entity\InsurerPosition;
use Make\OrdersBundle\Entity\ServiceOrder;
use Make\WorkersBundle\Entity\Worker;
use Make\CoreBundle\Entity\BlameableInterface;
use Make\DepartmentBundle\Entity\DepartmentAwareInterface;
use Make\CoreBundle\Entity\TimestampableInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Make\PrintBundle\Entity\PrintSubjectInterface;

interface CourtCaseInterface  extends ResourceInterface, TimestampableInterface, BlameableInterface, PrintSubjectInterface, DepartmentAwareInterface
{
    public function getId();

    public function setCaseValue($caseValue);

    public function getCaseValue();

    public function setFaultsDate($faultsDate);

    public function getFaultsDate();

    public function setFaultsReceivedDate($faultsReceivedDate);

    public function getFaultsReceivedDate();

    public function setAppealDate($appealDate);

    public function getAppealDate();

    public function setCaseDate($caseDate);

    public function getCaseDate();

    public function setSignature($signature);

    public function getSignature();

    public function setLawyer($lawyer);

    public function getLawyer();

    public function setCourt($court);

    public function getCourt();

    public function setNotices($notices);

    public function getNotices();

    public function setStatus(CaseStatus $status = null);

    public function getStatus();

    public function setDepartment(Department $department = null);

    public function getDepartment();

    public function setOrder(ServiceOrder $order = null);

    public function getOrder();

    public function setWorker(Worker $worker = null);

    public function getWorker();

    public function setInsurerPosition(InsurerPosition $insurerPosition = null);

    public function getInsurerPosition();
}
