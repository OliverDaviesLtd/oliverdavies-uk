<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\opdavies_blog\UseCase\ConvertPostToTweet;
use GuzzleHttp\ClientInterface;
use Webmozart\Assert\Assert;

final class IntegromatPostPusher extends WebhookPostPusher {

  use StringTranslationTrait;

  private ConfigFactoryInterface $configFactory;

  public function __construct(
    ConvertPostToTweet $convertPostToTweet,
    ClientInterface $client,
    ConfigFactoryInterface $configFactory
  ) {
    $this->convertPostToTweet = $convertPostToTweet;
    $this->configFactory = $configFactory;

    parent::__construct($convertPostToTweet, $client);
  }

  public function push(Post $post): void {
    $url = $this->configFactory
      ->get('opdavies_blog.settings')
      ->get('integromat_webhook_url');

    Assert::notNull($url, 'Cannot push the post if there is no URL.');

    $this->client->post($url, [
      'form_params' => [
        'text' => $this->t('@text', ['@text' => ($this->convertPostToTweet)($post)])
          ->render(),
      ],
    ]);
  }

}
