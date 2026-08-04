<?php

namespace App\Campaigns;

use App\Campaigns\Strategies\BuyXPayYCampaign;
use App\Campaigns\Strategies\CategoryPercentageDiscount;
use App\Campaigns\Strategies\TotalPercentageDiscount;

use App\Models\Campaign;

class CampaignManager
{
    protected array $campaigns = [];

    public function __construct()
    {
        $activateCampaigns = Campaign::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        foreach ($activateCampaigns as $dbCampaign) {
            $strategyClass = $this->getStrategyClass($dbCampaign->type);
            if ($strategyClass){
                $this->campaigns[] = new $strategyClass($dbCampaign);
            }
        }
    }

    public function getStrategyClass(string $type): ?string
    {
        $map = [
            'buy_x_pay_y' => BuyXPayYCampaign::class,
            'category_percentage' => CategoryPercentageDiscount::class,
            'total_percentage' => TotalPercentageDiscount::class,
        ];

        return $map[$type] ?? null;
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
