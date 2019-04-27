<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintBirthDate extends Constraint
{
    public $message = 'La date de naissance {{ date }} n\'est pas au bon format. La date doit être au format dd/mm/YYYY';
}