<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

use Composer\Plugin\Capability\CommandProvider as ComposerCommandProvider;
use Xylemical\Composer\Discovery\Command\DiscoverCommand;

/**
 * Provides the composer commands.
 */
class CommandProvider implements ComposerCommandProvider {

  /**
   * {@inheritdoc}
   */
  public function getCommands() {
    return [
      new DiscoverCommand(),
    ];
  }

}
