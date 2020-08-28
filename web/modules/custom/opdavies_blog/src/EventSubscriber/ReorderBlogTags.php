<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ReorderBlogTags implements EventSubscriberInterface {

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_PRE_SAVE => 'onEntityPreSave',
    ];
  }

  public function onEntityPresave(BaseEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    /** @var Post $entity */
    if ($entity->bundle() != 'post') {
      return;
    }

    $sortedTags = $entity->getTags()
      ->sortBy(fn(TermInterface $tag) => $tag->label());

    $entity->setTags($sortedTags->toArray());
  }

}
