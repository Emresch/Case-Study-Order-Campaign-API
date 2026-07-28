<?php

namespace App\Campaigns\Contracts;

interface CampaignInterface
{
    public function isApplicable(array $cartitems, float $totalAmount): bool;
    public function calculateDiscount(array $cartitems, float $totalAmount): float;
    public function getName(): string;
}