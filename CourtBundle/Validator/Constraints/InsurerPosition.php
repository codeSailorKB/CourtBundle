<?php

namespace Make\CourtBundle\Validator\Constraints;

use Make\CourtBundle\Validator\InsurerPositionValidator;
use Symfony\Component\Validator\Constraint;

class InsurerPosition extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return InsurerPositionValidator::class;
    }
}
