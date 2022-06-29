<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery\Command;

use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xylemical\Composer\Discovery\ComposerDiscoveryInterface;
use Xylemical\Composer\Discovery\ComposerTestCase;
use Xylemical\Composer\Discovery\TestDiscovery;

/**
 * Tests \Xylemical\Composer\Discover\Command\DiscoverCommand.
 */
class DiscoverCommandTest extends ComposerTestCase {

  /**
   * Provides test data for testSanity().
   *
   * @return array[]
   *   The test data.
   */
  public function providerTestSanity(): array {
    return [
      [NULL, TRUE],
      [TestDiscovery::class, TRUE],
      [ComposerDiscoveryInterface::class, FALSE],
    ];
  }

  /**
   * Tests sanity.
   *
   * @dataProvider providerTestSanity
   */
  public function testSanity(?string $class, bool $expected): void {
    $this->results = [];

    $input = $this->prophesize(InputInterface::class);
    $input->getArgument('class')->willReturn($class);
    $input->hasArgument('class')->willReturn(TRUE);
    $input->hasArgument(Argument::any())->willReturn(FALSE);
    $input->hasParameterOption(Argument::any(), Argument::any())->willReturn(FALSE);
    $input->bind(Argument::any())->will(function () {});
    $input->validate()->will(function () {});
    $input->hasOption(Argument::any())->willReturn(FALSE);
    $input->isInteractive()->willReturn(FALSE);
    $input = $input->reveal();

    $output = $this->getMockBuilder(OutputInterface::class)->getMock();

    $command = new DiscoverCommand();
    $command->setApplication($this->application);
    $command->run($input, $output);

    if ($expected) {
      $this->assertContains("<info>Performing discovery:</info> Test Discovery", $this->results);
      $this->assertContains('foo/bar: ./phpstan.neon', $this->results);
    }
    else {
      $this->assertEquals(0, count($this->results));
    }
  }

}
