<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\opdavies_blog\Entity\Node\Post;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "opdavies_blog_push_post_to_social_media",
 *   title = "Push a blog post to social media",
 *   cron = {"time": 30}
 * )
 */
final class PostPusherQueueWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  private EntityStorageInterface $nodeStorage;

  public function __construct(
    array $configuration,
    string $pluginId,
    array $pluginDefinition,
    EntityStorageInterface $nodeStorage
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->nodeStorage = $nodeStorage;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $pluginId,
    $pluginDefinition
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('entity_type.manager')->getStorage('node')
    );
  }

  public function processItem($data): void {
    /** @var Post $post */
    ['post' => $post] = $data;

    if (!$this->shouldBePushed($post)) {
      return;
    }

    if (!$post->isLatestRevision()) {
      $post = $this->nodeStorage->load($post->id());

      // @phpstan-ignore-next-line
      if (!$this->shouldBePushed($post)) {
        return;
      }
    }

    // @phpstan-ignore-next-line
    $post->set(Post::FIELD_SENT_TO_SOCIAL_MEDIA, TRUE);
    $post->save();
  }

  private function shouldBePushed(Post $post): bool {
    if ($post->isExternalPost()) {
      return FALSE;
    }

    if (!$post->isPublished()) {
      return FALSE;
    }

    if (!$post->shouldSendToSocialMedia()) {
      return FALSE;
    }

    if ($post->hasBeenSentToSocialMedia()) {
      return FALSE;
    }

    return TRUE;
  }

}
