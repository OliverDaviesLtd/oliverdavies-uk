<?php

namespace Drupal\rollbar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for rollbar settings.
 */
class RollbarSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rollbar_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['rollbar.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('rollbar.settings');

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $config->get('enabled'),
    ];

    $form['access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Access Token'),
      '#default_value' => $config->get('access_token'),
      '#required' => TRUE,
    ];

    $form['capture_uncaught'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Capture Uncaught'),
      '#default_value' => $config->get('capture_uncaught'),
    ];

    $form['capture_unhandled_rejections'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Capture uncaught rejections'),
      '#default_value' => $config->get('capture_unhandled_rejections'),
    ];

    $form['environment'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Environment'),
      '#default_value' => $config->get('environment'),
      '#description' => $this->t('The environment string to use when reporting errors'),
    ];

    $form['log_level'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Log level'),
      '#default_value' => $config->get('log_level'),
      '#description' => $this->t('Selected log types will be send to Rollbar'),
      '#options' => [
        'Emergency',
        'Alert',
        'Critical',
        'Error',
        'Warning',
        'Notice',
        'Info' ,
        'Debug',
      ]
    ];

    $form['channels'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Filter channels'),
      '#default_value' => $config->get('channels'),
      '#description' => $this->t("Enter channels separated by ';' to prevent send them to rollbar"),
    ];

    $form['rollbar_js_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rollbar JS URL'),
      '#default_value' => $config->get('rollbar_js_url'),
      '#description' => $this->t('The URL to the Rollbar js library'),
    ];

    $form['host_white_list'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Host white list'),
      '#default_value' => $config->get('host_white_list'),
      '#description' => $this->t('List of hosts for which rollbar reports errors'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('rollbar.settings')
      ->set('access_token', $form_state->getValue('access_token'))
      ->set('enabled', $form_state->getValue('enabled'))
      ->set('capture_uncaught', $form_state->getValue('capture_uncaught'))
      ->set('capture_unhandled_rejections', $form_state->getValue('capture_unhandled_rejections'))
      ->set('environment', $form_state->getValue('environment'))
      ->set('log_level', $form_state->getValue('log_level'))
      ->set('channels', $form_state->getValue('channels'))
      ->set('rollbar_js_url', $form_state->getValue('rollbar_js_url'))
      ->set('host_white_list', $form_state->getValue('host_white_list'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
