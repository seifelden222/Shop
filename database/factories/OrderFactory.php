<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;

        
        if ($faker) {
            $customerName = $faker->name();
            $customerEmail = $faker->email();
            $customerPhone = $faker->phoneNumber();
            $city = $faker->city();
            $postal = $faker->postcode();
            $notes = $faker->optional()->sentence();
            $total = $faker->randomFloat(2, 10, 1000);
            $description = $faker->optional()->paragraph();
            $address = $faker->address();
            $status = $faker->randomElement(['pending', 'processing', 'completed', 'cancelled']);
            $shippingCost = $faker->randomFloat(2, 0, 50);
            $orderNumber = $faker->uuid();
            $providerOrderId = $faker->optional()->uuid();
            $paymentMethod = $faker->randomElement(['credit_card', 'paypal', 'stripe', 'cash_on_delivery', 'bank_transfer']);
            $paymentStatus = $faker->randomElement(['pending', 'paid', 'failed', 'refunded']);
            $transactionId = $faker->optional()->uuid();
            $createdAt = $faker->dateTimeBetween('-3 months', 'now');
        } else {
            $customerName = 'Customer ' . substr(sha1(microtime(true)), 0, 6);
            $customerEmail = 'customer+' . substr(sha1(microtime(true)), 0, 6) . '@example.com';
            $customerPhone = '010' . rand(10000000, 99999999);
            $city = 'City';
            $postal = (string) rand(10000, 99999);
            $notes = null;
            $total = (float) number_format(rand(1000, 100000) / 100, 2);
            $description = null;
            $address = 'Sample Address';
            $status = 'pending';
            $shippingCost = 0.0;
            $orderNumber = (string) uniqid('order_');
            $providerOrderId = null;
            $paymentMethod = 'cash_on_delivery';
            $paymentStatus = 'pending';
            $transactionId = null;
            $createdAt = now();
        }

        return [
            'user_id' => \App\Models\User::factory(),
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'city' => $city,
            'postal_code' => $postal,
            'notes' => $notes,
            'total_price' => $total,
            'description' => $description,
            'address' => $address,
            'status' => $status,
            'shipping_cost' => $shippingCost,
            'order_number' => $orderNumber,
            'currency' => 'USD',
            'provider_order_id' => $providerOrderId,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'transaction_id' => $transactionId,
            'created_at' => $createdAt,
        ];
    }
}
