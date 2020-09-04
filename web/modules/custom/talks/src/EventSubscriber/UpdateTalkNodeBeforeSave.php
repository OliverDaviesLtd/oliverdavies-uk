<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\EventSubscriber;

use Carbon\Carbon;
use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\paragraphs\ParagraphInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Update a talk node before it's saved.
 */
final class UpdateTalkNodeBeforeSave implements EventSubscriberInterface {

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

    /** @var Talk $talk */
    $talk = $event->getEntity();
    $this->reorderEvents($talk);
    $this->updateCreatedDate($talk);
  }

  private function reorderEvents(Talk $talk): void {
    $events = $talk->getEvents();

    $eventsByDate = $events
      ->sortBy(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->values();

    // If the original event IDs don't match the sorted event IDs, update the
    // event field to use the sorted ones.
    if ($events->map->id() != $eventsByDate->map->id()) {
      $talk->set('field_events', $eventsByDate->toArray());
    }
  }

  private function updateCreatedDate(Talk $talk): void {
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
