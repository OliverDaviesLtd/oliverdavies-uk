<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\EventSubscriber;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\core_event_dispatcher\Event\Entity\AbstractEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class DisplayMessageOnOldPosts implements EventSubscriberInterface {

  use StringTranslationTrait;

  private ConfigFactoryInterface $configFactory;

  private MessengerInterface $messenger;

  private TimeInterface $time;

  public function __construct(
    ConfigFactoryInterface $configFactory,
    MessengerInterface $messenger,
    TimeInterface $time
  ) {
    $this->configFactory = $configFactory;
    $this->messenger = $messenger;
    $this->time = $time;
  }

  public function displayMessage(AbstractEntityEvent $event): void {
    $entity = $event->getEntity();

    if ($entity->getEntityTypeId() != 'node') {
      return;
    }

    if ($entity->bundle() != 'post') {
      return;
    }

    if ($this->wasPostPublishedTooRecently($entity)) {
      return;
    }

    $text = $this->configFactory->get('opdavies_blog.settings')
      ->get('old_post_message_text');

    $this->messenger->addStatus($this->t($text));
  }

  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_VIEW => 'displayMessage',
    ];
  }

  private function wasPostPublishedTooRecently(Post $post): bool {
    $currentTime = $this->time->getRequestTime();
    $postAgeInSeconds = $currentTime - $post->getCreatedTime();
    $maxAgeInSeconds = 60 * 60 * 24 * 365;

    return $postAgeInSeconds < $maxAgeInSeconds;
  }

}
