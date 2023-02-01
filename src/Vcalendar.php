<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use InvalidArgumentException;

class Vcalendar extends Component
{
    public const NAME = 'VCALENDAR';
    public const VERSION = '2.0';

    protected const CARDINALITY = [
        self::PROPERTY_CALSCALE => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_METHOD => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_PRODID => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_VERSION => self::CARDINALITY_ONE_MUST,
    ];

    private const LINE_REGEX = '/^([-A-Z]+)((;[-A-Z]+=(".+"|.+?))*):(.+)$/';
    private const VALUE_SPLIT_REGEX = '/(?<!\\\),/';

    public function __construct()
    {
        $this->properties = [
            self::PROPERTY_VERSION => [new Property(self::PROPERTY_VERSION, self::VERSION)]
        ];
    }

    public static function import(string $icalendar): Vcalendar
    {
        $icalendar = str_replace(array("\r\n", "\n\r", "\n", "\r"), "\n", $icalendar);
        $icalendar = str_replace(array("\n ", "\n\t"),"", $icalendar);
        $icalendar = trim($icalendar, "\n");
        self::$lines = explode("\n", $icalendar);

        if (array_shift(self::$lines) !== Component::BEGIN . Property::PROPERTY_SEPARATOR . self::NAME) {
            throw new InvalidArgumentException('Invalid iCalendar');
        }

        return self::importComponent(new Vcalendar());
    }

    private static function importComponent(Component $component): Component
    {
        while (true) {
            $line = array_shift(self::$lines);

            if (str_starts_with($line, Component::BEGIN . Property::PROPERTY_SEPARATOR)) {
                $childComponent = match (substr($line, 6)) {
                    Daylight::NAME => new Daylight(),
                    Standard::NAME => new Standard(),
                    Valarm::NAME => new Valarm(),
                    Vevent::NAME => new Vevent(),
                    Vfreebusy::NAME => new Vfreebusy(),
                    Vjournal::NAME => new Vjournal(),
                    Vtimezone::NAME => new Vtimezone(),
                    Vtodo::NAME => new Vtodo()
                };
                $component = $component->addComponent(self::importComponent($childComponent));
            } elseif (str_starts_with($line, Component::END . Property::PROPERTY_SEPARATOR)) {
                return $component;
            } else {
                $component = self::importProperty($component, $line);
            }
        }
    }

    private static function importProperty(Component $component, string $line): Component
    {
        $property = [];
        if (!preg_match(self::LINE_REGEX, $line, $property)) {
            throw new InvalidArgumentException("Invalid iCalendar property: $line");
        }

        if (!empty($property[2])) {
            $keys = [];
            $values = [];

            foreach (explode(Property::PARAMETER_SEPARATOR, substr($property[2], 1)) as $parameter) {
                list($keys[], $values[]) = explode(Property::EQUALS, $parameter);
            }

            $parameters = array_combine($keys, $values);
        } else {
            $parameters = [];
        }

        return $component->addProperty(
            $property[1],
            self::parseValue($property[1], $property[5]),
            $parameters
        );
    }

    public static function parseValue(string $name, string $value): array|string
    {
        return match ($name) {
            Vfreebusy::PROPERTY_FREEBUSY => self::freebusy($value),
            self::PROPERTY_RRULE => self::rrule($value),
            default => self::property($value)
        };
    }

    public static function freebusy(string $value): array
    {
        $keys = [];
        $values = [];

        foreach (preg_split(self::VALUE_SPLIT_REGEX, $value) as $item) {
            list($keys[], $values[]) = explode(Vfreebusy::FREEBUSY_SEPARATOR, $item);
        }

        return array_combine($keys, $values);
    }

    public static function property(string $value): array|string
    {
        $result = preg_split(self::VALUE_SPLIT_REGEX, $value);

        return sizeof($result) === 1 ? $result[0] : $result;
    }

    public static function rrule(string $value): array
    {
        $keys = [];
        $values = [];

        foreach (explode(Property::RECUR_SEPARATOR, $value) as $item) {
            list($keys[], $values[]) = explode(Property::EQUALS, $item);
        }

        foreach ($values as &$v) {
            $v = explode(Property::LIST_SEPARATOR, $v);
        }

        return array_combine($keys, $values);
    }
}
