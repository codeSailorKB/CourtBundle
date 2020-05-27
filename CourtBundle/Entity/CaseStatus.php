<?php

namespace Make\CourtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Make\CoreBundle\Entity\BlameableTrait;
use Make\CoreBundle\Entity\TimestampableTrait;

class CaseStatus implements CaseStatusInterface
{
    use TimestampableTrait,
        BlameableTrait;

    private $id;

    private $name;

    protected $sort;

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * @var float
     */

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * {@inheritdoc}
     */
    public function getSort()
    {
        return $this->sort;
    }
}
