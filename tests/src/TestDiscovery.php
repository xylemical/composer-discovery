<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

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
  public function getPaths(ComposerPackage $package): array {
    $path = "{$package->getPath()}/phpstan.neon";
    return file_exists($path) ? [$path] : [];
  }

  /**
   * {@inheritdoc}
   */
  public function discover(ComposerPackage $package, string $path): void {
    $this->io->write("{$package->getName()}: {$path}");
  }

}
