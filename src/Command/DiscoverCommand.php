<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Xylemical\Composer\Discovery\ComposerDiscovery;
use function array_intersect_key;

/**
 * Provides the "discover" command.
 */
class DiscoverCommand extends BaseCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure(): void {
    $this->setName('discover');
    $this->setDescription('Perform all discoveries or a discovery against all the packages.');
    $this->addArgument('class', InputArgument::OPTIONAL, 'The discovery class to perform.');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $discovery = new ComposerDiscovery($this->requireComposer(), $this->getIO());

    $packages = $discovery->getPackages();
    $discoveries = $discovery->getDiscoveries();

    if ($argument = $input->getArgument('class')) {
      if (isset($discoveries[$argument])) {
        $discovery->discover($packages, array_intersect_key($discoveries, [$argument => TRUE]));
      }
      return 0;
    }

    $discovery->discover($packages, $discoveries);
    return 0;
  }

}
