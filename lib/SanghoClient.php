<?php

declare(strict_types=1);

namespace Sangho;

use Sangho\Resource\{
    Apps,
    Customers,
    Products,
    PaymentIntents,
    PaymentLinks,
    CheckoutSessions,
    Invoices,
    Transactions,
    Refunds,
    Subscriptions,
    PaymentMethods,
    Receipts,
    Webhooks,
    Security,
    Partners
};

class SanghoClient
{
    public readonly Apps $apps;
    public readonly Customers $customers;
    public readonly Products $products;
    public readonly PaymentIntents $paymentIntents;
    public readonly PaymentLinks $paymentLinks;
    public readonly CheckoutSessions $checkoutSessions;
    public readonly Invoices $invoices;
    public readonly Transactions $transactions;
    public readonly Refunds $refunds;
    public readonly Subscriptions $subscriptions;
    public readonly PaymentMethods $paymentMethods;
    public readonly Receipts $receipts;
    public readonly Webhooks $webhooks;
    public readonly Security $security;
    public readonly Partners $partners;

    public function __construct(
        string $apiKey,
        string $baseUrl = 'https://api.sangho.com/v1',
        int $timeout = 30
    ) {
        $http = new HttpClient($apiKey, $baseUrl, $timeout);

        $this->apps = new Apps($http);
        $this->customers = new Customers($http);
        $this->products = new Products($http);
        $this->paymentIntents = new PaymentIntents($http);
        $this->paymentLinks = new PaymentLinks($http);
        $this->checkoutSessions = new CheckoutSessions($http);
        $this->invoices = new Invoices($http);
        $this->transactions = new Transactions($http);
        $this->refunds = new Refunds($http);
        $this->subscriptions = new Subscriptions($http);
        $this->paymentMethods = new PaymentMethods($http);
        $this->receipts = new Receipts($http);
        $this->webhooks = new Webhooks($http);
        $this->security = new Security($http);
        $this->partners = new Partners($http);
    }
}
