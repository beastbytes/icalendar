<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use BeastBytes\ICalendar\Exception\InvalidPropertyException;

class Property
{
    public const EQUALS = '=';
    public const GEO_SEPARATOR = ';';
    public const LIST_SEPARATOR = ',';
    public const PARAMETER_SEPARATOR = ';';
    public const PROPERTY_SEPARATOR = ':';
    public const RECUR_SEPARATOR = ';';

    private const LINE_LENGTH = 75;

    private const COLORS = [
        Component::COLOR_ALICE_BLUE,
        Component::COLOR_ANTIQUE_WHITE,
        Component::COLOR_AQUA,
        Component::COLOR_AQUAMARINE,
        Component::COLOR_AZURE,
        Component::COLOR_BEIGE,
        Component::COLOR_BISQUE,
        Component::COLOR_BLACK,
        Component::COLOR_BLANCHED_ALMOND,
        Component::COLOR_BLUE,
        Component::COLOR_BLUE_VIOLET,
        Component::COLOR_BROWN,
        Component::COLOR_BURLYWOOD,
        Component::COLOR_CADET_BLUE,
        Component::COLOR_CHARTREUSE,
        Component::COLOR_CHOCOLATE,
        Component::COLOR_CORAL,
        Component::COLOR_CORNFLOWER_BLUE,
        Component::COLOR_CORNSILK,
        Component::COLOR_CRIMSON,
        Component::COLOR_CYAN,
        Component::COLOR_DARK_BLUE,
        Component::COLOR_DARK_CYAN,
        Component::COLOR_DARK_GOLDENROD,
        Component::COLOR_DARK_GRAY,
        Component::COLOR_DARK_GREEN,
        Component::COLOR_DARK_GREY,
        Component::COLOR_DARK_KHAKI,
        Component::COLOR_DARK_MAGENTA,
        Component::COLOR_DARK_OLIVE_GREEN,
        Component::COLOR_DARK_ORANGE,
        Component::COLOR_DARK_ORCHID,
        Component::COLOR_DARK_RED,
        Component::COLOR_DARK_SALMON,
        Component::COLOR_DARK_SEA_GREEN,
        Component::COLOR_DARK_SLATE_BLUE,
        Component::COLOR_DARK_SLATE_GRAY,
        Component::COLOR_DARK_SLATE_GREY,
        Component::COLOR_DARK_TURQUOISE,
        Component::COLOR_DARK_VIOLET,
        Component::COLOR_DEEP_PINK,
        Component::COLOR_DEEP_SKY_BLUE,
        Component::COLOR_DIM_GRAY,
        Component::COLOR_DIM_GREY,
        Component::COLOR_DODGER_BLUE,
        Component::COLOR_FIREBRICK,
        Component::COLOR_FLORAL_WHITE,
        Component::COLOR_FOREST_GREEN,
        Component::COLOR_FUCHSIA,
        Component::COLOR_GAINSBORO,
        Component::COLOR_GHOST_WHITE,
        Component::COLOR_GOLD,
        Component::COLOR_GOLDENROD,
        Component::COLOR_GRAY,
        Component::COLOR_GREEN,
        Component::COLOR_GREEN_YELLOW,
        Component::COLOR_GREY,
        Component::COLOR_HONEYDEW,
        Component::COLOR_HOT_PINK,
        Component::COLOR_INDIAN_RED,
        Component::COLOR_INDIGO,
        Component::COLOR_IVORY,
        Component::COLOR_KHAKI,
        Component::COLOR_LAVENDER,
        Component::COLOR_LAVENDER_BLUSH,
        Component::COLOR_LAWN_GREEN,
        Component::COLOR_LEMON_CHIFFON,
        Component::COLOR_LIGHT_BLUE,
        Component::COLOR_LIGHT_CORAL,
        Component::COLOR_LIGHT_CYAN,
        Component::COLOR_LIGHT_GOLDENROD_YELLOW,
        Component::COLOR_LIGHT_GRAY,
        Component::COLOR_LIGHT_GREEN,
        Component::COLOR_LIGHT_GREY,
        Component::COLOR_LIGHT_PINK,
        Component::COLOR_LIGHT_SALMON,
        Component::COLOR_LIGHT_SEA_GREEN,
        Component::COLOR_LIGHT_SKY_BLUE,
        Component::COLOR_LIGHT_SLATE_GRAY,
        Component::COLOR_LIGHT_SLATE_GREY,
        Component::COLOR_LIGHT_STEEL_BLUE,
        Component::COLOR_LIGHT_YELLOW,
        Component::COLOR_LIME,
        Component::COLOR_LIME_GREEN,
        Component::COLOR_LINEN,
        Component::COLOR_MAGENTA,
        Component::COLOR_MAROON,
        Component::COLOR_MEDIUM_AQUAMARINE,
        Component::COLOR_MEDIUM_BLUE,
        Component::COLOR_MEDIUM_ORCHID,
        Component::COLOR_MEDIUM_PURPLE,
        Component::COLOR_MEDIUM_SEA_GREEN,
        Component::COLOR_MEDIUM_SLATE_BLUE,
        Component::COLOR_MEDIUM_SPRING_GREEN,
        Component::COLOR_MEDIUM_TURQUOISE,
        Component::COLOR_MEDIUM_VIOLET_RED,
        Component::COLOR_MIDNIGHT_BLUE,
        Component::COLOR_MINT_CREAM,
        Component::COLOR_MISTY_ROSE,
        Component::COLOR_MOCCASIN,
        Component::COLOR_NAVAJO_WHITE,
        Component::COLOR_NAVY,
        Component::COLOR_OLDLACE,
        Component::COLOR_OLIVE,
        Component::COLOR_OLIVE_DRAB,
        Component::COLOR_ORANGE,
        Component::COLOR_ORANGE_RED,
        Component::COLOR_ORCHID,
        Component::COLOR_PALE_GOLDENROD,
        Component::COLOR_PALE_GREEN,
        Component::COLOR_PALE_TURQUOISE,
        Component::COLOR_PALE_VIOLET_RED,
        Component::COLOR_PAPAYA_WHIP,
        Component::COLOR_PEACH_PUFF,
        Component::COLOR_PERU,
        Component::COLOR_PINK,
        Component::COLOR_PLUM,
        Component::COLOR_POWDER_BLUE,
        Component::COLOR_PURPLE,
        Component::COLOR_RED,
        Component::COLOR_ROSY_BROWN,
        Component::COLOR_ROYAL_BLUE,
        Component::COLOR_SADDLE_BROWN,
        Component::COLOR_SALMON,
        Component::COLOR_SANDY_BROWN,
        Component::COLOR_SEA_GREEN,
        Component::COLOR_SEASHELL,
        Component::COLOR_SIENNA,
        Component::COLOR_SILVER,
        Component::COLOR_SKY_BLUE,
        Component::COLOR_SLATE_BLUE,
        Component::COLOR_SLATE_GRAY,
        Component::COLOR_SLATE_GREY,
        Component::COLOR_SNOW,
        Component::COLOR_SPRING_GREEN,
        Component::COLOR_STEEL_BLUE,
        Component::COLOR_TAN,
        Component::COLOR_TEAL,
        Component::COLOR_THISTLE,
        Component::COLOR_TOMATO,
        Component::COLOR_TURQUOISE,
        Component::COLOR_VIOLET,
        Component::COLOR_WHEAT,
        Component::COLOR_WHITE,
        Component::COLOR_WHITE_SMOKE,
        Component::COLOR_YELLOW,
        Component::COLOR_YELLOW_GREEN,
    ];

    private const DATE_REGEX = '[0-9]{4}(((0[13578]|(10|12))(0[1-9]|[1-2][0-9]|3[0-1]))|(02(0[1-9]|[1-2][0-9]))|((0[469]|11)(0[1-9]|[1-2][0-9]|30)))';
    private const TIME_REGEX = 'T(?:[01]\d|2[0-3])[0-5]\d[0-5]\d';
    private const UTC_REGEX = 'Z';
    private const PERIOD_REGEX = 'P((\d+Y)?(\d+M)?(\d+W)?(\d+D)?)?(T(\d+H)?(\d+M)?(\d+S)?)?';

    private const STATUS = [
        Vevent::NAME => [
            Component::STATUS_CONFIRMED,
            Component::STATUS_CANCELLED,
            Component::STATUS_TENTATIVE,
        ],
        Vjournal::NAME => [
            Component::STATUS_CANCELLED,
            Component::STATUS_DRAFT,
            Component::STATUS_FINAL,
        ],
        Vtodo::NAME => [
            Component::STATUS_CANCELLED,
            Component::STATUS_COMPLETED,
            Component::STATUS_IN_PROCESS,
            Component::STATUS_NEEDS_ACTION,
        ],
    ];

    private const TRANSPARENCY = [
        Component::TRANSPARENCY_OPAQUE,
        Component::TRANSPARENCY_TRANSPARENT,
    ];

    private const VALIDATORS = [
        Component::PROPERTY_COLOR => ['in', 'list' => self::COLORS],
        Component::PROPERTY_DATETIME_COMPLETED => ['datetime'],
        Component::PROPERTY_DATETIME_CREATED => ['datetime'],
        Component::PROPERTY_DATETIME_END => ['datetime'],
        Component::PROPERTY_DATETIME_STAMP => ['datetime'],
        Component::PROPERTY_DATETIME_START => ['datetime'],
        Component::PROPERTY_DATETIME_DUE => ['datetime'],
        Component::PROPERTY_DURATION => ['duration'],
        Component::PROPERTY_EXCEPTION_DATE => ['datetime'],
        Component::PROPERTY_GEOGRAPHIC_POSITION => ['geo'],
        Component::PROPERTY_LAST_MODIFIED => ['datetime'],
        Component::PROPERTY_PERCENT_COMPLETE => ['integer', 'max' => 100],
        Component::PROPERTY_PRIORITY => ['integer'],
        Component::PROPERTY_RECURRENCE_ID => ['datetime'],
        Component::PROPERTY_REPEAT => ['integer'],
        Component::PROPERTY_SEQUENCE => ['integer'],
        Component::PROPERTY_STATUS => ['in', 'list' => self::STATUS],
        Component::PROPERTY_TIME_TRANSPARENCY => ['in', 'list' => self::TRANSPARENCY],
    ];

    private const LATITUDE = 90;
    private const LONGITUDE = 180;

    /** @var array $value */
    private array $value;

    public function __construct(
        private Component $component,
        private string $name,
        array|int|string $value,
        private array $parameters = []
    )
    {
        $this->validateValue($name, $value);

        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = [$value];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): string
    {
        $parameters = [];

        foreach ($this->parameters as $parameter => $value) {
            $parameters[] = $parameter . self::EQUALS . $value;
        }

        return empty($parameters)
            ? ''
            : self::PARAMETER_SEPARATOR . implode(self::PARAMETER_SEPARATOR, $parameters)
        ;
    }

    public function getValue(): string
    {
        return match ($this->getName()) {
            Vfreebusy::PROPERTY_FREEBUSY => $this->freebusy(),
            Component::PROPERTY_GEOGRAPHIC_POSITION => is_string($this->value)
                ? $this->value
                : implode(self::GEO_SEPARATOR, $this->value)
            ,
            Component::PROPERTY_RECURRENCE_RULE => $this->recur(),
            default => implode(self::LIST_SEPARATOR, $this->value)
        };
    }

    public function render(): string
    {
        return $this->fold(
            $this->getName()
            . $this->getParameters()
            . self::PROPERTY_SEPARATOR
            . $this->getValue()
        );
    }

    private function fold(string $line): string
    {
        if (strlen($line) > self::LINE_LENGTH) {
            $folded = [];

            $chars = mb_str_split($line);
            $byteCount = 0;
            $fold = '';

            do {
                $char = array_shift($chars);
                $byteCount += strlen($char);

                if ($byteCount <= self::LINE_LENGTH) {
                    $fold .= $char;
                } else {
                    $folded[] = $fold;
                    $fold = ' ' . $char;
                    $byteCount = strlen($fold);
                }
            } while (count($chars) > 0);
            $folded[] = $fold;

            $line = implode("\r\n", $folded);
        }

        return $line;
    }

    private function freebusy(): string
    {
        $freebusy = [];

        foreach ($this->value as $key => $value) {
            $freebusy[] = is_string($key) ? $key . Vfreebusy::FREEBUSY_SEPARATOR . $value : $value;
        }

        return implode(self::LIST_SEPARATOR, $freebusy);
    }

    private function recur(): string
    {
        $recur = [];

        foreach ($this->value as $key => $value) {
            $recur[] = $key . self::EQUALS . (
                is_array($value) ? implode(self::LIST_SEPARATOR, $value) : $value
            );
        }

        return implode(self::RECUR_SEPARATOR, $recur);
    }

    private function validateValue(string $property, $value): void
    {
        if (isset(self::VALIDATORS[$property])) {
            $validator = self::VALIDATORS[$property];
            $method = array_shift($validator);

            if (!$this->$method($property, $value, $validator)) {
                throw new InvalidPropertyException($this->component, $property, $value, 4);
            }
        }
    }

    private function datetime(string $property, string $value, array $parameters): bool
    {
        $pattern = match ($property) {
            Component::PROPERTY_DATETIME_COMPLETED,
            Component::PROPERTY_DATETIME_CREATED,
            Component::PROPERTY_DATETIME_STAMP,
            Component::PROPERTY_LAST_MODIFIED => self::DATE_REGEX . self::TIME_REGEX . self::UTC_REGEX,
            Component::PROPERTY_DATETIME_DUE,
            Component::PROPERTY_DATETIME_END,
            Component::PROPERTY_DATETIME_START,
            Component::PROPERTY_EXCEPTION_DATE,
            Component::PROPERTY_RECURRENCE_ID => self::DATE_REGEX . '(' . self::TIME_REGEX . self::UTC_REGEX . '?)?',
        };

        return (bool) preg_match("/$pattern/", $value);
    }

    private function duration(string $property, string $value): bool
    {
        $matches = [];
        return (bool) preg_match('/' . self::PERIOD_REGEX . '/', $value, $matches)
            && $matches[0] === $value
        ;
    }

    private function geo(string $property, array|string $value): bool
    {
        if (is_string($value)) {
            $value = explode(self::GEO_SEPARATOR, $value);
        }

        return (abs((float) $value[0]) <= self::LATITUDE && abs((float) $value[1]) <= self::LONGITUDE);
    }

    private function in(string $property, string $value, array $parameters): bool
    {
        if ($property === Component::PROPERTY_STATUS) {
            $list = $parameters['list'][$this->component->getName()];
        } else {
            $list = $parameters['list'];
        }
        return in_array($value, $list);
    }

    private function integer(string $property, int|string $value, array $parameters): bool
    {
        return ($value >= 0 && (isset($parameters['max']) ? $value <= $parameters['max'] : true));
    }
}
