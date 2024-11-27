<?php

namespace Drupal\time_show\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\time_show\Service\CurrentTimeService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a configuration form for Custom Config Form module.
 */
class TimeShowForm extends ConfigFormBase
{

    /**
     * The current time service.
     *
     * @var \Drupal\time_show\Service\CurrentTimeService
     */
    protected $currentTimeService;

    /**
     * Constructs a TimeShowForm object.
     *
     * @param \Drupal\time_show\Service\CurrentTimeService $currentTimeService
     *   The current time service.
     */
    public function __construct(CurrentTimeService $currentTimeService)
    {
        $this->currentTimeService = $currentTimeService;
    }


    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        // Here, the service is being injected from the container.
        return new static(
            $container->get('time_show.current_time_service') // Correctly inject the custom service.
        );
    }
  
    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['time_show.settings']; // Name of the configuration being edited.
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'time_show_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('time_show.settings');

        $form['country'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Country'),
        '#default_value' => $config->get('country'),
        '#description' => $this->t('Enter the country name.'),
        '#required' => true,
        ];

        $form['city'] = [
        '#type' => 'textfield',
        '#title' => $this->t('City'),
        '#default_value' => $config->get('city'),
        '#description' => $this->t('Enter the city name.'),
        '#required' => true,
        ];  

        $form['timezone'] = [
        '#type' => 'select',
        '#title' => $this->t('Timezone'),
        '#default_value' => $config->get('timezone'),
        '#options' => $this->getTimezones(),
        '#description' => $this->t('Select the timezone.'),
        '#required' => true,
        ];

        // Fetch the current time based on the stored timezone
        $current_time = $this->currentTimeService->getCurrentTime($config->get('timezone'));
    
        // Show the current time
        $form['current_time'] = [
        '#markup' => $this->t(
            'Current time in @timezone: @time', [
            '@timezone' => $config->get('timezone'),
            '@time' => $current_time,
            ]
        ),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
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
    protected function getTimezones()
    {
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
