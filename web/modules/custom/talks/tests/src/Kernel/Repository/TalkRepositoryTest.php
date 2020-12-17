<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_talks\Kernel\Repository;

use Drupal\node\NodeInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\opdavies_talks\Repository\TalkRepository;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\Tests\opdavies_talks\Kernel\TalksTestBase;

final class TalkRepositoryTest extends TalksTestBase {

  use NodeCreationTrait;

  private TalkRepository $talkRepository;

  /** @test */
  public function get_all_talks(): void {
    $this->createTalk(['title' => 'TDD - Test Driven Drupal']);
    $this->createTalk(['title' => 'Taking Flight with Tailwind CSS']);
    $this->createTalk(['title' => 'Upgrading to Drupal 9']);

    $talks = $this->talkRepository->getAll();

    $this->assertCount(3, $talks);
    $this->assertSame(
      [
        1 => 'TDD - Test Driven Drupal',
        2 => 'Taking Flight with Tailwind CSS',
        3 => 'Upgrading to Drupal 9',
      ],
      $talks->map(fn(Talk $talk) => $talk->label())->toArray()
    );
  }

  /** @test */
  public function get_all_published_talks(): void {
    $this->createTalk([
      'title' => 'TDD - Test Driven Drupal',
      'status' => NodeInterface::PUBLISHED,
    ]);

    $this->createTalk([
      'title' => 'Taking Flight with Tailwind CSS',
      'status' => NodeInterface::NOT_PUBLISHED,
    ]);

    $talks = $this->talkRepository->getAll(TRUE);

    $this->assertCount(1, $talks);
    $this->assertSame('TDD - Test Driven Drupal', $talks->first()->label());
  }

  /** @test */
  public function it_only_returns_talk_nodes(): void {
    $this->createNode(['type' => 'page']);

    $talks = $this->talkRepository->getAll();

    $this->assertEmpty($talks);
  }

  protected function setUp() {
    parent::setUp();

    $this->installConfig(['filter']);

    $this->talkRepository = $this->container->get(TalkRepository::class);
  }

}
