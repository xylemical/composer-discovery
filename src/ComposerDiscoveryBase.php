<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\IO\IOInterface;

/**
 * Provides a base class for discoveries.
 */
abstract class ComposerDiscoveryBase implements ComposerDiscoveryInterface {

  /**
   * The composer.
   *
   * @var \Composer\Composer
   */
  protected Composer $composer;

  /**
   * The IO.
   *
   * @var \Composer\IO\IOInterface
   */
  protected IOInterface $io;

  /**
   * The project.
   *
   * @var \Xylemical\Composer\Discovery\ComposerProject
   */
  protected ComposerProject $project;

  /**
   * {@inheritdoc}
   */
  public function __construct(Composer $composer, IOInterface $io, ComposerProject $project) {
    $this->composer = $composer;
    $this->io = $io;
    $this->project = $project;

    // Ensure autoload can take place when performing the discovery.
    $vendor = $composer->getConfig()->get('vendor-dir');
    if (file_exists($path = "{$vendor}/autoload.php")) {
      require_once $path;
    }
  }

  /**
   * {@inheritdoc}
   */
  abstract public function getName(): string;

  /**
   * {@inheritdoc}
   */
  public function discover(ComposerPackage $package): void {
  }

  /**
   * {@inheritdoc}
   */
  public function complete(): void {
  }

}
