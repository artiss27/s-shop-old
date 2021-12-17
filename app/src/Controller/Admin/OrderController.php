<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\StaticStorage\OrderStaticStorage;
use App\Form\Admin\EditOrderFormType;
use App\Form\Admin\FilterType\OrderFilterFormType;
use App\Form\DTO\EditOrderModel;
use App\Form\Handler\OrderFormHandler;
use App\Utils\Manager\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order", name="admin_order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function list(Request $request, OrderFormHandler $orderFormHandler): Response
    {
        $model      = new EditOrderModel();
        $filterForm = $this->createForm(OrderFilterFormType::class, $model);
        $filterForm->handleRequest($request);

        $pagination = $orderFormHandler->processOrderFiltersForm($request, $filterForm);

        return $this->render('admin/order/list.html.twig', [
            'pagination'         => $pagination,
            'orderStatusChoices' => OrderStaticStorage::getOrderStatusChoices(),
            'form'               => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     */
    public function edit(Request $request, OrderFormHandler $orderFormHandler, Order $order = null): Response
    {
        $model = EditOrderModel::makeFromOrder($order);

        $form = $this->createForm(EditOrderFormType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $orderFormHandler->processEditForm($model);

            $this->addFlash('success', 'Your changes were saved!');

            return $this->redirectToRoute('admin_order_edit', ['id' => $entity->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!');
        }

        return $this->render('admin/order/edit.html.twig', [
            'order' => $order,
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Order $order, OrderManager $orderManager): Response
    {
        $orderManager->remove($order);

        $this->addFlash('warning', 'The order was successfully deleted!');

        return $this->redirectToRoute('admin_order_list');
    }
}
