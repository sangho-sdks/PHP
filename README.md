# Sangho PHP SDK

Official PHP SDK for the [Sangho](https://sangho.com) payment platform.

## Installation

```bash
composer require sangho/sdk
```

## Quick Start

```php
<?php
use Sangho\SanghoClient;

$sangho = new SanghoClient('sk_test_xxx');

// Create a customer
$customer = $sangho->customers->create([
    'email' => 'jean@example.com',
    'name'  => 'Jean Ondo',
]);

// Create a payment intent
$intent = $sangho->paymentIntents->create([
    'amount'   => 25000,
    'customer' => $customer['id'],
]);

// Confirm
$confirmed = $sangho->paymentIntents->confirm($intent['id']);
```

## Webhook Verification

```php
<?php
use Sangho\Resource\Webhooks;

$event = Webhooks::constructEvent(
    payload: file_get_contents('php://input'),
    signatureHeader: $_SERVER['HTTP_SANGHO_SIGNATURE'],
    secret: 'whsec_xxx',
);
```

## Requirements

- PHP 8.1+
- Guzzle 7+
