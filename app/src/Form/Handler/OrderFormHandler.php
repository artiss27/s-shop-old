<?php

namespace App\Form\Handler;

use App\Entity\Order;
use App\Form\DTO\EditOrderModel;
use App\Utils\Manager\OrderManager;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class OrderFormHandler
{
    private OrderManager         $manager;
    private PaginatorInterface   $paginator;
    private FilterBuilderUpdater $filterBuilderUpdater;

    public function __construct(OrderManager $manager, PaginatorInterface $paginator, FilterBuilderUpdater $filterBuilderUpdater)
    {
        $this->manager              = $manager;
        $this->paginator            = $paginator;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
    }

    public function processOrderFiltersForm(Request $request, FormInterface $filterForm): PaginationInterface
    {
        $queryBuilder = $this->manager->getRepository()
                                      ->createQueryBuilder('o')
                                      ->leftJoin('o.owner', 'u')
                                      ->where('o.isDeleted = :isDeleted')
                                      ->orderBy('o.createdAt', 'DESC')
                                      ->setParameter('isDeleted', false);

        if ($filterForm->isSubmitted()) {
//            if ($owner = $request->query->get('order_filter_form')['owner'] ?? 0) {
//                $queryBuilder->andwhere('o.owner = :owner')->setParameter('owner', $owner);
//            }
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $this->paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1)
        );
    }

    /**
     * @param EditOrderModel $model
     * @return Order
     */
    public function processEditForm(EditOrderModel $model): Order
    {
        $entity = new Order();

        if ($model->id) {
            $entity = $this->manager->get($model->id);
        }

        $entity->setStatus($model->status);
        $entity->setCreatedAt($model->createdAt);
        $entity->setUpdatedAt($model->updatedAt);
        $entity->setOwner($model->owner);
        $entity->setIsDeleted($model->isDeleted);
        $entity->setOrderProducts($model->orderProducts);

        $this->manager->recalculateOrderTotalPrice($entity);
        $this->manager->save($entity);

        return $entity;
    }
}
