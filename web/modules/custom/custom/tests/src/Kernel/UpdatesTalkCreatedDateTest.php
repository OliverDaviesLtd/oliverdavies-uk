<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel;

use Carbon\Carbon;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

final class UpdatesTalkCreatedDateTest extends TalksTestBase {

  public function testCreatingNode() {
    $eventDate = Carbon::today()->addWeek();
    $eventDateToFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateToTimestamp = $eventDate->getTimestamp();

    $talk = $this->createTalk($eventDateToFormat);

    $this->assertEqual($eventDateToTimestamp, $talk->get('created')
      ->getString());
  }

  public function testUpdatingNode() {
    $talk = $this->createTalk();

    $eventDate = Carbon::today()->addWeek();
    $eventDateToFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateToTimestamp = $eventDate->getTimestamp();

    $event = $this->createEvent($eventDateToFormat);
    $talk->set('field_events', [$event]);
    $talk->save();

    $this->assertEqual($eventDateToTimestamp, $talk->get('created')
      ->getString());
  }

}
