<?php

namespace Make\CourtBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Make\CoreBundle\Entity\BlameableInterface;
use Make\CoreBundle\Entity\TimestampableInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CaseStatusInterface extends ResourceInterface, TimestampableInterface, BlameableInterface
{
    public function getId();

    public function getName();

    public function setName($name);

    public function setSort($sort);

    public function getSort();
}
