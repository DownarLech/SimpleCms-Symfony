<?php declare(strict_types=1);


namespace App\Component\ProductFrontEnd\Communication\Form;


use App\Component\Category\Business\CategoryBusinessFacade;
use App\DataTransferObject\CsvProductDataProvider;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'product name'
        ])
            ->add('description', TextType::class, [
                'label' => 'product description'
            ])
            ->add('articleNumber', TextType::class, [
                'label' => 'article number'
            ])
            ->add('categoryName', EntityType::class, [
                'class' => Category::class,
                'mapped' => false,
                'choice_label' => 'name',
             //   'choice_value' => function (CategoryBusinessFacade $categoryBusinessFacade) {
             //       return $categoryBusinessFacade->getCategoryByName('name');
             //   }
            ])
            ->add('update', SubmitType::class, [
                'label' => 'Update'
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CsvProductDataProvider::class,
        ]);
    }

}