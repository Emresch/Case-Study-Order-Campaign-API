<?php

namespace App\Campaigns\Strategies;
use App\Campaigns\Contracts\CampaignInterface;
use App\Models\Campaign;

class BuyXPayYCampaign implements CampaignInterface
{
    protected Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        $quantity = 0;
        foreach ($cartitems as $item) {
            if ($item['author_id'] === ($this->campaign->parameters['author_id'] ?? null))
                 {
                $quantity += $item['quantity'];
            }
        }
        return $quantity >= ($this->campaign->parameters['min_quantity'] ?? 3);
    }
    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        $pricelist = [];
        foreach ($cartitems as $item) {
            if ($item['author_id'] === $this->campaign->parameters['author_id'] ?? null) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $pricelist[] = $item['price'];
                }
            }
        }
        sort($pricelist);
         return $pricelist[0]; // The cheapest book is free
    }
    public function getName(): string
    {
        return $this->campaign->title;
    }
}