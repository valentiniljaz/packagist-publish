## Private Packagist Publish

Publish your package to Packagist.com using API. Very useful for PHP monorepos.

### Install

Add the package as dev dependency to your project:

```
composer require --dev valentiniljaz/packagist-publish
```

### Use

Publish your package:

```
./vendor/bin/packagist-publish
```

> Initial publish of the package must be done manually by uploading
> archive to Packagist.com

If you only need to prepare archive for publishing:

```
./vendor/bin/packagist-archive
```

Help about commands:

```
./vendor/bin/packagist-publish -h
./vendor/bin/packagist-archive -h
```

### How it works

#### 1) Builds zip archive using GIT

```
git archive -o <dest> HEAD[:<subdir>]
```

> You must have all your changes committed.

#### 2) Publishes archive to Packagist.com using API endpoint 

```
POST /api/packages/<package-name>/artifacts/
```

> You must provide Packagist API Key and Secret either
> by command params (--apiKey, --apiSecret)
> or by env variables (PACKAGIST_KEY, PACKAGIST_SECRET).
> Params have precedence over env vars.

#### Details

- If path to composer.json is not specified by command option it'll take the first composer.json it finds from current working dir upwards.
- For GIT ARCHIVE it needs to know the root of GIT repo; it'll take the first dir containing `.git` from current working dir upwards.
- Default destination for archive is the location of dir containing composer.json.

## Author

Valentin Iljaž

[valentin.iljaz@gmail.com](mailto:valentin.iljaz@gmail.com)

## License

MIT