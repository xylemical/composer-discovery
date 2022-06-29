<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use PHPUnit\Framework\TestCase;
use Xylemical\Composer\Discovery\Command\DiscoverCommand;

/**
 * Tests \Xylemical\Composer\Discovery\CommandProvider.
 */
class CommandProviderTest extends TestCase {

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $provider = new CommandProvider();
    $this->assertEquals([
      new DiscoverCommand(),
    ], $provider->getCommands());
  }

}
