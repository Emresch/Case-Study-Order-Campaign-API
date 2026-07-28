<?php

namespace App\Campaigns\Strategies;
use App\Campaigns\Contracts\CampaignInterface;

class CategoryPercentageDiscount implements CampaignInterface
{
    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        foreach ($cartitems as $item) {
            if ($item['category_id'] === 1) { // Assuming '1' is the ID for the "Roman" category
                return true;
            }
        }
        return false;
    }
    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        $discount = 0;
        foreach ($cartitems as $item) {
            if ($item['category_id'] === 1) { // Assuming '1' is the ID for the "Roman" category
                $discount += $item['price'] * $item['quantity'] * 0.15; // 15% discount for Roman category
            }
        }
        return $discount;
    }
    public function getName(): string
    {
        return '%15 Roman Kategorisi İndirimi';
    }
}

    