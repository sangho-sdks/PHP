<?php
declare(strict_types=1);
namespace Sangho\Resource;
class CheckoutSessions extends AbstractResource
{
    protected string $path = '/checkout-sessions/';
    public function list(array $c = []): array
    {
        $this->http->assertSecretKey('checkoutSessions.list');
        return $this->http->get($this->path, $c);
    }
    public function retrieve(string $id): array
    {
        $this->http->assertSecretKey('checkoutSessions.retrieve');
        return $this->http->get("{$this->path}{$id}/");
    }
    public function create(array $p): array
    {
        $this->http->assertSecretKey('checkoutSessions.create');
        return $this->http->post($this->path, $p);
    }
    public function expire(string $id): array
    {
        $this->http->assertSecretKey('checkoutSessions.expire');
        return $this->http->post("{$this->path}{$id}/expire/");
    }
    public function options(): array
    {
        return $this->http->options($this->path);
    }
}
