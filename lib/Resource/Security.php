<?php
declare(strict_types=1);
namespace Sangho\Resource;
class Security extends AbstractResource
{
    protected string $path = '/security/';
    public function retrieve(): array
    {
        $this->http->assertSecretKey('security.retrieve');
        return $this->http->get($this->path);
    }
    public function update(array $p): array
    {
        $this->http->assertSecretKey('security.update');
        return $this->http->patch($this->path, $p);
    }
    public function rollSecretKey(): array
    {
        $this->http->assertSecretKey('security.rollSecretKey');
        return $this->http->post("{$this->path}roll-secret/");
    }
    public function listSessions(array $c = []): array
    {
        $this->http->assertSecretKey('security.listSessions');
        return $this->http->get("{$this->path}sessions/", $c);
    }
    public function revokeSession(string $sessionId): void
    {
        $this->http->assertSecretKey('security.revokeSession');
        $this->http->delete("{$this->path}sessions/{$sessionId}/");
    }
    public function options(): array
    {
        return $this->http->options($this->path);
    }
}
