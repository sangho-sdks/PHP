<?php
declare(strict_types=1);
namespace Sangho\Resource;
class Transactions extends AbstractResource
{
    protected string $path = '/transactions/';
    public function list(array $c = []): array
    {
        $this->http->assertSecretKey('transactions.list');
        return $this->http->get($this->path, $c);
    }
    public function retrieve(string $id): array
    {
        $this->http->assertSecretKey('transactions.retrieve');
        return $this->http->get("{$this->path}{$id}/");
    }
    public function options(): array
    {
        return $this->http->options($this->path);
    }
}
