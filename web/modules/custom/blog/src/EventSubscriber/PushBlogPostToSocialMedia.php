<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\core_event_dispatcher\Event\Entity\AbstractEntityEvent;
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

  public function onEntityUpdate(AbstractEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    /** @var Post $entity */
    if ($entity->bundle() != 'post') {
      return;
    }

    if (!$url = $this->config->get('post_tweet_webhook_url')) {
      return;
    }

    if (!$this->shouldBePushed($entity)) {
      return;
    }

    $this->client->post($url, [
      'form_params' => [
        'value1' => $entity->toTweet(),
      ],
    ]);

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

