<?php

namespace App\Form;

use App\Entity\Ticket;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TicketType
 * @package App\Form
 * doc form: https://symfony.com/doc/current/forms.html
 * doc repeatetype: https://symfony.com/doc/current/reference/forms/types/repeated.html
 *
 */

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_booking', TextType::class, ['empty_data' => null,
                'label' => false, 'attr' => ['placeholder' => 'Date de réservation', 'class' => 'form-control datepicker date-picker-booking mb-4']])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'invalid_message' => 'Les emails ne sont pas identiques',
                'options' => ['attr' => ['class' => 'form-control mb-4']],
                'required' => true,
                'first_options'  => ['label' => false, 'attr' => ['placeholder' => 'E-mail', 'class' => 'form-control mb-4']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'Confirmer votre E-mail', 'class' => 'form-control mb-4']],
            ])
            ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Nom', 'class' => 'form-control mb-4']])
            ->add('first_name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Prénom', 'class' => 'form-control mb-4']])
            ->add('country', CountryType::class, ["preferred_choices" => array('FR'),'label' => false, 'attr' => ['placeholder' => 'Choisissez votre pays', 'class' => 'form-control mb-4']])
            ->add('birth_date', TextType::class, ['empty_data' => null,
                'label' => false, 'attr' => ['placeholder' => 'Date de naissance', 'class' => 'form-control datepicker date-picker-birth mb-4']])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Tarif normal' => 0,
                    'Mi-journée (à partir de 14h)' => 1,
                    'Tarif Réduit (Etudiant,Employé du musée...) - Un justificatif peut être demandé à l\'entrée' => 2,
                ],
                'label' => false, 'attr' => ['class' => 'form-control mb-4'],
                'placeholder' => 'Choisissez le type de billet',
                'choice_attr' => array(
                    'Tarif normal' => array('class' => 'ChoiceType0'),
                    'Mi-journée (à partir de 14h)' => array('class' => 'ChoiceType1'),
                    'Tarif Réduit (Etudiant,Employé du musée...) - Un justificatif peut être demandé à l\'entrée' => array('class' => 'ChoiceType2'),
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
