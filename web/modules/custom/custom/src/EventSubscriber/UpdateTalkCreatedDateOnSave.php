<?php

declare(strict_types=1);

namespace Drupal\custom\EventSubscriber;

use Carbon\Carbon;
use Drupal\Core\Entity\EntityInterface;
use Drupal\custom\Entity\Node;
use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Set the created date for a talk to be the last date that the talk is given.
 */
final class UpdateTalkCreatedDateOnSave implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      HookEventDispatcherInterface::ENTITY_INSERT => 'entityInsertOrUpdate',
      HookEventDispatcherInterface::ENTITY_UPDATE => 'entityInsertOrUpdate',
    ];
  }

  public function entityInsertOrUpdate(BaseEntityEvent $event): void {
    if (!$event->getEntity() instanceof Node) {
      return;
    }

    $this->updateCreatedDate($event->getEntity());
  }

  private function updateCreatedDate(EntityInterface $talk): void {
    /** @var \Drupal\custom\Entity\Node $talk */
    if (!$eventDate = $talk->findLatestEventDate()) {
      return;
    }

    $talkDate = Carbon::parse($eventDate)->getTimestamp();

    if ($talkDate == $talk->get('created')->getString()) {
      return;
    }

    $talk->set('created', $talkDate);
  }

}
