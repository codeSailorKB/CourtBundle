<?php

namespace Make\CourtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Make\CourtBundle\Entity\CaseStatus;
use Make\DepartmentBundle\Entity\Department;
use Make\OrdersBundle\Entity\InsurerPosition;
use Make\OrdersBundle\Entity\ServiceOrder;
use Make\WorkersBundle\Entity\Worker;
use Make\CoreBundle\Entity\BlameableTrait;
use Make\CoreBundle\Entity\TimestampableTrait;

class CourtCase implements CourtCaseInterface
{
    use TimestampableTrait,
        BlameableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var float
     */
    private $caseValue;

    /**
     * @var \DateTime
     */
    private $faultsDate;

    /**
     * @var \DateTime
     */
    private $faultsReceivedDate;

    /**
     * @var \DateTime
     */
    private $appealDate;

    /**
     * @var \DateTime
     */
    private $caseDate;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $lawyer;

    /**
     * @var string
     */
    private $court;

    /**
     * @var string
     */
    private $notices;

    /**
     * @var CaseStatus
     */
    private $status;

    /**
     * @var Department
     */
    private $department;

    /**
     * @var ServiceOrder
     */
    private $order;

    /**
     * @var Worker
     */
    private $worker;

    /**
     * @var InsurerPosition
     */
    private $insurerPosition;


    public function __toString()
    {
        if ($this->signature == null) {
            return 'sprawa bez sygnatury';
        }

        return $this->signature;
    }


    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->__toString();
    }

    /**
     * Set caseValue
     *
     * @param float $caseValue
     *
     * @return CourtCase
     */
    public function setCaseValue($caseValue)
    {
        $this->caseValue = $caseValue;

        return $this;
    }

    /**
     * Get caseValue
     *
     * @return float
     */
    public function getCaseValue()
    {
        return $this->caseValue;
    }

    /**
     * Set faultsDate
     *
     * @param \DateTime $faultsDate
     *
     * @return CourtCase
     */
    public function setFaultsDate($faultsDate)
    {
        $this->faultsDate = $faultsDate;

        return $this;
    }

    /**
     * Get faultsDate
     *
     * @return \DateTime
     */
    public function getFaultsDate()
    {
        return $this->faultsDate;
    }

    /**
     * Set faultsReceivedDate
     *
     * @param \DateTime $faultsReceivedDate
     *
     * @return CourtCase
     */
    public function setFaultsReceivedDate($faultsReceivedDate)
    {
        $this->faultsReceivedDate = $faultsReceivedDate;

        return $this;
    }

    /**
     * Get faultsReceivedDate
     *
     * @return \DateTime
     */
    public function getFaultsReceivedDate()
    {
        return $this->faultsReceivedDate;
    }

    /**
     * Set appealDate
     *
     * @param \DateTime $appealDate
     *
     * @return CourtCase
     */
    public function setAppealDate($appealDate)
    {
        $this->appealDate = $appealDate;

        return $this;
    }

    /**
     * Get appealDate
     *
     * @return \DateTime
     */
    public function getAppealDate()
    {
        return $this->appealDate;
    }

    /**
     * Set caseDate
     *
     * @param \DateTime $caseDate
     *
     * @return CourtCase
     */
    public function setCaseDate($caseDate)
    {
        $this->caseDate = $caseDate;

        return $this;
    }

    /**
     * Get caseDate
     *
     * @return \DateTime
     */
    public function getCaseDate()
    {
        return $this->caseDate;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return CourtCase
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set lawyer
     *
     * @param string $lawyer
     *
     * @return CourtCase
     */
    public function setLawyer($lawyer)
    {
        $this->lawyer = $lawyer;

        return $this;
    }

    /**
     * Get lawyer
     *
     * @return string
     */
    public function getLawyer()
    {
        return $this->lawyer;
    }

    /**
     * Set court
     *
     * @param string $court
     *
     * @return CourtCase
     */
    public function setCourt($court)
    {
        $this->court = $court;

        return $this;
    }

    /**
     * Get court
     *
     * @return string
     */
    public function getCourt()
    {
        return $this->court;
    }

    /**
     * Set notices
     *
     * @param string $notices
     *
     * @return CourtCase
     */
    public function setNotices($notices)
    {
        $this->notices = $notices;

        return $this;
    }

    /**
     * Get notices
     *
     * @return string
     */
    public function getNotices()
    {
        return $this->notices;
    }

    /**
     * Set status
     *
     * @param CaseStatus $status
     *
     * @return CourtCase
     */
    public function setStatus(CaseStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return CaseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set department
     *
     * @param Department $department
     *
     * @return CourtCase
     */
    public function setDepartment(Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set order
     *
     * @param ServiceOrder $order
     *
     * @return CourtCase
     */
    public function setOrder(ServiceOrder $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return ServiceOrder
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Get order number
     *
     * @return string
     */
    public function getOrderNumber()
    {
        if ($this->order != null) {
            return $this->order->getNumber();
        }

        return '';
    }
    /**
     * Get faults number
     *
     * @return string
     */
    public function getFaultsNumber()
    {
        if ($this->insurerPosition != null) {
            return $this->insurerPosition->getFaultNumber();
        }

        return '';
    }

    /**
     * Get faults kind
     *
     * @return string
     */
    public function getFaultsKind()
    {
        if ($this->insurerPosition != null && $this->insurerPosition->getFaultKind() != null) {
            return $this->insurerPosition->getFaultKind()->getName();
        }

        return '';
    }

    /**
     * Set worker
     *
     * @param Worker $worker
     *
     * @return CourtCase
     */
    public function setWorker(Worker $worker = null)
    {
        $this->worker = $worker;

        return $this;
    }

    /**
     * Get worker
     *
     * @return Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Set insurerPosition
     *
     * @param InsurerPosition $insurerPosition
     *
     * @return CourtCase
     */
    public function setInsurerPosition(InsurerPosition $insurerPosition = null)
    {
        $this->insurerPosition = $insurerPosition;

        return $this;
    }

    /**
     * Get insurerPosition
     *
     * @return InsurerPosition
     */
    public function getInsurerPosition()
    {
        return $this->insurerPosition;
    }
}
