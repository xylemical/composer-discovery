<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackageInterface;

/**
 * Provides discovery mechanisms.
 */
class ComposerDiscovery {

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
   * The packages.
   *
   * @var \Xylemical\Composer\Discovery\ComposerPackage[]
   */
  protected array $packages;

  /**
   * Discovery constructor.
   *
   * @param \Composer\Composer $composer
   *   The composer.
   * @param \Composer\IO\IOInterface $io
   *   The IO.
   */
  public function __construct(Composer $composer, IOInterface $io) {
    $this->composer = $composer;
    $this->io = $io;
  }

  /**
   * Get the project.
   *
   * @return \Xylemical\Composer\Discovery\ComposerProject
   *   The project.
   */
  public function getProject(): ComposerProject {
    if (isset($this->project)) {
      return $this->project;
    }

    $package = $this->composer->getPackage();
    $project = (new ComposerProject(
      $package->getName(),
      $this->composer->getInstallationManager()->getInstallPath($package),
    ))->setExtra($package->getExtra())
      ->setVendor($this->composer->getConfig()->get('vendor-dir'));

    $this->project = $project;
    return $project;
  }

  /**
   * Get the packages from composer.
   *
   * @return \Xylemical\Composer\Discovery\ComposerPackage[]
   *   The packages.
   */
  public function getPackages(): array {
    if (isset($this->packages)) {
      return $this->packages;
    }

    $installer = $this->composer->getInstallationManager();
    $repo = $this->composer->getRepositoryManager()->getLocalRepository();

    $packages = [];
    foreach ($repo->getPackages() as $package) {
      if ($package instanceof CompletePackageInterface) {
        $packages[] = (new ComposerPackage(
          $package->getName(),
          $installer->getInstallPath($package))
        )->setExtra($package->getExtra());
      }
    }

    $packages[] = $this->getProject();

    $this->packages = $packages;
    return $packages;
  }

  /**
   * Get the discoveries.
   *
   * @return \Xylemical\Composer\Discovery\ComposerDiscoveryInterface[]
   *   The discoveries, indexed by class name.
   */
  public function getDiscoveries(): array {
    $discoveries = [];
    foreach ($this->getPackages() as $package) {
      if (!($discovery = $package->get('discovery'))) {
        continue;
      }

      $discovery = (array) $discovery;
      foreach ($discovery as $class) {
        if ($object = $this->getDiscovery($class)) {
          $discoveries[$class] = $object;
        }
      }
    }
    return $discoveries;
  }

  /**
   * Get the discovery class.
   *
   * @param string $class
   *   The class.
   *
   * @return \Xylemical\Composer\Discovery\ComposerDiscoveryInterface|null
   *   The discovery class or NULL.
   */
  protected function getDiscovery(string $class): ?ComposerDiscoveryInterface {
    if (!class_exists($class) || !(is_subclass_of($class, ComposerDiscoveryInterface::class))) {
      return NULL;
    }
    return new $class($this->composer, $this->io, $this->getProject());
  }

  /**
   * Perform the discovery.
   *
   * @param \Xylemical\Composer\Discovery\ComposerPackage[] $packages
   *   The packages.
   * @param \Xylemical\Composer\Discovery\ComposerDiscoveryInterface[] $discoveries
   *   The discovery.
   */
  public function discover(array $packages, array $discoveries): void {
    foreach ($discoveries as $discovery) {
      $this->io->write("<info>Performing discovery:</info> {$discovery->getName()}");

      foreach ($packages as $package) {
        $discovery->discover($package);
      }

      $discovery->complete();
    }
  }

}
