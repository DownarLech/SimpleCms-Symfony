<?php declare(strict_types=1);

namespace App\Component\Category\Communication\Form;

use App\DataTransferObject\CategoryDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('categoryId', IntegerType::class, [
            'label' => 'category Id'
        ])
            ->add('categoryName', TextType::class, [
                'label' => 'category name'
            ])
            ->add('update', SubmitType::class, [
                'label' => 'Update'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryDataProvider::class
        ]);
    }

}