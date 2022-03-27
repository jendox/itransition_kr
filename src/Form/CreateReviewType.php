<?php
declare(strict_types=1);
namespace App\Form;

use App\Entity\Category;
use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\UX\Dropzone\Form\DropzoneType;

class CreateReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('images', FileType::class, [
                'multiple' => true,
                'mapped' => false,
                'label' => false,
                'row_attr' => [
                    'class' => 'mt-2',
                ],
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                ],
//                'attr' => [
//                    'data-controller' => 'mydropzone',
//                    'class' => 'form-control',
//                ],
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '4096K',
//                    ])
//                ]
            ])
//            ->add('photo2', FileType::class, [
//                'mapped' => false,
//                'label' => false,
//                'row_attr' => [
//                    'class' => 'mt-3',
//                ],
//                'required' => false,
////                'attr' => [
////                    'data-controller' => 'mydropzone',
////                    'class' => 'form-control',
////                ],
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '4096K',
//                    ])
//                ]
//            ])
//            ->add('photo3', FileType::class, [
//                'mapped' => false,
//                'label' => false,
//                'row_attr' => [
//                    'class' => 'mt-3',
//                ],
//                'required' => false,
////                'attr' => [
////                    'data-controller' => 'mydropzone',
////                    'class' => 'form-control',
////                ],
//                'constraints' => [
//                    new Image([
//                        'maxSize' => '4096K',
//                    ])
//                ]
//            ])
//            ->add('photo3', DropzoneType::class, [
//                'mapped' => false,
//                'label' => false,
//                'row_attr' => [
//                    'class' => 'mt-3'
//                ],
//                'required' => false,
//                'attr' => [
//                    'data-controller' => 'mydropzone',
//                    'class' => 'form-control',
//                ],
//                'constraints' => [
//                    new File([
//                        'maxSize' => '4096K',
//                        'mimeTypes' => [
//                            'image/jpeg',
//                            'image/bmp',
//                            'image/gif',
//                            'image/png'
//                        ],
//                        'mimeTypesMessage' => 'Invalid image format'
//                    ])
//                ]
//            ])
            ->add('title')
            ->add('body', null, [
                'label' => 'Text',
                'attr' => [
                    'rows' => '5',
                ],
            ])
            ->add('authorRating', null, [
                'label' => 'Rating',
                'row_attr' => [
                    'class' => 'col-1 mx-3'
                ],
                'attr' => [
                    'min' => '1',
                    'max' => '5',
                    'step' => '1'
                    ]
            ])
            ->add('category', null, [
                'row_attr' => [
                    'class' => 'col-2'
                ]
            ])
            ->add('tags', TextType::class, [
                'mapped' => false,
                'required' => false,
                'row_attr' => [
                    'class' => 'mt-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
