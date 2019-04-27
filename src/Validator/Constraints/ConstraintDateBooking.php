<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintDateBooking extends Constraint
{
public $message = 'La date de réservation {{ date }} n\'est pas au bon format. La date doit être au format dd/mm/YYYY';

public $message2 = 'Vous ne pouvez pas réserver le mardi et le dimanche';

public $message3 = 'Vous ne pouvez pas réserver les jours fériés';
}