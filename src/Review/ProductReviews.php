<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizaplace\Review;

class ProductReviews
{
    /**
     * @var
     */
    private $reviews;

    public function getReviews(): ?array
    {
        return $this->reviews;
    }

    public function addReview(ProductReview $productReview): ProductReviews
    {
        $this->reviews[] = $productReview;
        return $this;
    }
}
