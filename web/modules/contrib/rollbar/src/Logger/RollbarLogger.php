<?php

namespace Drupal\rollbar\Logger;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Logger\RfcLogLevel;
use Psr\Log\LoggerInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Rollbar\Rollbar;
use Rollbar\Payload\Level as RollbarLogLevel;

/**
 * Redirects logging messages to Rollbar.
 */
class RollbarLogger implements LoggerInterface {
  use RfcLoggerTrait;

  /**
   * A configuration object containing rollbar settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The message's placeholders parser.
   *
   * @var \Drupal\Core\Logger\LogMessageParserInterface
   */
  protected $parser;

  /**
   * Checks if the Rollbar is initialized.
   *
   * @var bool
   */
  private $isInitialized = FALSE;

  /**
   * Constructs a Rollbar object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
   *   The parser to use when extracting message variables.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LogMessageParserInterface $parser) {
    $this->config = $config_factory->get('rollbar.settings');
    $this->parser = $parser;
  }

  /**
   * Initialize rollbar object.
   */
  protected function init() {
    $token = $this->config->get('access_token');
    $environment = $this->config->get('environment');

    if (empty($token) || empty($environment)) {
      return FALSE;
    }

    if (!$this->isInitialized) {
      Rollbar::init(['access_token' => $token, 'environment' => $environment]);
      $this->isInitialized = TRUE;
    }

    return TRUE;
  }
  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = []) {
    if (!$this->init()) {
      return;
    }
    $enabled_log_levels = $this->config->get('log_level');
    $log_level_condition = !in_array($level, $enabled_log_levels);
    $omit_channel = array_map('trim', explode(";", $this->config->get('channels')));
    $omit_channel_condition = isset($context['channel']) && in_array($context['channel'], $omit_channel);
    if ($log_level_condition || $omit_channel_condition) {
        return;
    }
    $level_map = [
      RfcLogLevel::EMERGENCY => RollbarLogLevel::critical(),
      RfcLogLevel::ALERT =>  RollbarLogLevel::critical(),
      RfcLogLevel::CRITICAL =>  RollbarLogLevel::critical(),
      RfcLogLevel::ERROR =>  RollbarLogLevel::error(),
      RfcLogLevel::WARNING =>  RollbarLogLevel::warning(),
      RfcLogLevel::NOTICE =>  RollbarLogLevel::info(),
      RfcLogLevel::INFO =>  RollbarLogLevel::info(),
      RfcLogLevel::DEBUG =>  RollbarLogLevel::debug(),
    ];

    // Populate the message placeholders and then replace them in the message.
    $message_placeholders = $this->parser->parseMessagePlaceholders($message, $context);
    $message = empty($message_placeholders) ? $message : strtr($message, $message_placeholders);
    Rollbar::log($level_map[$level], $message, $context);
  }

}

