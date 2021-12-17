<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_talks\Kernel;

use Carbon\Carbon;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\node\Entity\Node;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\opdavies_talks\Service\TalkDateUpdater;

final class TalkEventDateTest extends TalksTestBase {

  /** @test */
  public function talk_event_dates_are_set_to_the_next_future_date(): void {
    $dateFormat = DateTimeItemInterface::DATE_STORAGE_FORMAT;

    $talk = $this->createTalk([
      'field_event_date' => NULL,
      'field_events' => [
        $this->createEvent([
          'field_date' => Carbon::today()
            ->subWeeks(2)
            ->format($dateFormat),
        ]),
        $this->createEvent([
          'field_date' => Carbon::today()
            ->subDays(2)
            ->format($dateFormat),
        ]),
        $this->createEvent([
          'field_date' => Carbon::today()
            ->addDays(4)
            ->format($dateFormat),
        ]),
        $this->createEvent([
          'field_date' => Carbon::today()
            ->addDays(10)
            ->format($dateFormat),
        ]),
      ],
    ]);

    $dateUpdater = $this->container->get(TalkDateUpdater::class);
    $dateUpdater->__invoke();

    $expected = Carbon::today()->addDays(4)->getTimestamp();

    $node = Node::load($talk->id());
    $talk = Talk::createFromNode($node);

    $this->assertNextEventDateIs($talk, $expected);
  }

  /** @test */
  public function talk_event_dates_are_set_to_the_last_past_date(): void {
    $dateFormat = DateTimeItemInterface::DATE_STORAGE_FORMAT;

    $talk = $this->createTalk([
      'field_event_date' => NULL,
      'field_events' => [
        $this->createEvent([
          'field_date' => Carbon::today()
            ->subDays(4)
            ->format($dateFormat),
        ]),
        $this->createEvent([
          'field_date' => Carbon::today()
            ->subDays(2)
            ->format($dateFormat),
        ]),
      ],
    ]);

    $dateUpdater = $this->container->get(TalkDateUpdater::class);
    $dateUpdater->__invoke();

    $expected = Carbon::today()->subDays(2)->getTimestamp();

    $node = Node::load($talk->id());
    $talk = Talk::createFromNode($node);

    $this->assertNextEventDateIs($talk, $expected);
  }

  /** @test */
  public function next_event_date_is_empty_if_there_are_no_events(): void {
    $talk = $this->createTalk([
      'field_event_date' => NULL,
      'field_events' => [],
    ]);

    $dateUpdater = $this->container->get(TalkDateUpdater::class);
    $dateUpdater->__invoke();

    $node = Node::load($talk->id());
    $talk = Talk::createFromNode($node);

    $this->assertNoNextEventDate($talk);
  }

  private function assertNextEventDateIs(Talk $talk, $expected): void {
    $this->assertSame($expected, $talk->getNextDate());
  }

  private function assertNoNextEventDate(Talk $talk): void {
    $this->assertNull($talk->getNextDate());
  }

}
