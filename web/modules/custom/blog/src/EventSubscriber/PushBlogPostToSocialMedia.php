<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PushBlogPostToSocialMedia implements EventSubscriberInterface {

  private ClientInterface $client;

  private ImmutableConfig $config;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    Client $client
  ) {
    $this->client = $client;
    $this->config = $configFactory->get('opdavies_blog.settings');
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

  public function onEntityUpdate(BaseEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    /** @var Post $entity */
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

    if ($entity->isExternalPost()) {
      return;
    }

    if (!$url = $this->config->get('zapier_post_tweet_url')) {
      return;
    }

    $this->client->post($url, [
      'form_params' => [
        'message' => $entity->toTweet(),
      ],
    ]);

    $entity->set('field_sent_to_social_media', TRUE);
    $entity->save();
  }

}
