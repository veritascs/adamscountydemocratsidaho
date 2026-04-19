<p align="center"><img src="https://statamic.com/assets/branding/Statamic-Logo+Wordmark-Rad.svg" width="400" alt="Statamic Logo" /></p>

## About Statamic

Statamic is the flat-first, Laravel + Git powered CMS designed for building beautiful, easy to manage websites.

> [!NOTE]
> This repository contains the code for a fresh Statamic project that is installed via the Statamic CLI tool.
>
> The code for the Statamic Composer package itself can be found at the [Statamic core package repository][cms-repo].


## Learning Statamic

Statamic has extensive [documentation][docs]. We dedicate a significant amount of time and energy every day to improving them, so if something is unclear, feel free to open issues for anything you find confusing or incomplete. We are happy to consider anything you feel will make the docs and CMS better.

## Support

We provide official developer support on [Statamic Pro](https://statamic.com/pricing) projects. Community-driven support is available via [GitHub Discussions](https://github.com/statamic/cms/discussions) and in [Discord][discord].


## Contributing

Thank you for considering contributing to Statamic! We simply ask that you review the [contribution guide][contribution] before you open issues or send pull requests.


## Code of Conduct

In order to ensure that the Statamic community is welcoming to all and generally a rad place to belong, please review and abide by the [Code of Conduct](https://github.com/statamic/cms/wiki/Code-of-Conduct).


## Important Links

- [Statamic Main Site](https://statamic.com)
- [Statamic Documentation][docs]
- [Statamic Core Package Repo][cms-repo]
- [Statamic Migrator](https://github.com/statamic/migrator)
- [Statamic Discord][discord]

## DreamHost Deployment

This site is deployed on DreamHost with the application code outside the served web root.

### Server layout

- App repo: `/home/dh_v2mg7z/adamscountydemocratsidaho.org/adamscountydemocratsidaho`
- Served web root: `/home/dh_v2mg7z/adamscountydemocratsidaho.org/public`

### One-time setup

1. Install Composer locally for the DreamHost shell user:

	```bash
	mkdir -p ~/.php/composer
	cd ~/.php/composer
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php --filename=composer.phar
	rm composer-setup.php
	```

2. Create `.env` from `.env.example` and configure production values.

3. Make sure the served `public/index.php` points back to the repo:

	```php
	<?php

	use Illuminate\Foundation\Application;
	use Illuminate\Http\Request;

	define('LARAVEL_START', microtime(true));

	$basePath = __DIR__.'/../adamscountydemocratsidaho';

	if (file_exists($maintenance = $basePath.'/storage/framework/maintenance.php')) {
		 require $maintenance;
	}

	require $basePath.'/vendor/autoload.php';

	/** @var Application $app */
	$app = require_once $basePath.'/bootstrap/app.php';

	$app->handleRequest(Request::capture());
	```

### Deploy steps

Run on the DreamHost server:

```bash
cd /home/dh_v2mg7z/adamscountydemocratsidaho.org/adamscountydemocratsidaho
git pull
php ~/.php/composer/composer.phar install --no-dev --optimize-autoloader
php artisan optimize
php please stache:refresh
```

If the sqlite database file does not exist yet:

```bash
touch database/database.sqlite
```

### Frontend assets

Vite build files are ignored by git, so after frontend changes you must upload `public/build` from your local machine to both locations:

1. The repo `public/build` so Laravel can read the Vite manifest.
2. The served `public/build` so browsers can fetch the CSS and JS.

From the local machine:

```bash
rsync -av /Users/Max.Bechdel/Projects/adamscountydems/public/build/ \
dh_v2mg7z@pdx1-shared-a1-06.dreamhost.com:/home/dh_v2mg7z/adamscountydemocratsidaho.org/adamscountydemocratsidaho/public/build/

rsync -av /Users/Max.Bechdel/Projects/adamscountydems/public/build/ \
dh_v2mg7z@pdx1-shared-a1-06.dreamhost.com:/home/dh_v2mg7z/adamscountydemocratsidaho.org/public/build/
```

### Known gotchas

- The lock file must stay compatible with PHP 8.2 on DreamHost.
- `APP_URL` must include the scheme, for example `https://adamscountydemocratsidaho.org`.
- `APP_DEBUG` should be `false` in production.
- If the frontend 500s while `/cp` still works, check `public/index.php` pathing and whether `public/build` exists in both locations.

[docs]: https://statamic.dev/
[discord]: https://statamic.com/discord
[contribution]: https://github.com/statamic/cms/blob/master/CONTRIBUTING.md
[cms-repo]: https://github.com/statamic/cms
