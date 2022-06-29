<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Composer\Discovery\ComposerDiscoveryBase.
 */
class ComposerDiscoveryBaseTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $composer = $this->getMockBuilder(Composer::class)
      ->disableOriginalConstructor()
      ->getMock();
    $io = $this->getMockBuilder(IOInterface::class)->getMock();
    $project = $this->getMockBuilder(ComposerProject::class)
      ->disableOriginalConstructor()
      ->getMock();
    $package = $this->getMockBuilder(ComposerPackage::class)
      ->disableOriginalConstructor()
      ->getMock();

    $mock = $this->getMockForAbstractClass(ComposerDiscoveryBase::class, [
      $composer,
      $io,
      $project,
    ]);
    $this->assertEquals([], $mock->getPaths($package));
  }

}
