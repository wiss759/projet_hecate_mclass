<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\OpenHours;
use App\Repository\CategoryRepository;
use App\Repository\OpenHoursRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoursOpenType extends AbstractType
{
    private $_tabCat = [];

    public function __construct(Security $security)
    {
        foreach($security->getUser()->getCategories() as $row){
            $this->_tabCat[] = $row->getId();
        }        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tabHourStart = [];
        $hoursStart = 8;
        $hoursEnd = 18;
        for($i = $hoursStart; $i <= $hoursEnd; $i++){
            $tabHourStart[] = $i;
        }

        $builder
            ->add('start_hours', TimeType::class, [
                'hours' => $tabHourStart,
                'minutes' => [0, 10, 20, 30, 40, 50]
            ])
            ->add('end_hours', TimeType::class, [
                'hours' => $tabHourStart,
                'minutes' => [0, 10, 20, 30, 40, 50]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.id IN (:list)')
                        ->setParameter(':list', $this->_tabCat)
                        ->orderBy('u.name', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'mapped' => false
            ])
            ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OpenHours::class,
        ]);
    }
}
