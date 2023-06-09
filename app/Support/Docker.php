<?php

namespace App\Support;

use App\Fleet;

class Docker
{
    public function getNetwork(string $name): ?string
    {
        $process = Fleet::process("docker network ls --filter name=^{$name}$ --format '{{.ID}}'");

        if ($process->isSuccessful()) {
            return trim($process->getOutput());
        }

        return null;
    }

    public function createNetwork(string $name): string
    {
        $process = Fleet::process("docker network create {$name}");

        if ($process->isSuccessful()) {
            return trim($process->getOutput());
        }

        throw new \Exception('Could not create Docker network');
    }

    public function removeNetwork(string $name): void
    {
        Fleet::process("docker network rm {$name}");
    }

    public function getContainer(string $name): ?string
    {
        $process = Fleet::process("docker ps --filter name=^{$name}$ --format '{{.ID}}'");

        if ($process->isSuccessful()) {
            return trim($process->getOutput());
        }

        return null;
    }

    public function removeContainers(string $network): void
    {
        $process = Fleet::process("docker ps -a --filter network={$network} --format {{.ID}}");

        $ids = explode("\n", $process->getOutput());
        foreach (array_filter($ids) as $id) {
            Fleet::process("docker rm -f {$id}");
        }
    }

    public function startFleetTraefikContainer(): void
    {
        $homeDirectory = app(Filesystem::class)->getHomeDirectory();

        Fleet::process(
            "docker run -d -p 8080:8080 -p 80:80 -p 443:443 --network=fleet -v /var/run/docker.sock:/var/run/docker.sock -v {$homeDirectory}/.config/mkcert:/etc/traefik --name=fleet traefik:v2.9 --api.insecure=true --providers.docker --entryPoints.web.address=:80 --entryPoints.websecure.address=:443 --providers.file.directory=/etc/traefik/conf --providers.file.watch=true",
            true
        );
    }
}
