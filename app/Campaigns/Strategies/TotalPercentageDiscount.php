<?php

namespace App\Campaigns\Strategies;

use App\Campaigns\Contracts\CampaignInterface;

class TotalPercentageDiscount implements CampaignInterface
{
    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        return $totalAmount > 200;
    }

    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        return $totalAmount * 0.10; // 10% discount
    }

    public function getName(): string
    {
        return '%10 Sepet İndirimi';
    }
    
}