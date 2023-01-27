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
    private const LINE_REGEX = '/^([A-Z]+)((;[-A-Z]+=(".+"|.+?))*):(.+)$/';
    private const VALUE_SPLIT_REGEX = '/(?<!\\\),/';

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
        do {
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
            } elseif (!str_starts_with($line, Component::END . Property::PROPERTY_SEPARATOR)) {
                $component = self::importProperty($component, $line);
            }
        } while (count(self::$lines) > 0);

        return $component;
    }

    private static function importProperty(Component $component, string $line): Component
    {
        $property = [];
        if (!preg_match(self::LINE_REGEX, $line, $property)) {
            throw new InvalidArgumentException("Invalid iCalendar property: $line");
        }

        $parameters = (!empty($property[2])
            ? explode(Property::PARAMETER_SEPARATOR, substr($property[2], 1))
            : []
        );

        $keys = [];
        $values = [];
        foreach ($parameters as $parameter) {
            $parameter = explode(Property::EQUALS, $parameter);
            $keys[] = $parameter[0];
            $values[] = $parameter[1];
        }

        return $component->addProperty(
            $property[1],
            preg_split(self::VALUE_SPLIT_REGEX, $property[5]),
            array_combine($keys, $values)
        );
    }
}
