<?php

namespace App\Campaigns\Strategies;

use App\Campaigns\Contracts\CampaignInterface;
use App\Models\Campaign;

class TotalPercentageDiscount implements CampaignInterface
{
    protected Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        $threshold = $this->campaign->parameters['threshold'] ?? 2000.00; // Default threshold if not set
        return $totalAmount >= $threshold;
    }

    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        $discountRatio = $this->campaign->parameters['discount_ratio'] ?? 0.10; // Default discount ratio if not set
        return $totalAmount * $discountRatio;
    }

    public function getName(): string
    {
        return $this->campaign->title;
    }
    
}