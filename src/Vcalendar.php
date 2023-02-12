<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use BeastBytes\ICalendar\Exception\InvalidComponentException;
use BeastBytes\ICalendar\Exception\InvalidPropertyException;
use UnhandledMatchError;

class Vcalendar extends Component
{
    public const NAME = 'VCALENDAR';
    public const VERSION = '2.0';

    public const PROPERTY_NAME = 'NAME';
    public const PROPERTY_REFRESH_INTERVAL = 'REFRESH-INTERVAL';
    public const PROPERTY_SOURCE = 'SOURCE';

    public const CARDINALITY = [
        self::PROPERTY_CALENDAR_SCALE => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_CATEGORIES => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_COLOR => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_CONFERENCE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_DESCRIPTION => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_IMAGE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_LAST_MODIFIED => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_METHOD => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_NAME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_PRODUCT_IDENTIFIER => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_REFRESH_INTERVAL => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_SOURCE => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_UID => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_VERSION => self::CARDINALITY_ONE_MUST,
    ];

    protected const COMPONENTS = [
        Vevent::NAME,
        Vfreebusy::NAME,
        Vjournal::NAME,
        Vtimezone::NAME,
        Vtodo::NAME
    ];

    private const LINE_REGEX = '/^([-A-Z]+)((;[-A-Z]+=(".+"|.+?))*):(.+)$/';
    private const VALUE_SPLIT_REGEX = '/(?<!\\\),/';

    /**
     * @var list<string> $nonStandardComponents
     */
    protected static array $nonStandardComponents = [];

    public function __construct()
    {
        parent::__construct();

        $this->properties = [
            self::PROPERTY_VERSION => [new Property(self::PROPERTY_VERSION, self::VERSION)]
        ];
    }

    public static function registerNonStandardComponent(string $name): void
    {
        if (!in_array($name, self::$nonStandardComponents, true)) {
            self::$nonStandardComponents[] = $name;
        }
    }

    public static function import(string $icalendar): Vcalendar
    {
        $icalendar = str_replace(array("\r\n", "\n\r", "\n", "\r"), "\n", $icalendar);
        $icalendar = str_replace(array("\n ", "\n\t"),"", $icalendar);
        $icalendar = trim($icalendar, "\n");
        self::$lines = explode("\n", $icalendar);

        if (array_shift(self::$lines) !== Component::BEGIN . Property::PROPERTY_SEPARATOR . self::NAME) {
            throw new InvalidComponentException();
        }

        return self::importComponent(new Vcalendar());
    }

    private static function importComponent(Component $component): Component
    {
        while (true) {
            $line = array_shift(self::$lines);

            if (str_starts_with($line, Component::BEGIN . Property::PROPERTY_SEPARATOR)) {
                try {
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
                } catch (UnhandledMatchError $e) {
                    throw new InvalidComponentException($component, new Vcalendar());
                }
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
            throw new InvalidPropertyException($component, $line, 3);
        }

        if (!empty($property[2])) {
            $keys = [];
            $values = [];

            foreach (explode(Property::PARAMETER_SEPARATOR, substr($property[2], 1)) as $parameter) {
                [$keys[], $values[]] = explode(Property::EQUALS, $parameter);
            }

            $parameters = array_combine($keys, $values);
        } else {
            $parameters = [];
        }

        if ($property[1] === Vcalendar::PROPERTY_VERSION) {
            return $component
                ->setProperty(
                    $property[1],
                    0,
                    self::parseValue($property[1], $property[5]),
                    $parameters
                )
            ;
        }

        return $component
            ->addProperty(
                $property[1],
                self::parseValue($property[1], $property[5]),
                $parameters
            )
        ;
    }

    public static function parseValue(string $name, string $value): array|string
    {
        return match ($name) {
            Vfreebusy::PROPERTY_FREEBUSY => self::freebusy($value),
            self::PROPERTY_RECURRENCE_RULE => self::rrule($value),
            default => self::property($value)
        };
    }

    public static function freebusy(string $value): array
    {
        $keys = [];
        $values = [];

        foreach (preg_split(self::VALUE_SPLIT_REGEX, $value) as $item) {
            [$keys[], $values[]] = explode(Vfreebusy::FREEBUSY_SEPARATOR, $item);
        }

        return array_combine($keys, $values);
    }

    public static function property(string $value): array|string
    {
        $result = preg_split(self::VALUE_SPLIT_REGEX, $value);

        return count($result) === 1 ? $result[0] : $result;
    }

    public static function rrule(string $value): array
    {
        $keys = [];
        $values = [];

        foreach (explode(Property::RECUR_SEPARATOR, $value) as $item) {
            [$keys[], $values[]] = explode(Property::EQUALS, $item);
        }

        foreach ($values as &$v) {
            $v = explode(Property::LIST_SEPARATOR, $v);
        }

        return array_combine($keys, $values);
    }

    protected function checkComponentValid(Component $component): void
    {
        if (!in_array(
            $component->getName(),
            array_merge(static::COMPONENTS, self::$nonStandardComponents),
            true
        )) {
            throw new InvalidComponentException($this, $component);
        }
    }
}
