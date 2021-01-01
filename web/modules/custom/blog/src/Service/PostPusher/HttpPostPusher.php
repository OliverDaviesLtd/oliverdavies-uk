<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use GuzzleHttp\ClientInterface;

abstract class HttpPostPusher implements PostPusher {

  protected ClientInterface $client;

  public function __construct(ClientInterface $client) {
    $this->client = $client;
  }

}
