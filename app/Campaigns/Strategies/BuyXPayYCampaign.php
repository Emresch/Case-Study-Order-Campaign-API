<?php

namespace App\Campaigns\Strategies;
use App\Campaigns\Contracts\CampaignInterface;

class BuyXPayYCampaign implements CampaignInterface
{
    public function isApplicable(array $cartitems, float $totalAmount): bool
    {
        $quantity = 0;
        foreach ($cartitems as $item) {
            if ($item['author_id'] === 3) { // Assuming '3' is the ID for the "Sabahattin Ali" author
                $quantity += $item['quantity'];
            }
        }
        return $quantity >= 3;
    }
    public function calculateDiscount(array $cartitems, float $totalAmount): float
    {
        $pricelist = [];
        foreach ($cartitems as $item) {
            if ($item['author_id'] === 3) { // Assuming '3' is the ID for the "Sabahattin Ali" author
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
        return 'Sabahattin Ali Yazarına Özel 3 Al 2 Öde Kampanyası';
    }
}