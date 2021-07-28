<?php declare(strict_types=1);


namespace App\Component\ProductFrontEnd\Communication\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('upload_file', FileType::class, [
            'label' => 'file to upload',
            'attr' => [
                'placeholder' => 'select file to upload',
            ],
            'mapped' => false,
            'required' => false,
            ])
            ->add('send', SubmitType::class,[
                'label' => 'Import File'
            ])
        ;
    }

 /*
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CsvProductDataProvider::class , // which class??
             'csrf_token_id'   => 'task_item',
        ]);
    }
 */
}