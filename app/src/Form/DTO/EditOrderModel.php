<?php

namespace App\Form\DTO;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class EditOrderModel
{
    public int                $id;
    public User               $owner;
    public int                $status;
    public float              $totalPrice;
    public                    $createdAt;
    public \DateTimeImmutable $updatedAt;
    public Collection         $orderProducts;
    public bool               $isDeleted;

    /**
     * @param Order|null $order
     * @return static
     */
    public static function makeFromOrder(?Order $order): self
    {
        $model = new self();
        if (!$order) {
            return $model;
        }

        $model->id            = $order->getId();
        $model->owner         = $order->getOwner();
        $model->status        = $order->getStatus();
        $model->totalPrice    = $order->getTotalPrice();
        $model->createdAt     = $order->getCreatedAt();
        $model->updatedAt     = $order->getUpdatedAt();
        $model->isDeleted     = $order->getIsDeleted();
        $model->orderProducts = $order->getOrderProducts();

        return $model;
    }
}
