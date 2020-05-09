<?php

declare(strict_types=1);

namespace Drupal\custom\EventSubscriber;

use Carbon\Carbon;
use Drupal\Core\Entity\EntityInterface;
use Drupal\hook_event_dispatcher\Event\Entity\BaseEntityEvent;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;
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
    if ($event->getEntity()->getEntityTypeId() != 'node') {
      return;
    }

    if ($event->getEntity()->bundle() != 'talk') {
      return;
    }

    $this->updateCreatedDate($event->getEntity());
  }

  private function updateCreatedDate(EntityInterface $talk): void {
    if (!$eventDate = $this->findLatestEventDate($talk)) {
      return;
    }

    $talkDate = Carbon::parse($eventDate)->getTimestamp();

    if ($talkDate == $talk->get('created')->getString()) {
      return;
    }

    $talk->set('created', $talkDate);
  }

  /**
   * Find the date for the latest event.
   *
   * @return string|null
   */
  private function findLatestEventDate(EntityInterface $talk) {
    return Collection::make($talk->get('field_events')->referencedEntities())
      ->map(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->max();
  }

}
