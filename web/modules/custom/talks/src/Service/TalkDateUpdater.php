<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Service;

use Carbon\Carbon;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\opdavies_talks\Repository\TalkRepository;
use Drupal\paragraphs\ParagraphInterface;

final class TalkDateUpdater {

  private TalkRepository $talkRepository;
  private TimeInterface $time;

  public function __construct(
    TalkRepository $talkRepository,
    TimeInterface $time
  ) {
    $this->talkRepository = $talkRepository;
    $this->time = $time;
  }

  public function __invoke(): void {
    foreach ($this->talkRepository->findAll() as $talk) {
      $this->updateNextEventDate($talk);
    }
  }

  private function updateNextEventDate(Talk $talk): void {
    if (!$nextDate = $this->findNextEventDate($talk)) {
      return;
    }

    $nextDateTimestamp = Carbon::parse($nextDate)
      ->getTimestamp();

    if ($nextDateTimestamp == $talk->getNextDate()) {
      return;
    }

    $talk->setNextDate($nextDateTimestamp);
    $talk->save();
  }

  private function findNextEventDate(Talk $talk): ?string {
    $currentTime = Carbon::today()
      ->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);

    $dates = $talk->getEvents()
      ->map(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->sort();

    if ($dates->isEmpty()) {
      return NULL;
    }

    // If a future date is found, return it.
    if ($futureDate = $dates->first(fn(string $eventDate) => $eventDate > $currentTime)) {
      return $futureDate;
    }

    // If no future date is found, return the last past date.
    return $dates->last();
  }

}
