<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_talks\Kernel;

use Carbon\Carbon;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;

final class UpdatesTalkCreatedDateTest extends TalksTestBase {

  /** @test */
  public function the_date_is_updated_when_a_talk_node_is_created(): void {
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

  /** @test */
  public function the_date_is_updated_when_a_talk_node_is_updated(): void {
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
