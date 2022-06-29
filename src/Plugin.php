<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Provides the composer plugin for discovery.
 */
class Plugin implements PluginInterface, EventSubscriberInterface, Capable {

  /**
   * {@inheritdoc}
   */
  public function getCapabilities() {
    return [
      ComposerCommandProvider::class => CommandProvider::class,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ScriptEvents::POST_INSTALL_CMD => 'onPostInstall',
      ScriptEvents::POST_UPDATE_CMD => 'onPostUpdate',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function activate(Composer $composer, IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function deactivate(Composer $composer, IOInterface $io) {
  }

  /**
   * {@inheritdoc}
   */
  public function uninstall(Composer $composer, IOInterface $io) {
  }

  /**
   * Processes the discovery classes after install of packages.
   *
   * @param \Composer\Script\Event $event
   *   The event.
   */
  public function onPostInstall(Event $event): void {
    $discovery = new ComposerDiscovery($event->getComposer(), $event->getIO());
    $discovery->discover($discovery->getPackages(), $discovery->getDiscoveries());
  }

  /**
   * Processes the discovery classes after update of packages.
   *
   * @param \Composer\Script\Event $event
   *   The event.
   */
  public function onPostUpdate(Event $event): void {
    $discovery = new ComposerDiscovery($event->getComposer(), $event->getIO());
    $discovery->discover($discovery->getPackages(), $discovery->getDiscoveries());
  }

}
