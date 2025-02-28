<?php

use Ollyo\Task\Routes;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helper.php';
require_once('vendor/autoload.php');

define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', baseUrl());

$products = [
    [
        'name' => 'Minimalist Leather Backpack',
        'image' => BASE_URL . '/resources/images/backpack.webp',
        'qty' => 1,
        'price' => 120,
    ],
    [
        'name' => 'Wireless Noise-Canceling Headphones',
        'image' => BASE_URL . '/resources/images/headphone.jpg',
        'qty' => 1,
        'price' => 250,
    ],
    [
        'name' => 'Smart Fitness Watch',
        'image' => BASE_URL . '/resources/images/watch.webp', 
        'qty' => 1,
        'price' => 199,
    ],
    [
        'name' => 'Portable Bluetooth Speaker',
        'image' => BASE_URL . '/resources/images/speaker.webp',
        'qty' => 1,
        'price' => 89,
    ],
];
$shippingCost = 10;

$data = [
    'products' => $products,
    'shipping_cost' => $shippingCost,
    'address' => [
        'name' => 'Sherlock Holmes',
        'email' => 'sherlock@example.com',
        'address' => '221B Baker Street, London, England',
        'city' => 'London',
        'post_code' => 'NW16XE',
    ]
];

Routes::get('/', function () {
    return view('app', []);
});

Routes::get('/checkout', function () use ($data) {
    return view('checkout', $data);
});

Routes::post('/checkout', function ($request) use ($data) {
    // @todo: Implement PayPal payment gate way integration here
    // 1. Initialize PayPal API client with credentials
    // 2. Create payment with order details from $data
    // 3. Execute payment and handle response
    // 4. If payment successful, save order and redirect to thank you page
    // 5. If payment fails, redirect to error payment page with message

    // Consider creating a dedicated controller class  to handle payment processing
    // This helps separate payment logic from routing and keeps code organized
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postCode = trim($_POST['post_code'] ?? '');

    // Initialize Stripe client
    \Stripe\Stripe::setApiKey("sk_test_51QxPhn2cAqGBtE7RE8IqZVPyx5UN0hWB8EQQJ8vN8Bg3RHuE5hXThR240lsW9K9bzZXzTo5hvZejzMuvKIpA5fIR00ngIIVqA2");

    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Full name is required.';
    }

    if (empty($email) || preg_match('/\s/', $email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $errors['email'] = 'Invalid email format.';
    }

    if (empty($address)) {
        $errors['address'] = 'Address is required.';
    }

    if (empty($city)) {
        $errors['city'] = 'City is required.';
    }

    if (empty($postCode)) {
        $errors['post_code'] = 'Postal code is required.';
    } else if (!preg_match('/^[a-zA-Z0-9\s\-]+$/', $postCode)) {
        $errors['post_code'] = 'Invalid postal code format.';
    }

    if (!empty($errors)) {
        return view('checkout', array_merge($data, ['errors' => $errors]));
    }

    // Calculate total amount for Stripe (in cents)
    $subtotal = 0;
    foreach ($data['products'] as $product) {
        $subtotal += $product['price'] * $product['qty'];
    }
    $total = $subtotal + $data['shipping_cost'];
    $amountInCents = $total * 100; // Convert to cents

    try {
        // Create a PaymentIntent with Stripe
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'description' => 'Order from ' . $name,
            'receipt_email' => $email,
            'metadata' => [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'city' => $city,
                'post_code' => $postCode,
            ],
        ]);

        // Verify payment amount matches cart total
        if ($paymentIntent->amount !== $amountInCents) {
            throw new Exception("Payment amount mismatch. Expected: $amountInCents, Received: " . $paymentIntent->amount);
        }

        // Check payment status from Stripe
        // if ($paymentIntent->status !== 'succeeded') {
        //     throw new Exception("Payment failed. Status: " . $paymentIntent->status);
        // }

        // Validate transaction ID
        $transactionId = $paymentIntent->id;
        if (empty($transactionId)) {
            throw new Exception("Invalid transaction ID.");
        }

        return view('success', ['paymentIntent' => $paymentIntent]);
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // API errors
        return view('failure', ['error' => $e->getMessage()]);
    } catch (Exception $e) {
        // custom errors (like amount mismatch, invalid transaction ID)
        return view('failure', ['error' => $e->getMessage()]);
    }
});

Routes::get('/success', function () {
    return view('success', []);
});

Routes::get('/failure', function () {
    return view('failure', []);
});
// Register thank you & payment failed routes with corresponding views here.

$route = Routes::getInstance();
$route->dispatch();
?>
