<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\Core\Queue\QueueFactory;
use Drupal\core_event_dispatcher\Event\Entity\AbstractEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PushPostToSocialMediaOnceItIsPublished implements EventSubscriberInterface {

  private QueueFactory $queueFactory;

  public function __construct(QueueFactory $queueFactory) {
    $this->queueFactory = $queueFactory;
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_INSERT => 'onEntityUpdate',
      HookEventDispatcherInterface::ENTITY_UPDATE => 'onEntityUpdate',
    ];
  }

  public function onEntityUpdate(AbstractEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    /** @var Post $entity */
    if ($entity->bundle() != 'post') {
      return;
    }

    $queue = $this->queueFactory->get('opdavies_blog_push_post_to_social_media');
    $queue->createQueue();

    $queue->createItem([
      'post' => $entity,
    ]);
  }

}
