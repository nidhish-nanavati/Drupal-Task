<?php

namespace Drupal\time_show\Service;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Datetime\DateTimeZoneInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Service for getting current time based on the selected timezone.
 */
class CurrentTimeService
{

    /**
     * The date and time service.
     *
     * @var \Drupal\Core\Datetime\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * Constructs a CurrentTimeService object.
     *
     * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
     *   The date and time service.
     */
    public function __construct(DateFormatterInterface $dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;
    }

    /**
     * Returns the current time in the specified timezone.
     *
     * @param string $timezone
     *   The timezone to use (e.g., 'America/New_York').
     *
     * @return string
     *   The current time in the specified timezone,
     */
    public function getCurrentTime($timezone)
    {
        // Create a DateTime object with the specified timezone.
        $date = new \DateTime('now', new \DateTimeZone($timezone));
        
        // Format the date as "19 Sept 2022 - 11:15 AM"
        $formatted_time =  $date->format('j M Y - h:i A');

        return $formatted_time;
    }
}
