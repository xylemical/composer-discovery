<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use PHPUnit\Framework\TestCase;

/**
 * Tests \Xylemical\Composer\Discovery\ComposerPackage.
 */
class ComposerPackageTest extends TestCase {

  /**
   * Test sanity.
   */
  public function testSanity(): void {
    $package = new ComposerPackage('foo', 'bar');
    $this->assertEquals('foo', $package->getName());
    $this->assertEquals('bar', $package->getPath());
    $this->assertEquals([], $package->getExtra());
    $this->assertNull($package->get('key'));
    $this->assertEquals('test', $package->get('key', 'test'));

    $package->setExtra([
      'key' => 'dummy',
    ]);
    $this->assertEquals('dummy', $package->get('key'));
    $this->assertEquals('dummy', $package->get('key', 'foo'));
  }

}
