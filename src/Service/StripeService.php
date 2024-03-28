<?php 

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\Query\Expr\Func;
use Stripe\StripeClient;

class StripeService{

    private StripeClient $stripe;

    public function createProduct(Product $product): \Stripe\Product
    {
        return $this->getStripe()->products->create([
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'active' => $product->isActive(),
        ]);
    }

    public function createPrice(Product $product): \Stripe\Price
    {
        return $this->getStripe()->prices->create([
            'unit_amount' => $product->getPrice(),
            'currency' => 'eur',
            'product' => $product->getStripeProductId(),    
        ]);
    }

    public function updateProduct(Product $product): \Stripe\Product
    {
        return $this->getStripe()->products->update(
            $product->getStripeProductId(),
            [
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'active' => $product->isActive(),
            ]
        );
    }


    private function getStripe(): StripeClient
    {
        return  $this->stripe ??= new StripeClient($_ENV['STRIPE_API_SECRET']);
    }


}