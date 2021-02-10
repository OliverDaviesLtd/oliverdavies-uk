<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Service;

use Carbon\Carbon;
use Drupal\opdavies_talks\Repository\TalkRepository;
use Drupal\paragraphs\ParagraphInterface;

final class TalkCounter {

  private TalkRepository $talkRepository;

  public function __construct(TalkRepository $talkRepository) {
    $this->talkRepository = $talkRepository;
  }

  public function getCount(): int {
    $today = Carbon::today()->format('Y-m-d H:i:s');

    return $this->talkRepository
      ->findAllPublished()
      ->getEvents()
      ->filter(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString() <= $today)
      ->count();
  }

}
