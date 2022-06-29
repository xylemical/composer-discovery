<?php

declare(strict_types=1);

namespace Xylemical\Composer\Discovery;

/**
 * Provides an abstraction of a composer package.
 */
class ComposerPackage {

  /**
   * The package name.
   *
   * @var string
   */
  protected string $name;

  /**
   * The package installation path.
   *
   * @var string
   */
  protected string $path;

  /**
   * The extra provided by the package.
   *
   * @var array
   */
  protected array $extra = [];

  /**
   * Package constructor.
   *
   * @param string $name
   *   The package name.
   * @param string $path
   *   The package path.
   */
  public function __construct(string $name, string $path) {
    $this->name = $name;
    $this->path = $path;
  }

  /**
   * Get the package name.
   *
   * @return string
   *   The name.
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Get the package installation path.
   *
   * @return string
   *   The path.
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * Get the extra from the package.
   *
   * @return array
   *   The extra.
   */
  public function getExtra(): array {
    return $this->extra;
  }

  /**
   * Set the extra from the package.
   *
   * @param array $extra
   *   The extra.
   *
   * @return $this
   */
  public function setExtra(array $extra): static {
    $this->extra = $extra;
    return $this;
  }

  /**
   * Get a key from the extras.
   *
   * @param string $key
   *   The key, uses periods to indicate sub-keys.
   * @param mixed|null $default
   *   The default.
   *
   * @return mixed
   *   The value or default.
   */
  public function get(string $key, mixed $default = NULL): mixed {
    $parts = explode('.', $key);
    $target = $this->extra;
    while (count($parts)) {
      $part = array_shift($parts);
      if (!is_array($target) || !isset($target[$part])) {
        return $default;
      }
      $target = $target[$part];
    }
    return $target;
  }

}
