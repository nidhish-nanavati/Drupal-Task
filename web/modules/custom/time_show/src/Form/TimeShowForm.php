<?php

namespace Drupal\time_show\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a configuration form for Custom Config Form module.
 */
class TimeShowForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['time_show.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'time_show_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('time_show.settings');

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
      '#description' => $this->t('Enter the country name.'),
      '#required' => TRUE,
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
      '#description' => $this->t('Enter the city name.'),
      '#required' => TRUE,
    ];  

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#default_value' => $config->get('timezone'),
      '#options' => $this->getTimezones(),
      '#description' => $this->t('Select the timezone.'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('time_show.settings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Helper function to get timezone options.
   */
  protected function getTimezones() {
    return [
      'America/New_York' => 'America/New_York',
      'America/Chicago' => 'America/Chicago',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London'
    ];
  }
}
