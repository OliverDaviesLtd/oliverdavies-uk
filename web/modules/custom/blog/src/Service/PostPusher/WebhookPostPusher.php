<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use Drupal\opdavies_blog\Action\ConvertPostToTweet;
use GuzzleHttp\ClientInterface;

abstract class WebhookPostPusher implements PostPusher {

  protected ConvertPostToTweet $convertPostToTweetAction;

  protected ClientInterface $client;

  public function __construct(
    ConvertPostToTweet $convertPostToTweetAction,
    ClientInterface $client
  ) {
    $this->convertPostToTweetAction = $convertPostToTweetAction;
    $this->client = $client;
  }

}
