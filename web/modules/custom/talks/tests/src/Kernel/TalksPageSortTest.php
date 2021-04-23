<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_talks\Kernel;

use Carbon\Carbon;
use Drupal\views\ResultRow;
use Illuminate\Support\Collection;

final class TalksPageSortTest extends TalksTestBase {

  public static $modules = [
    'views',
    'opdavies_talks',
  ];

  /**
   * @test
   */
  public function upcoming_talks_are_shown_first_followed_by_past_talks_and_ordered_by_distance(): void {
    $this->createTalk([
      'field_event_date' => Carbon::today()->addDays(4)->getTimestamp(),
    ]);
    $this->createTalk([
      'field_event_date' => Carbon::today()->subDays(2)->getTimestamp(),
    ]);
    $this->createTalk([
      'field_event_date' => Carbon::today()->addDay()->getTimestamp(),
    ]);
    $this->createTalk([
      'field_event_date' => Carbon::today()->subDays(10)->getTimestamp(),
    ]);

    $talkIds = (new Collection(views_get_view_result('talks')))
      ->map(fn(ResultRow $row) => (int) $row->_entity->id());

    $this->assertSame([3, 1, 2, 4], $talkIds->toArray());
  }

}
