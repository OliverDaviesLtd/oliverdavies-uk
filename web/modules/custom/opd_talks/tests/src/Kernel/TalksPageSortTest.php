<?php

declare(strict_types=1);

namespace Drupal\Tests\opd_talks\Kernel;

use Carbon\Carbon;
use Drupal\Tests\custom\Kernel\TalksTestBase;
use Drupal\views\ResultRow;
use Illuminate\Support\Collection;

final class TalksPageSortTest extends TalksTestBase {

  public static $modules = [
    'views',
    'opd_talks',
  ];

  /**
   * @test
   */
  public function upcoming_talks_are_shown_first_followed_by_past_talks_and_ordered_by_distance() {
    $this->createTalk(['created' => Carbon::parse('+4 days')->getTimestamp()]);
    $this->createTalk(['created' => Carbon::parse('-2 days')->getTimestamp()]);
    $this->createTalk(['created' => Carbon::parse('+1 days')->getTimestamp()]);
    $this->createTalk(['created' => Carbon::parse('-10 days')->getTimestamp()]);

    $talkIds = (new Collection(views_get_view_result('talks')))
      ->map(fn(ResultRow $row) => (int) $row->_entity->id());

    $this->assertSame([3, 1, 2, 4], $talkIds->toArray());
  }

}
