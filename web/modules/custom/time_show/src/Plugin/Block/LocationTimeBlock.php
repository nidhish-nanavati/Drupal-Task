<?php

namespace Drupal\time_show\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\time_show\Service\CurrentTimeService;

/**
 * Provides a 'Location and Time' Block.
 *
 * @Block(
 *   id = "location_time_block",
 *   admin_label = @Translation("Location and Time Block"),
 *   category = @Translation("Custom")
 * )
 */
class LocationTimeBlock extends BlockBase implements ContainerFactoryPluginInterface
{

    /**
     * The CurrentTimeService.
     *
     * @var \Drupal\time_show\Service\CurrentTimeService
     */
    protected $currentTimeService;

    /**
     * The configuration factory.
     *
     * @var \Drupal\Core\Plugin\ContainerFactoryPluginInterface;
     */
    protected ConfigFactoryInterface $configFactory;

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('config.factory'),
            $container->get('time_show.current_time_service')
        );
    }

    /**
     * {@inheritdoc}
     */  
    public function __construct(array $configuration, $plugin_id, $plugin_definition)
    {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
    }  
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // Load the configuration for the block.
        $config = \Drupal::config('time_show.settings');

        // Retrieve the configuration values.
        $country = $config->get('country');
        $city = $config->get('city');
        $timezone = $config->get('timezone');

        // Get the current time using the CurrentTimeService.
        $current_time = \Drupal::service('time_show.current_time_service')->getCurrentTime($timezone);

        // Return the block output with the time and location.
        $build = [
            '#markup' => $this->t(
                'Location: @city, @country<br>Time in @timezone: @time', [
                    '@city' => $city,
                    '@country' => $country,
                    '@timezone' => $timezone,
                    '@time' => $current_time,
                ]
            ),
        ];

        // Add cache metadata.
        $build['#cache'] = [
            'tags' => ['config:time_show.settings'], // Invalidate cache when configuration changes.
            'max-age' => 60,
        ];

        return $build;
    }

}
