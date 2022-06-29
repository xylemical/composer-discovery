<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\Config;
use Composer\Console\Application;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Installer\InstallationManager;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Repository\RepositoryManager;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputDefinition;

/**
 * Provides base support for testing the composer plugin.
 */
class ComposerTestCase extends TestCase {

  use ProphecyTrait;

  /**
   * The application.
   *
   * @var \Composer\Console\Application
   */
  protected Application $application;

  /**
   * The composer object.
   *
   * @var \Composer\Composer
   */
  protected Composer $composer;

  /**
   * The IO object.
   *
   * @var \Composer\IO\IOInterface
   */
  protected IOInterface $io;

  /**
   * The written results.
   *
   * @var string[]
   */
  protected array $results = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    $package = $this->prophesize(RootPackageInterface::class);
    $package->getName()->willReturn('foo/bar');
    $package->getExtra()->willReturn([]);
    $package = $package->reveal();

    $dependency = $this->prophesize(CompletePackageInterface::class);
    $dependency->getName()->willReturn('foo/baz');
    $dependency->getExtra()->willReturn([
      'discovery' => [
        TestDiscovery::class,
        ComposerDiscoveryInterface::class,
      ],
    ]);
    $dependency = $dependency->reveal();

    $im = $this->prophesize(InstallationManager::class);
    $im->getInstallPath($package)->willReturn('.');
    $im->getInstallPath($dependency)->willReturn('path/to/dependency');
    $im = $im->reveal();

    $repo = $this->prophesize(InstalledRepositoryInterface::class);
    $repo->getPackages()->willReturn([$dependency]);
    $repo = $repo->reveal();

    $rm = $this->prophesize(RepositoryManager::class);
    $rm->getLocalRepository()->willReturn($repo);
    $rm = $rm->reveal();

    $config = $this->prophesize(Config::class);
    $config->get('vendor-dir')->willReturn('dummy');
    $config = $config->reveal();

    $eventDispatcher = $this->prophesize(EventDispatcher::class);
    $eventDispatcher->dispatch(Argument::any(), Argument::any())->willReturn(0);
    $eventDispatcher = $eventDispatcher->reveal();

    $composer = $this->prophesize(Composer::class);
    $composer->getRepositoryManager()->willReturn($rm);
    $composer->getInstallationManager()->willReturn($im);
    $composer->getPackage()->willReturn($package);
    $composer->getConfig()->willReturn($config);
    $composer->getEventDispatcher()->willReturn($eventDispatcher);

    $this->composer = $composer->reveal();

    $io = $this->prophesize(IOInterface::class);
    $results = &$this->results;
    $io->write(Argument::any())->will(function ($args) use (&$results) {
      $results[] = $args[0];
    });
    $this->io = $io->reveal();

    $app = $this->prophesize(Application::class);
    $app->getComposer(Argument::any(), Argument::any(), Argument::any())->willReturn($this->composer);
    $app->getIO()->willReturn($this->io);
    $app->getHelperSet()->willReturn(
      $this->getMockBuilder(HelperSet::class)->getMock()
    );
    $app->getDefinition()->willReturn(new InputDefinition());
    $this->application = $app->reveal();
  }

}
