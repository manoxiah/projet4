<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Validator\Constraints\ValidateDateFormat;

class ConstraintBirthDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ConstraintBirthDate) 
        {
            throw new UnexpectedTypeException($constraint, ConstraintBirthDate::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) 
        {
            return;
        }

        if (!is_string($value)) 
        {
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
    }

}