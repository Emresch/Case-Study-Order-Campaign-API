<?php

namespace App\Campaigns\Strategies;
use App\Campaigns\Contracts\CampaignInterface;
use App\Models\Campaign;

class CategoryPercentageDiscount implements CampaignInterface
{
    protected Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        foreach ($cartitems as $item) {
            if ($item['category_id'] === $this->campaign->parameters['category_id'] ?? null) {
                return true;
            }
        }
        return false;
    }
    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        $discount = 0;
        foreach ($cartitems as $item) {
            if ($item['category_id'] === $this->campaign->parameters['category_id'] ?? null) {
                $discount += $item['price'] * $item['quantity'] * ($this->campaign->parameters['discount_ratio'] ?? 0.15); // 15% discount for Roman category
            }
        }
        return $discount;
    }
    public function getName(): string
    {
        return $this->campaign->title;
    }
}

    