<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use Drupal\opdavies_blog\UseCase\ConvertPostToTweet;
use GuzzleHttp\ClientInterface;

abstract class WebhookPostPusher implements PostPusher {

  protected ConvertPostToTweet $convertPostToTweet;

  protected ClientInterface $client;

  public function __construct(
    ConvertPostToTweet $convertPostToTweet,
    ClientInterface $client
  ) {

    $this->convertPostToTweet = $convertPostToTweet;
    $this->client = $client;
  }

}
