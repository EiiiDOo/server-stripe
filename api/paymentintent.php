<?php
require 'vendor/autoload.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $stripe = new \Stripe\StripeClient('sk_test_51Q9FTVFaCGaX8xOkoGQjYZWRoTqZGBClcfnRvghpHZvxO8620cqXPBy9Hp4BWC6OOEJH4dp2DnUW11Cd6TjxwmHp005dyQxRae');

    $customer = $stripe->customers->create(
        [
            'name' => "someone",
            'address' => [
                'line1' => 'yousry address',
                'postal_code' => '738933',//possible error
                'city' => 'cairo',
                'state' => 'pyramids',
                'country' => 'Egypt'
            ]
        ]
    );
    $ephemeralKey = $stripe->ephemeralKeys->create([
        'customer' => $customer->id,
    ], [
        'stripe_version' => '2024-09-30.acacia',
    ]);

    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => 1099,//10.99 euro implicitly dev by 100
        'currency' => 'egp',
        'description' => 'Payment for Shopyfy',
        'customer' => $customer->id,
        // In the latest version of the API, specifying the `automatic_payment_methods` parameter
        // is optional because Stripe enables its functionality by default.
        'automatic_payment_methods' => [
            'enabled' => 'true',
        ],
    ]);

    echo json_encode(
        [
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
            'publishableKey' => 'pk_test_51Q9FTVFaCGaX8xOkXtRMvfjKnSZIwtn13J73JRHAp0lYBvX9fgjbwZSts7HFLaKUQ1Rs5pcBeRhxrne1hmJeWGh900iM13frwB'
        ]
    );
    http_response_code(200);
}else
    echo "Not Authorized";
