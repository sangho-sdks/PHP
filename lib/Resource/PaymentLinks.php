<?php
declare(strict_types=1);
namespace Sangho\Resource;
class PaymentLinks extends AbstractResource
{
    protected string $path = '/payment-links/';
    public function list(array $c = []): array
    {
        $this->http->assertSecretKey('paymentLinks.list');
        return $this->http->get($this->path, $c);
    }
    public function retrieve(string $id): array
    {
        $this->http->assertSecretKey('paymentLinks.retrieve');
        return $this->http->get("{$this->path}{$id}/");
    }
    public function create(array $p): array
    {
        $this->http->assertSecretKey('paymentLinks.create');
        return $this->http->post($this->path, $p);
    }
    public function update(string $id, array $p): array
    {
        $this->http->assertSecretKey('paymentLinks.update');
        return $this->http->patch("{$this->path}{$id}/", $p);
    }
    public function deactivate(string $id): array
    {
        $this->http->assertSecretKey('paymentLinks.deactivate');
        return $this->http->post("{$this->path}{$id}/deactivate/");
    }
    public function options(): array
    {
        return $this->http->options($this->path);
    }
}
