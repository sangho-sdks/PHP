# Sangho PHP SDK

SDK officiel PHP pour l'API [Sangho](https://sangho.com) — paiements XAF pour l'Afrique.

[![Packagist](https://img.shields.io/packagist/v/sangho/sangho-php.svg)](https://packagist.org/packages/sangho/sangho-php)
[![CI](https://github.com/sangho-sdks/sangho-php/actions/workflows/ci.yml/badge.svg)](https://github.com/sangho-sdks/sangho-php/actions/workflows/ci.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

---

## Installation

```bash
composer require sangho/sangho-php
```

## Quickstart

```php
use Sangho\Sangho;

$client = new Sangho('sk_live_...');

// Créer un payment intent
$intent = $client->paymentIntents->create([
    'amount'   => 5000,
    'currency' => 'XAF',
    'customer' => 'cust_xxx',
]);

echo $intent->id;
```

## Documentation

La documentation complète est disponible sur [docs.sangho.africa](https://docs.sangho.africa).

## Ressources disponibles

`apps` · `customers` · `products` · `paymentIntents` · `checkoutSessions` ·
`invoices` · `transactions` · `refunds` · `subscriptions` · `paymentMethods` ·
`webhooks` · `paymentLinks` · `addresses` · `partners`

## Contribuer

Voir [CONTRIBUTING.md](CONTRIBUTING.md).

## Changelog

Voir [CHANGELOG.md](CHANGELOG.md).

## Licence

MIT
