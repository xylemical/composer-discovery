# Composer discovery

Provides composer with a way to perform discovery processes.

## Install

The recommended way to install this library is [through composer](http://getcomposer.org).

```sh
composer require xylemical/composer-discovery
```

## Usage

Once a discovery process has been defined in a package, any package or project that
requires the package will have the discovery applied.

Adding a discovery is as simple as defining the discovery class in the extra key of
the composer.json file:

```json
{
  "extra": {
    "discovery": [
      "My\\Discovery"
    ]
  }
}
```

An example discovery that prints out all the package readme files is as follows:

```php
namespace My;

use Xylemical\Composer\Discovery\ComposerDiscoveryBase;

/**
 * Performs output of README.md for any package that defines it. 
 */
class Discovery extends ComposerDiscoveryBase {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'My Discovery';
  }

  /**
   * {@inheritdoc}
   */
  public function getPaths(Package $package): array {
    $path = $package->getPath() . '/README.md';
    return file_exists($path) ? [$path] : [];
  }

  /**
   * {@inheritdoc}
   */
  public function discover(Package $package, string $path): void {
    $this->io->write(file_get_contents($path));
  }

} 
```

## License

MIT, see LICENSE.
