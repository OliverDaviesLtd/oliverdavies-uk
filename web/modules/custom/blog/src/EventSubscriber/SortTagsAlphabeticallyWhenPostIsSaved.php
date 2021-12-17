<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\core_event_dispatcher\Event\Entity\AbstractEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SortTagsAlphabeticallyWhenPostIsSaved implements EventSubscriberInterface {

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_PRE_SAVE => 'sortTags',
    ];
  }

  public function sortTags(AbstractEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    if ($entity->bundle() != 'post') {
      return;
    }

    $post = Post::createFromNode($entity);

    $sortedTags = $post->getTags()
      ->sortBy(fn(TermInterface $tag) => $tag->label());

    $post->setTags($sortedTags->toArray());
  }

}
