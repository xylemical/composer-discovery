<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Prophecy\PhpUnit\ProphecyTrait;
use Composer\Plugin\Capability\CommandProvider as CapabilityCommandProvider;

/**
 * Tests \Xylemical\Composer\Discovery\Plugin.
 */
class PluginTest extends ComposerTestCase {

  use ProphecyTrait;

  /**
   * Tests sanity.
   */
  public function testSanity(): void {
    $plugin = new Plugin();
    $this->assertEquals([
      CapabilityCommandProvider::class => CommandProvider::class,
    ], $plugin->getCapabilities());

    $this->assertEquals([
      ScriptEvents::POST_INSTALL_CMD => 'onPostInstall',
      ScriptEvents::POST_UPDATE_CMD => 'onPostUpdate',
    ], Plugin::getSubscribedEvents());

    $event = $this->prophesize(Event::class);
    $event->getComposer()->willReturn($this->composer);
    $event->getIO()->willReturn($this->io);
    $event = $event->reveal();

    $plugin->onPostInstall($event);
    $this->assertContains("<info>Performing discovery:</info> Test Discovery", $this->results);
    $this->assertContains('foo/bar: ./phpstan.neon', $this->results);

    $this->results = [];
    $plugin->onPostUpdate($event);
    $this->assertContains("<info>Performing discovery:</info> Test Discovery", $this->results);
    $this->assertContains('foo/bar: ./phpstan.neon', $this->results);
  }

}
