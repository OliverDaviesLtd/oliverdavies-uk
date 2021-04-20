<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\opdavies_blog\Action\ConvertPostToTweet;
use Drupal\opdavies_blog\Entity\Node\Post;
use GuzzleHttp\ClientInterface;
use Webmozart\Assert\Assert;

final class IftttPostPusher extends WebhookPostPusher {

  use StringTranslationTrait;

  private ConfigFactoryInterface $configFactory;

  public function __construct(
    ConvertPostToTweet $convertPostToTweetAction,
    ClientInterface $client,
    ConfigFactoryInterface $configFactory
  ) {
    $this->convertPostToTweetAction = $convertPostToTweetAction;
    $this->configFactory = $configFactory;

    parent::__construct($convertPostToTweetAction, $client);
  }

  public function push(Post $post): void {
    $url = $this->configFactory
      ->get('opdavies_blog.settings')
      ->get('post_tweet_webhook_url');

    Assert::notNull($url, 'Cannot push the post if there is no URL.');

    $this->client->post($url, [
      'form_params' => [
        'value1' => $this->t('Blogged: @text', ['@text' => ($this->convertPostToTweetAction)($post)])
          ->render(),
      ],
    ]);
  }

}
