<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use InvalidArgumentException;
use RuntimeException;

class Valarm extends Component
{
    public const ACTION_AUDIO = 'AUDIO';
    public const ACTION_DISPLAY = 'DISPLAY';
    public const ACTION_EMAIL = 'EMAIL';
    public const NAME = 'VALARM';
    public const PROPERTY_ACTION = 'ACTION';
    public const PROPERTY_TRIGGER = 'TRIGGER';

    protected const CARDINALITY = [
        self::PROPERTY_ACTION => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_ATTENDEE => self::CARDINALITY_ONE_OR_MORE_MUST,
        self::PROPERTY_DESCRIPTION => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_DURATION => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_REPEAT => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_REQUEST_STATUS => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_SUMMARY => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_TRIGGER => self::CARDINALITY_ONE_MUST,
    ];

    protected const COMPONENTS = [];

    protected const PROPERTIES = [
        'common' => [
            self::PROPERTY_ACTION,
            self::PROPERTY_DURATION,
            self::PROPERTY_REPEAT,
            self::PROPERTY_REQUEST_STATUS,
            self::PROPERTY_TRIGGER,
        ],
        self::ACTION_AUDIO => [
            self::PROPERTY_ATTACH,
        ],
        self::ACTION_DISPLAY => [
            self::PROPERTY_DESCRIPTION,
        ],
        self::ACTION_EMAIL => [
            self::PROPERTY_ATTACH,
            self::PROPERTY_ATTENDEE,
            self::PROPERTY_DESCRIPTION,
            self::PROPERTY_SUMMARY,
        ]
    ];

    protected function cardinality(string $name): string
    {
        if ($name === self::PROPERTY_ATTACH) {
            $cardinality = match ($this->getProperty(self::PROPERTY_ACTION, 0)->getValue()) {
                self::ACTION_AUDIO => self::CARDINALITY_ONE_MAY,
                self::ACTION_EMAIL => self::CARDINALITY_ONE_OR_MORE_MAY,
                default => null
            };

            if ($cardinality === null) {
                throw new RuntimeException(
                    'Unable to determine cardinality for '
                    . self::NAME . '::' . self::PROPERTY_ATTACH
                    . ' - ensure ' . self::NAME . '::' . self::PROPERTY_ACTION . ' is set.'
                );
            }

            return $cardinality;
        }

        return self::CARDINALITY[$name];
    }

    protected function checkPropertyValid(string $name): void
    {
        $properties = array_merge(
            self::PROPERTIES['common'],
            $this->hasProperty(self::PROPERTY_ACTION)
                ? self::PROPERTIES[$this->getProperty(self::PROPERTY_ACTION, 0)->getValue()]
                : []
            ,
            self::$ianaProperties,
            self::$xProperties
        );

        if (!in_array($name, $properties)) {
            throw new InvalidArgumentException(strtr(
                '<property> not a valid property of <component>',
                [
                    '<property>' => $name,
                    '<component>' => $this->getName()
                ]
            ));
        }
    }
}
