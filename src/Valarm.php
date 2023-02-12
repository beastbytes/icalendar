<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use BeastBytes\ICalendar\Exception\InvalidPropertyException;
use BeastBytes\ICalendar\Exception\MissingPropertyException;

class Valarm extends Component
{
    public const ACTION_AUDIO = 'AUDIO';
    public const ACTION_DISPLAY = 'DISPLAY';
    public const ACTION_EMAIL = 'EMAIL';
    public const NAME = 'VALARM';
    public const PROPERTY_ACTION = 'ACTION';
    public const PROPERTY_TRIGGER = 'TRIGGER';

    public const CARDINALITY = [
        self::PROPERTY_ACTION => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_DURATION => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_REPEAT => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_REQUEST_STATUS => self::CARDINALITY_ONE_MAY,
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

    private array $propertyCardinality = [];

    public function getCardinality(string $property): string
    {
        if (
            in_array($property, self::PROPERTIES[self::ACTION_EMAIL], true)
            && !$this->hasProperty(self::PROPERTY_ACTION)
        ) {
            return '';
        }

        return match ($property) {
            self::PROPERTY_ATTACH => match (
                $this->getProperty(self::PROPERTY_ACTION, 0)
                     ->getValue()
            ) {
                self::ACTION_AUDIO => self::CARDINALITY_ONE_MAY,
                self::ACTION_EMAIL => self::CARDINALITY_ONE_OR_MORE_MAY,
                default => ''
            },
            self::PROPERTY_ATTENDEE => match (
                $this->getProperty(self::PROPERTY_ACTION, 0)
                     ->getValue()
            ) {
                self::ACTION_EMAIL => self::CARDINALITY_ONE_OR_MORE_MUST,
                default => ''
            },
            self::PROPERTY_DESCRIPTION => match (
                $this->getProperty(self::PROPERTY_ACTION, 0)
                     ->getValue()
            ) {
                self::ACTION_DISPLAY, self::ACTION_EMAIL => self::CARDINALITY_ONE_MUST,
                default => ''
            },
            self::PROPERTY_SUMMARY => match (
                $this->getProperty(self::PROPERTY_ACTION, 0)
                     ->getValue()
            ) {
                self::ACTION_EMAIL => self::CARDINALITY_ONE_MUST,
                default => ''
            },
            default => self::CARDINALITY[$property]
        };
    }

    protected function checkPropertyValid(string $name): void
    {
        $properties = array_merge(
            self::PROPERTIES['common'],
            $this->hasProperty(self::PROPERTY_ACTION)
                ? self::PROPERTIES[$this->getProperty(self::PROPERTY_ACTION, 0)->getValue()]
                : []
            ,
            self::$nonStandardProperties
        );

        if (!in_array($name, $properties)) {
            if (
                $this->hasProperty(self::PROPERTY_ACTION)
                && in_array($name, self::PROPERTIES[self::ACTION_EMAIL], true) // contains all possibilities
            ) {
                throw new InvalidPropertyException($this, $name, 1);
            }

            throw new InvalidPropertyException($this, $name);
        }
    }

    protected function hasRequiredProperties(): void
    {
        if (empty($this->propertyCardinality)) {
            $this->propertyCardinality = static::CARDINALITY;

            foreach (self::PROPERTIES[self::ACTION_EMAIL] as $property) {
                $this->propertyCardinality[$property] = $this->getCardinality($property);
            }
        }

        foreach ($this->propertyCardinality as $property => $cardinality) {
            if (
                in_array($cardinality, [self::CARDINALITY_ONE_MUST, self::CARDINALITY_ONE_OR_MORE_MUST])
                && !$this->hasProperty($property)
            ) {
                throw new MissingPropertyException($this, $property);
            }
        }
    }
}
