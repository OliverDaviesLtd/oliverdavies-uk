<?php

declare(strict_types = 1);

namespace Drupal\Tests\custom\Kernel;

use Carbon\Carbon;
use Drupal\custom\Service\TalkCounter;
use Drupal\node\NodeInterface;
use PHPUnit\Framework\Assert;

class CountPreviousTalksTest extends TalksTestBase {

  private TalkCounter $talkCounter;

  /** @test */
  public function previous_talks_are_counted(): void {
    $this->createTalk([
      'field_events' => [
        $this->createEvent(),
        $this->createEvent(),
      ],
    ]);

    $this->createTalk([
      'field_events' => [
        $this->createEvent(),
      ],
    ]);

    Assert::assertSame(3, $this->talkCounter->getCount());
  }

  /** @test */
  public function future_talks_are_not_counted(): void {
    $this->createTalk([
      'field_events' => [
        $this->createEvent([
          'field_date' => Carbon::now()->subDay(),
        ]),
        $this->createEvent([
          'field_date' => Carbon::now()->addDay(),
        ]),
      ],
    ]);

    Assert::assertSame(1, $this->talkCounter->getCount());
  }

  /** @test */
  public function unpublished_talks_are_not_counted(): void {
    $this->createTalk([
      'field_events' => [$this->createEvent()],
      'status' => NodeInterface::NOT_PUBLISHED,
    ]);

    Assert::assertSame(0, $this->talkCounter->getCount());
  }

  protected function setUp() {
    parent::setUp();

    $this->talkCounter = $this->container->get(TalkCounter::class);
  }

}
