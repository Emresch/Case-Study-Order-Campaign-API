<?php

namespace App\Campaigns;

use App\Campaigns\Strategies\BuyXPayYCampaign;
use App\Campaigns\Strategies\CategoryPercentageDiscount;
use App\Campaigns\Strategies\TotalPercentageDiscount;

class CampaignManager
{
    protected array $campaigns = [];

    public function __construct()
    {
        $this->campaigns = [
            new BuyXPayYCampaign,
            new CategoryPercentageDiscount,
            new TotalPercentageDiscount,
        ];
    }

    public function getBestCampaigns(array $cartItems, float $subtotal)
    {
        $maxDiscount = 0;
        $selectedCampaign = null;
        
        foreach ($this->campaigns as $campaign) {
            if ($campaign->isApplicable($cartItems, $subtotal)) {
                $discount = $campaign->calculateDiscount($cartItems, $subtotal);
                if ($discount > $maxDiscount) {
                    $maxDiscount = $discount;
                    $selectedCampaign = $campaign;
                }
            }
        }
        if ($selectedCampaign === null) {
            return [
                'campaign_name' => null,
                'discount_amount' => 0,
            ];
        }
        return [
            'campaign_name' => $selectedCampaign->getName(),
            'discount_amount' => $maxDiscount,
        ];
    }
}
