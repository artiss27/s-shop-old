<?php

namespace App\Form\Admin\FilterType;

use App\Entity\StaticStorage\OrderStaticStorage;
use App\Entity\User;
use App\Form\DTO\EditOrderModel;
use App\Form\Type\VirtualSelectType;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFilterFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('id', Filters\NumberFilterType::class, [
//                'label'      => 'Id',
//                'label_attr' => ['class' => 'input-group-text'],
//                'attr'       => ['class' => 'form-control'],
//            ])
            ->add('createdAt', Filters\DateTimeRangeFilterType::class, [
                'label'                  => 'Created at',
                'label_attr'             => ['class' => 'visually-hidden'],
                'row_attr'               => [
                    'class' => 'myclass',
                ],
                'left_datetime_options'  => [
                    'label'      => 'From',
                    'label_attr' => ['class' => 'input-group-text'],
                    'widget'     => 'single_text',
                    'attr'       => ['class' => 'form-control'],
                    'row_attr'   => ['class' => 'input-group w-auto float-start me-2'],
                ],
                'right_datetime_options' => [
                    'label'      => 'To',
                    'label_attr' => ['class' => 'input-group-text'],
                    'widget'     => 'single_text',
                    'attr'       => ['class' => 'form-control'],
                    'row_attr'   => ['class' => 'input-group w-auto float-start'],
                ],
            ])
            ->add('status', Filters\ChoiceFilterType::class, [
                'placeholder' => '...choose status',
                'label'       => 'Status',
                'label_attr'  => ['class' => 'input-group-text'],
                'choices'     => array_flip(OrderStaticStorage::getOrderStatusChoices()),
                'attr'        => ['class' => 'form-control'],
            ])
            ->add('owner', VirtualSelectType::class, [
                'placeholder'  => '...choose User',
                'label'        => 'Owner',
                'label_attr'   => ['class' => 'input-group-text'],
                'attr'         => ['class' => 'form-control'],
                'class'        => User::class,
                'choice_label' => function ($user) {
                    return sprintf('#%s %s', $user->getId(), $user->getEmail());
                },
            ]);
//            ->add('owner', Filters\EntityFilterType::class, [
//                'label'        => 'Owner',
//                'label_attr'   => ['class' => 'input-group-text'],
//                'attr'         => ['class' => 'form-control'],
//                'class'        => User::class,
//                'choice_label' => function ($user) {
//                    return sprintf('#%s %s', $user->getId(), $user->getEmail());
//                },
//            ]);
//            ->add('totalPrice', Filters\NumberRangeFilterType::class, [
//                'label' => 'Total price',
//                'left_number_options' => [
//                    'label' => 'From',
//                    'condition_operator' => FilterOperands::OPERATOR_GREATER_THAN_EQUAL,
//                    'attr' => [
//                        'class' => 'form-control',
//                    ],
//                ],
//                'right_number_options' => [
//                    'label' => 'To',
//                    'condition_operator' => FilterOperands::OPERATOR_LOWER_THAN_EQUAL,
//                    'attr' => [
//                        'class' => 'form-control',
//                    ],
//                ],
//            ])
    }

    public function getBlockPrefix(): string
    {
        return 'order_filter_form';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class'        => EditOrderModel::class,
                                   'method'            => 'GET',
                                   'validation_groups' => ['filtering'],
                               ]);
    }
}
