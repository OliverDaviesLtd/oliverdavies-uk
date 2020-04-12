<?php

declare(strict_types=1);

namespace Drupal\custom\Command;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drush\Commands\DrushCommands;

/**
 * Renames legacy tag terms.
 */
final class FormatTagNamesCommand extends DrushCommands {

  /**
   * The taxonomy term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $termStorage;

  /**
   * A lookup table for new name overrides.
   *
   * @var array
   *   An associative array, keyed by the original tag name. The value is either
   *   an overridden tag name or FALSE if the tag name is not to be changed.
   */
  private static $tagNames = [
    'accessible-bristol' => 'Accessible Bristol',
    'admin:hover' => FALSE,
    'aria' => 'ARIA',
    'cck' => 'CCK',
    'centos' => 'CentOS',
    'css' => 'CSS',
    'dcbristol' => FALSE,
    'ddev' => 'DDEV',
    'drupal-association' => 'Drupal Association',
    'drupal-bristol' => 'Drupal Bristol',
    'drupal-commerce' => 'Drupal Commerce',
    'drupal-planet' => 'Drupal Planet',
    'drupal-vm' => 'Drupal VM',
    'drupal-vm-generator' => 'Drupal VM Generator',
    'drupalcamp' => 'DrupalCamp',
    'drupalcamp-bristol' => 'DrupalCamp Bristol',
    'drupalcamp-london' => 'DrupalCamp London',
    'drupalcamp-north' => 'DrupalCamp North',
    'drupalcon' => 'DrupalCon',
    'entity-api' => 'Entity API',
    'fancy-slide' => 'Fancy Slide',
    'field-collection' => 'Field Collection',
    'filefield' => 'FileField',
    'form-api' => 'Form API',
    'git-flow' => 'Git Flow',
    'github' => 'GitHub',
    'illuminate-collections' => 'Illuminate Collections',
    'image-caption' => 'Image Caption',
    'imagecache' => 'ImageCache',
    'imagefield' => 'ImageField',
    'imagefield-import' => 'ImageField Import',
    'javascript' => 'JavaScript',
    'laravel-collections' => 'Laravel Collections',
    'laravel-mix' => 'Laravel Mix',
    'linux-journal' => 'Linux Journal',
    'mac-os-x' => 'macOS',
    'mamp' => 'MAMP',
    'mod_rewrite' => FALSE,
    'npm' => FALSE,
    'oliverdavies.co.uk' => FALSE,
    'php' => 'PHP',
    'php-south-wales' => 'PHP South Wales',
    'phpstorm' => 'PhpStorm',
    'phpsw' => 'PHPSW',
    'phpunit' => 'PHPUnit',
    'postcss' => 'PostCSS',
    'psr' => 'PSR',
    'regular-expression' => 'Regular expressions',
    'sequel-pro' => 'Sequel Pro',
    'settings.php' => FALSE,
    'sql' => 'SQL',
    'ssh' => 'SSH',
    'sublime-text' => 'Sublime Text',
    'svn' => 'SVN',
    'swdug' => 'SWDUG',
    'symfonylive' => 'SymfonyLive',
    'tailwind-css' => 'Tailwind CSS',
    'tdd' => 'TDD',
    'views-attach' => 'Views Attach',
    'virtualbox' => 'VirtualBox',
    'vuejs' => 'VueJS',
    'virtualhostx' => 'VirtualHostX',
  ];

  /**
   * FormatTagNamesCommand constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager service.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct();

    $this->termStorage = $entityTypeManager->getStorage('taxonomy_term');
  }

  /**
   * Drush command for updating legacy tag names.
   *
   * @command opdavies:update-tag-names
   */
  public function updateTagNames(): void {
    foreach ($this->getTags() as $tag) {
      $name = $tag->label();
      $newName = $this->getNewTagName($name);

      if ($newName === NULL) {
        $this->writeln(sprintf('Skipping %s.', $name));
        continue;
      }

      $this->writeln(sprintf('Updating %s to %s.', $name, $newName));
      $tag->name = $newName;
      $tag->save();
    }
  }

  /**
   * Get all of the current tags.
   */
  private function getTags(): array {
    return $this->termStorage->loadByProperties([
      'vid' => 'tags',
    ]);
  }

  /**
   * Get the new tag name.
   */
  private function getNewTagName(string $tagName): ?string {
    if (!array_key_exists($tagName, static::$tagNames)) {
      return str_replace('-', ' ', ucfirst($tagName));
    }

    if (static::$tagNames[$tagName] === FALSE) {
      return NULL;
    }

    return static::$tagNames[$tagName];
  }

}
