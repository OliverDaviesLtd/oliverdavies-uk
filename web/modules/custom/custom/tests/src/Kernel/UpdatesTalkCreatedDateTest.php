<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel;

use Carbon\Carbon;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

final class UpdatesTalkCreatedDateTest extends TalksTestBase {

  public function testCreatingNode() {
    $eventDate = Carbon::today()->addWeek();
    $eventDateFormat = $eventDate
      ->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateTimestamp = $eventDate->getTimestamp();

    $talk = $this->createTalk([
      'field_events' => [
        $this->createEvent(['field_date' => $eventDateFormat]),
      ],
    ]);

    $this->assertEqual($eventDateTimestamp, $talk->getCreatedTime());
  }

  public function testUpdatingNode() {
    $talk = $this->createTalk();
    $originalCreatedTime = $talk->getCreatedTime();

    $eventDate = Carbon::today()->addWeek();
    $eventDateFormat = $eventDate
      ->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateTimestamp = $eventDate->getTimestamp();

    $talk->addEvent(
      $this->createEvent(['field_date' => $eventDateFormat])
    );
    $talk->save();

    $this->assertNotSame($originalCreatedTime, $talk->getCreatedTime());
    $this->assertSame($eventDateTimestamp, $talk->getCreatedTime());
  }

}
