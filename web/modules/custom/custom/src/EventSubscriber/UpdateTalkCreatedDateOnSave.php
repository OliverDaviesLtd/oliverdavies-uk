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
      HookEventDispatcherInterface::ENTITY_PRE_SAVE => 'onEntityPreSave',
    ];
  }

  public function onEntityPreSave(BaseEntityEvent $event): void {
    if ($event->getEntity()->getEntityTypeId() != 'node') {
      return;
    }

    if ($event->getEntity()->bundle() != 'talk') {
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
