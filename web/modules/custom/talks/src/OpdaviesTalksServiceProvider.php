<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;

final class OpdaviesTalksServiceProvider implements ServiceProviderInterface {

  public function register(ContainerBuilder $container): void {
    foreach (['EventSubscriber', 'Repository', 'Service'] as $directory) {
      $files = Finder::create()
        ->in(__DIR__ . '/' . $directory)
        ->files()
        ->name('*.php');

      foreach ($files as $file) {
        $class = 'Drupal\opdavies_talks\\' . $directory . '\\' .
          str_replace('/', '\\', substr($file->getRelativePathname(), 0, -4));

        if ($container->hasDefinition($class)) {
          continue;
        }

        $definition = new Definition($class);
        $definition->setAutowired(TRUE);
        if ($directory == 'EventSubscriber') {
          $definition->addTag('event_subscriber');
        }
        $container->setDefinition($class, $definition);
      }
    }
  }

}
