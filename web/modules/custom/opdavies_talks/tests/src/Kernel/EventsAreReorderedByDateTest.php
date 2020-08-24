<?php

declare(strict_types=1);

namespace Drupal\Tests\opdavies_talks\Kernel;

use Carbon\Carbon;
use Drupal\paragraphs\ParagraphInterface;

final class EventsAreReorderedByDateTest extends TalksTestBase {

  public function testCreatingNode() {
    $events = [
      $this->createEvent([
        'field_date' => Carbon::today()->addWeeks(2),
        'field_name' => 'Drupal Bristol',
      ]),
      $this->createEvent([
        'field_date' => Carbon::yesterday(),
        'field_name' => 'DrupalCamp London',
      ]),
      $this->createEvent([
        'field_date' => Carbon::tomorrow(),
        'field_name' => 'PHP UK conference',
      ]),
      $this->createEvent([
        'field_date' => Carbon::today()->addMonths(3),
        'field_name' => 'CMS Philly',
      ]),
      $this->createEvent([
        'field_date' => Carbon::today()->subYear(),
        'field_name' => 'PHP South Wales',
      ]),
    ];

    $talk = $this->createTalk([
      'field_events' => $events,
    ]);

    $this->assertSame(
      [
        'PHP South Wales',
        'DrupalCamp London',
        'PHP UK conference',
        'Drupal Bristol',
        'CMS Philly',
      ],
      $talk->getEvents()
        ->map(fn(ParagraphInterface $event) => $event->get('field_name')
          ->getString())
        ->toArray()
    );
  }

  public function testUpdatingNode() {
    $events = [
      $this->createEvent([
        'field_date' => Carbon::today()->addWeeks(2),
        'field_name' => 'Drupal Bristol',
      ]),
      $this->createEvent([
        'field_date' => Carbon::yesterday(),
        'field_name' => 'DrupalCamp London',
      ]),
      $this->createEvent([
        'field_date' => Carbon::today()->addMonths(3),
        'field_name' => 'CMS Philly',
      ]),
      $this->createEvent([
        'field_date' => Carbon::today()->subYear(),
        'field_name' => 'PHP South Wales',
      ]),
    ];

    $talk = $this->createTalk([
      'field_events' => $events,
    ]);

    $this->assertSame(
      [
        'PHP South Wales',
        'DrupalCamp London',
        'Drupal Bristol',
        'CMS Philly',
      ],
      $talk->getEvents()
        ->map(fn(ParagraphInterface $event) => $event->get('field_name')
          ->getString())
        ->toArray()
    );

    $talk->addEvent($this->createEvent([
      'field_date' => Carbon::tomorrow(),
      'field_name' => 'PHP UK conference',
    ]));
    $talk->save();

    $this->assertSame(
      [
        'PHP South Wales',
        'DrupalCamp London',
        'PHP UK conference',
        'Drupal Bristol',
        'CMS Philly',
      ],
      $talk->getEvents()
        ->map(fn(ParagraphInterface $event) => $event->get('field_name')
          ->getString())
        ->toArray()
    );
  }

}
