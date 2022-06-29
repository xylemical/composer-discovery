<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

/**
 * Provides the project definition.
 */
class ComposerProject extends ComposerPackage {

  /**
   * The vendor directory.
   *
   * @var string
   */
  protected string $vendor = '';

  /**
   * Get the vendor directory.
   *
   * @return string
   *   The vendor directory.
   */
  public function getVendor(): string {
    return $this->vendor;
  }

  /**
   * Set the vendor directory.
   *
   * @param string $vendor
   *   The vendor directory.
   *
   * @return $this
   */
  public function setVendor(string $vendor): static {
    $this->vendor = $vendor;
    return $this;
  }

}
