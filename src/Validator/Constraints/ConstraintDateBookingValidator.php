<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Validator\Constraints\ValidateDateFormat;

class ConstraintDateBookingValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ConstraintDateBooking) {
            throw new UnexpectedTypeException($constraint, ConstraintDateBooking::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'date');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        $ValidateDateFormat = new ValidateDateFormat();

        $isValidDate = $ValidateDateFormat->ValidateDateFormat($value);

        if ( $isValidDate == false) 
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $value)
                ->addViolation();
        }
        else {
            $date = \DateTime::createFromFormat('d/m/Y',$value);

            if ( $date->format('D') == 'Tue' || $date->format('D') == 'Sun' )
            {
                $this->context->buildViolation($constraint->message2)
                ->addViolation();
            }
            $DateForbidden = [ '01/05','01/11','25/12','01/01' ];
            $split = explode( '/', $value );

            $DateDayMonts = $split[0].'/'.$split[1];

            if ( in_array($DateDayMonts,$DateForbidden))
            {
                $this->context->buildViolation($constraint->message3)
                ->addViolation();
            }
        }
        
    } 
    
}