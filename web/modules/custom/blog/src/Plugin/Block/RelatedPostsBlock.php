<?php

namespace Drupal\opdavies_blog\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\opdavies_blog\Repository\RelatedPostsRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tightenco\Collect\Support\Collection;

/**
 * @Block(
 *   id = "opdavies_blog_related_posts",
 *   admin_label = @Translation("Related Posts"),
 *   category = @Translation("Blog")
 * )
 */
class RelatedPostsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  private CurrentRouteMatch $currentRouteMatch;

  private RelatedPostsRepository $relatedPostsRepository;

  public function __construct(
    array $configuration,
    string $pluginId,
    array $pluginDefinition,
    CurrentRouteMatch $currentRouteMatch,
    RelatedPostsRepository $relatedPostsRepository
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->currentRouteMatch = $currentRouteMatch;
    $this->relatedPostsRepository = $relatedPostsRepository;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $pluginId,
    $pluginDefinition
  ): self {
    // @phpstan-ignore-next-line
    return new self(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('current_route_match'),
      $container->get(RelatedPostsRepository::class)
    );
  }

  public function build(): array {
    $currentPost = $this->currentRouteMatch->getParameter('node');

    /** @var Collection|Post[] $relatedPosts */
    $relatedPosts = $this->relatedPostsRepository->getFor($currentPost);

    if ($relatedPosts->isEmpty()) {
      return [];
    }

    $build['content'] = [
      '#cache' => [
        'max-age' => 604800,
        'tags' => ["node:{$currentPost->id()}"],
      ],
      '#theme' => 'item_list',
      '#items' => $relatedPosts
        ->sortByDesc(fn(Post $post) => $post->getCreatedTime())
        ->map(fn(Post $post) => $this->generateLinkToPost($post))
        ->slice(0, 3)
        ->toArray(),
    ];

    return $build;
  }

  private function generateLinkToPost(Post $post): Link {
    return Link::createFromRoute(
      $post->getTitle(),
      'entity.node.canonical',
      ['node' => $post->id()]
    );
  }

}
