<?php

declare(strict_types=1);

namespace Drupal\custom\EventSubscriber;

use Drupal\custom\Entity\Node\Post;
use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PushBlogPostToSocialMedia implements EventSubscriberInterface {

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

    /** @var NodeInterface $entity */
    if ($entity->bundle() != 'post') {
      return;
    }

    if (!$entity->isPublished()) {
      return;
    }

    // If this post has already been sent to social media, do not send it again.
    if ($entity->hasBeenSentToSocialMedia()) {
      return;
    }

    $url = \Drupal::configFactory()->get('opdavies_talks.config')
      ->get('zapier_post_tweet_url');

    \Drupal::httpClient()->post($url, [
      'form_params' => [
        'message' => $entity->toTweet(),
      ],
    ]);

    $entity->set('field_sent_to_social_media', TRUE);
  }

}
