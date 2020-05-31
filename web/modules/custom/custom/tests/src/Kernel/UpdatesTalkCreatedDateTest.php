<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel;

use Carbon\Carbon;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

final class UpdatesTalkCreatedDateTest extends TalksTestBase {

  public function testCreatingNode() {
    $eventDate = Carbon::today()->addWeek();
    $eventDateFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateTimestamp = $eventDate->getTimestamp();

    $talk = $this->createTalk([
      'field_events' => [
        $this->createEvent(['field_date' => $eventDateFormat]),
      ],
    ]);

    $this->assertEqual($eventDateTimestamp, $talk->get('created')
      ->getString());
  }

  public function testUpdatingNode() {
    $eventDate = Carbon::today()->addWeek();
    $eventDateFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateTimestamp = $eventDate->getTimestamp();

    $talk = $this->createTalk([
      'field_events' => [
        $this->createEvent(['field_date' => $eventDateFormat]),
      ],
    ]);

    $this->assertEqual($eventDateTimestamp, $talk->get('created')
      ->getString());
  }

}
