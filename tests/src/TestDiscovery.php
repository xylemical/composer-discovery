<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use function file_exists;

/**
 * A test discovery.
 */
class TestDiscovery extends ComposerDiscoveryBase {

  /**
   * {@inheritdoc}
   */
  public function getName(): string {
    return 'Test Discovery';
  }

  /**
   * {@inheritdoc}
   */
  public function discover(ComposerPackage $package): void {
    $path = "{$package->getPath()}/phpstan.neon";
    if (file_exists($path)) {
      $this->io->write("{$package->getName()}: {$path}");
    }
  }

}
