# Fleet

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jezzdk/fleet-cli.svg?style=flat-square)](https://packagist.org/packages/jezzdk/fleet-cli)
[![Total Downloads](https://img.shields.io/packagist/dt/jezzdk/fleet-cli.svg?style=flat-square)](https://packagist.org/packages/jezzdk/fleet-cli)

Easily run multiple Laravel Sail applications at the same time locally with custom domain names.

> Note: This is still new and may contain bugs, if you see something weird please [create an issue](https://github.com/jezzdk/fleet-cli/issues/new)

- [Installation](#installation)
- [Getting Started](#getting-started)
- [Local SSL](#local-ssl)
- [Port Conflicts](#port-conflicts)
- [Additional Usage](#additional-usage)

## Installation

You can install the package via composer:

```bash
composer require global jezzdk/fleet-cli
```

## Getting Started

Navigate to the root directory of your Laravel application, and stop any Sail instance if it's running. Then, use the following command to add Fleet support to your app:

```bash
fleet add
```

During setup you'll be prompted to enter in a domain name. Alternatively, you can pass it in through the command line:

```bash
fleet add my-app.localhost
```

After the setup finishes, you can start up Sail just like normal:

```bash
./vendor/bin/sail up
```

And your site will be available at the domain you provided!

> Note: If you chose a domain that doesn't end in `.localhost`, you will need to add an entry to your hosts file to direct traffic to 127.0.0.1

## Local SSL

Fleet supports local SSL on your custom domains through the power of [mkcert](https://mkcert.dev). After you've installed it on your machine, you can use the `--ssl` option when using the `fleet add` command to enable it for your application.

```bash
fleet add my-app.localhost --ssl
```

A local certificate will be generated and stored in `~/.config/mkcert/certs`. After spinning up your site with Sail, your specified domain will have https enabled.

## Port Conflicts

When spinning up multiple Laravel Sail appliactions, it's likely you'll encounter an error about port conflicts between Docker containers. This is because each service has a port mapped to your local machine, and by default, they're the same across your applications.

In order to fix this, add different forwarded port numbers to each Laravel application using the `.env` file. For example:

- App #1

```env
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
```

- App #2

```env
FORWARD_DB_PORT=4306
FORWARD_REDIS_PORT=7379
```

This way, both applications can be spun up using Fleet and Sail, and their respective services' ports won't conflict.

## Additional Usage

By default, whenever you use `fleet add`, a Docker network and container are both started to handle the traffic from your local domain name(s).

You can start this manually by using:

```bash
fleet start
```

If you would like to remove Fleet support from an application and return it back to the default Docker setup, you can run:

```bash
fleet remove
```

To stop and remove all Fleet containers and networks that are currently running on your system, you can use the command:

```bash
fleet stop
```

## Why use this?

[Laravel Sail](https://laravel.com/docs/sail) uses Docker and Docker Compose to spin up containers that create a local development environment for your application.

By default, the containers are bound to the `:80` port of your local machine. Spinning up a second application results in a failure due to port conflicts, but you can always adjust it so that the second app is available at something like `:8081`.

This can have some unintended consequences though, and can get messy juggling communication between two different applications using port numbers.

Instead, Fleet provides a small set of commands that alter your `docker-compose.yml` file to provide support for [Traefik](https://hub.docker.com/_/traefik), a reverse proxy that runs on a Docker container.

When you add a site to Fleet, a network and a few labels are added to the Docker Compose entry for your application, and a main Traefik container is spun up to handle all local web traffic incoming to the `:80` port.

This configuration allows two or more Laravel Sail applications configured to different domains to resolve to their respective running containers.

For a more in-depth look at how this all ties together, check out [this video](https://www.youtube.com/watch?v=mZbLvGQqEvY) that I published on using Traefik with Docker Compose.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what's recently changed.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
