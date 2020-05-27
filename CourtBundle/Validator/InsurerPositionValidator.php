<?php

namespace Make\CourtBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class InsurerPositionValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($document, Constraint $constraint)
    {
        if ($document->getOrder() != null && $document->getInsurerPosition() && $document->getOrder()->getId() != $document->getInsurerPosition()->getServiceOrder()->getId()) {
            return $this->context->addViolation('Wybrana szkoda nie dotyczy wybranego zlecenia.');
        }
    }
}
