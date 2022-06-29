<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\IO\IOInterface;

/**
 * Provides the discovery mechanism.
 */
interface ComposerDiscoveryInterface {

  /**
   * DiscoveryInterface constructor.
   *
   * @param \Composer\Composer $composer
   *   The composer.
   * @param \Composer\IO\IOInterface $io
   *   The IO.
   * @param \Xylemical\Composer\Discovery\ComposerProject $project
   *   The project.
   */
  public function __construct(Composer $composer, IOInterface $io, ComposerProject $project);

  /**
   * Get the display string for the discovery.
   *
   * @return string
   *   The discovery label.
   */
  public function getName(): string;

  /**
   * Get paths.
   *
   * @param \Xylemical\Composer\Discovery\ComposerPackage $package
   *   The package.
   *
   * @return string[]
   *   The paths.
   */
  public function getPaths(ComposerPackage $package): array;

  /**
   * Discover an item using the path within the package.
   *
   * @param \Xylemical\Composer\Discovery\ComposerPackage $package
   *   The package.
   * @param string $path
   *   The path within the package.
   */
  public function discover(ComposerPackage $package, string $path): void;

}
