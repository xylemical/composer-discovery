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
   * Discover items within the package.
   *
   * @param \Xylemical\Composer\Discovery\ComposerPackage $package
   *   The package.
   */
  public function discover(ComposerPackage $package): void;

  /**
   * Complete the discovery for all packages.
   */
  public function complete(): void;

}
