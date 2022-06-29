<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

/**
 * Tests \Xylemical\Composer\Discovery\ComposerDiscovery.
 */
class ComposerDiscoveryTest extends ComposerTestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $discovery = new ComposerDiscovery($this->composer, $this->io);
    $project = $discovery->getProject();
    $this->assertEquals('foo/bar', $project->getName());
    $this->assertEquals(".", $project->getPath());
    $this->assertEquals("dummy", $project->getVendor());

    $packages = $discovery->getPackages();
    $this->assertEquals([
      (new ComposerPackage('foo/baz', 'path/to/dependency'))
        ->setExtra([
          'discovery' => [
            TestDiscovery::class,
            ComposerDiscoveryInterface::class,
          ],
        ]),
      (new ComposerProject('foo/bar', '.'))
        ->setVendor('dummy'),
    ], $packages);

    $discoveries = $discovery->getDiscoveries();
    $this->assertEquals([
      TestDiscovery::class => new TestDiscovery($this->composer, $this->io, $project),
    ], $discoveries);

    $discovery->discover($packages, $discoveries);
    $this->assertContains("<info>Performing discovery:</info> Test Discovery", $this->results);
    $this->assertContains('foo/bar: ./phpstan.neon', $this->results);
  }

}
