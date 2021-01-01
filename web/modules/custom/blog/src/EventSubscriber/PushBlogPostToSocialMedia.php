<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\core_event_dispatcher\Event\Entity\AbstractEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\opdavies_blog\Service\PostPusher\PostPusher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PushBlogPostToSocialMedia implements EventSubscriberInterface {

  private PostPusher $postPusher;

  public function __construct(PostPusher $postPusher) {
    $this->postPusher = $postPusher;
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

    if (!$this->shouldBePushed($entity)) {
      return;
    }

    $this->postPusher->push($entity);

    $entity->markAsSentToSocialMedia();
    $entity->save();
  }

  private function shouldBePushed(Post $post): bool {
    if ($post->isExternalPost()) {
      return FALSE;
    }

    if (!$post->isPublished()) {
      return FALSE;
    }

    if (!$post->shouldSendToSocialMedia()) {
      return FALSE;
    }

    if ($post->hasBeenSentToSocialMedia()) {
      return FALSE;
    }

    return TRUE;
  }

}
