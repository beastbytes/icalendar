<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

use BeastBytes\ICalendar\Exception\InvalidComponentException;
use BeastBytes\ICalendar\Exception\InvalidPropertyException;
use BeastBytes\ICalendar\Exception\MissingPropertyException;

abstract class Component
{
    public const CARDINALITY_ONE_MAY = '*1';
    public const CARDINALITY_ONE_MUST = '1';
    public const CARDINALITY_ONE_OR_MORE_MAY = '*';
    public const CARDINALITY_ONE_OR_MORE_MUST = '1*';

    public const CLASSIFICATION_CONFIDENTIAL = 'CONFIDENTIAL';
    public const CLASSIFICATION_PRIVATE = 'PRIVATE';
    public const CLASSIFICATION_PUBLIC = 'PUBLIC';

    public const COLOR_ALICE_BLUE = 'aliceblue';
    public const COLOR_ANTIQUE_WHITE = 'antiquewhite';
    public const COLOR_AQUA = 'aqua';
    public const COLOR_AQUAMARINE = 'aquamarine';
    public const COLOR_AZURE = 'azure';
    public const COLOR_BEIGE = 'beige';
    public const COLOR_BISQUE = 'bisque';
    public const COLOR_BLACK = 'black';
    public const COLOR_BLANCHED_ALMOND = 'blanchedalmond';
    public const COLOR_BLUE = 'blue';
    public const COLOR_BLUE_VIOLET = 'blueviolet';
    public const COLOR_BROWN = 'brown';
    public const COLOR_BURLYWOOD = 'burlywood';
    public const COLOR_CADET_BLUE = 'cadetblue';
    public const COLOR_CHARTREUSE = 'chartreuse';
    public const COLOR_CHOCOLATE = 'chocolate';
    public const COLOR_CORAL = 'coral';
    public const COLOR_CORNFLOWER_BLUE = 'cornflowerblue';
    public const COLOR_CORNSILK = 'cornsilk';
    public const COLOR_CRIMSON = 'crimson';
    public const COLOR_CYAN = 'cyan';
    public const COLOR_DARK_BLUE = 'darkblue';
    public const COLOR_DARK_CYAN = 'darkcyan';
    public const COLOR_DARK_GOLDENROD = 'darkgoldenrod';
    public const COLOR_DARK_GRAY = 'darkgray';
    public const COLOR_DARK_GREEN = 'darkgreen';
    public const COLOR_DARK_GREY = 'darkgrey';
    public const COLOR_DARK_KHAKI = 'darkkhaki';
    public const COLOR_DARK_MAGENTA = 'darkmagenta';
    public const COLOR_DARK_OLIVE_GREEN = 'darkolivegreen';
    public const COLOR_DARK_ORANGE = 'darkorange';
    public const COLOR_DARK_ORCHID = 'darkorchid';
    public const COLOR_DARK_RED = 'darkred';
    public const COLOR_DARK_SALMON = 'darksalmon';
    public const COLOR_DARK_SEA_GREEN = 'darkseagreen';
    public const COLOR_DARK_SLATE_BLUE = 'darkslateblue';
    public const COLOR_DARK_SLATE_GRAY = 'darkslategray';
    public const COLOR_DARK_SLATE_GREY = 'darkslategrey';
    public const COLOR_DARK_TURQUOISE = 'darkturquoise';
    public const COLOR_DARK_VIOLET = 'darkviolet';
    public const COLOR_DEEP_PINK = 'deeppink';
    public const COLOR_DEEP_SKY_BLUE = 'deepskyblue';
    public const COLOR_DIM_GRAY = 'dimgray';
    public const COLOR_DIM_GREY = 'dimgrey';
    public const COLOR_DODGER_BLUE = 'dodgerblue';
    public const COLOR_FIREBRICK = 'firebrick';
    public const COLOR_FLORAL_WHITE = 'floralwhite';
    public const COLOR_FOREST_GREEN = 'forestgreen';
    public const COLOR_FUCHSIA = 'fuchsia';
    public const COLOR_GAINSBORO = 'gainsboro';
    public const COLOR_GHOST_WHITE = 'ghostwhite';
    public const COLOR_GOLD = 'gold';
    public const COLOR_GOLDENROD = 'goldenrod';
    public const COLOR_GRAY = 'gray';
    public const COLOR_GREEN = 'green';
    public const COLOR_GREEN_YELLOW = 'greenyellow';
    public const COLOR_GREY = 'grey';
    public const COLOR_HONEYDEW = 'honeydew';
    public const COLOR_HOT_PINK = 'hotpink';
    public const COLOR_INDIAN_RED = 'indianred';
    public const COLOR_INDIGO = 'indigo';
    public const COLOR_IVORY = 'ivory';
    public const COLOR_KHAKI = 'khaki';
    public const COLOR_LAVENDER = 'lavender';
    public const COLOR_LAVENDER_BLUSH = 'lavenderblush';
    public const COLOR_LAWN_GREEN = 'lawngreen';
    public const COLOR_LEMON_CHIFFON = 'lemonchiffon';
    public const COLOR_LIGHT_BLUE = 'lightblue';
    public const COLOR_LIGHT_CORAL = 'lightcoral';
    public const COLOR_LIGHT_CYAN = 'lightcyan';
    public const COLOR_LIGHT_GOLDENROD_YELLOW = 'lightgoldenrodyellow';
    public const COLOR_LIGHT_GRAY = 'lightgray';
    public const COLOR_LIGHT_GREEN = 'lightgreen';
    public const COLOR_LIGHT_GREY = 'lightgrey';
    public const COLOR_LIGHT_PINK = 'lightpink';
    public const COLOR_LIGHT_SALMON = 'lightsalmon';
    public const COLOR_LIGHT_SEA_GREEN = 'lightseagreen';
    public const COLOR_LIGHT_SKY_BLUE = 'lightskyblue';
    public const COLOR_LIGHT_SLATE_GRAY = 'lightslategray';
    public const COLOR_LIGHT_SLATE_GREY = 'lightslategrey';
    public const COLOR_LIGHT_STEEL_BLUE = 'lightsteelblue';
    public const COLOR_LIGHT_YELLOW = 'lightyellow';
    public const COLOR_LIME = 'lime';
    public const COLOR_LIME_GREEN = 'limegreen';
    public const COLOR_LINEN = 'linen';
    public const COLOR_MAGENTA = 'magenta';
    public const COLOR_MAROON = 'maroon';
    public const COLOR_MEDIUM_AQUAMARINE = 'mediumaquamarine';
    public const COLOR_MEDIUM_BLUE = 'mediumblue';
    public const COLOR_MEDIUM_ORCHID = 'mediumorchid';
    public const COLOR_MEDIUM_PURPLE = 'mediumpurple';
    public const COLOR_MEDIUM_SEA_GREEN = 'mediumseagreen';
    public const COLOR_MEDIUM_SLATE_BLUE = 'mediumslateblue';
    public const COLOR_MEDIUM_SPRING_GREEN = 'mediumspringgreen';
    public const COLOR_MEDIUM_TURQUOISE = 'mediumturquoise';
    public const COLOR_MEDIUM_VIOLET_RED = 'mediumvioletred';
    public const COLOR_MIDNIGHT_BLUE = 'midnightblue';
    public const COLOR_MINT_CREAM = 'mintcream';
    public const COLOR_MISTY_ROSE = 'mistyrose';
    public const COLOR_MOCCASIN = 'moccasin';
    public const COLOR_NAVAJO_WHITE = 'navajowhite';
    public const COLOR_NAVY = 'navy';
    public const COLOR_OLDLACE = 'oldlace';
    public const COLOR_OLIVE = 'olive';
    public const COLOR_OLIVE_DRAB = 'olivedrab';
    public const COLOR_ORANGE = 'orange';
    public const COLOR_ORANGE_RED = 'orangered';
    public const COLOR_ORCHID = 'orchid';
    public const COLOR_PALE_GOLDENROD = 'palegoldenrod';
    public const COLOR_PALE_GREEN = 'palegreen';
    public const COLOR_PALE_TURQUOISE = 'paleturquoise';
    public const COLOR_PALE_VIOLET_RED = 'palevioletred';
    public const COLOR_PAPAYA_WHIP = 'papayawhip';
    public const COLOR_PEACH_PUFF = 'peachpuff';
    public const COLOR_PERU = 'peru';
    public const COLOR_PINK = 'pink';
    public const COLOR_PLUM = 'plum';
    public const COLOR_POWDER_BLUE = 'powderblue';
    public const COLOR_PURPLE = 'purple';
    public const COLOR_RED = 'red';
    public const COLOR_ROSY_BROWN = 'rosybrown';
    public const COLOR_ROYAL_BLUE = 'royalblue';
    public const COLOR_SADDLE_BROWN = 'saddlebrown';
    public const COLOR_SALMON = 'salmon';
    public const COLOR_SANDY_BROWN = 'sandybrown';
    public const COLOR_SEA_GREEN = 'seagreen';
    public const COLOR_SEASHELL = 'seashell';
    public const COLOR_SIENNA = 'sienna';
    public const COLOR_SILVER = 'silver';
    public const COLOR_SKY_BLUE = 'skyblue';
    public const COLOR_SLATE_BLUE = 'slateblue';
    public const COLOR_SLATE_GRAY = 'slategray';
    public const COLOR_SLATE_GREY = 'slategrey';
    public const COLOR_SNOW = 'snow';
    public const COLOR_SPRING_GREEN = 'springgreen';
    public const COLOR_STEEL_BLUE = 'steelblue';
    public const COLOR_TAN = 'tan';
    public const COLOR_TEAL = 'teal';
    public const COLOR_THISTLE = 'thistle';
    public const COLOR_TOMATO = 'tomato';
    public const COLOR_TURQUOISE = 'turquoise';
    public const COLOR_VIOLET = 'violet';
    public const COLOR_WHEAT = 'wheat';
    public const COLOR_WHITE = 'white';
    public const COLOR_WHITE_SMOKE = 'whitesmoke';
    public const COLOR_YELLOW = 'yellow';
    public const COLOR_YELLOW_GREEN = 'yellowgreen';

    public const DATA_TYPE_BINARY = 'BINARY';
    public const DATA_TYPE_BOOLEAN = 'BOOLEAN';
    public const DATA_TYPE_CAL_ADDRESS = 'CAL-ADDRESS';
    public const DATA_TYPE_DATE = 'DATE';
    public const DATA_TYPE_DATE_TIME = 'DATE-TIME';
    public const DATA_TYPE_DURATION = 'DURATION';
    public const DATA_TYPE_FLOAT = 'FLOAT';
    public const DATA_TYPE_INTEGER = 'INTEGER';
    public const DATA_TYPE_PERIOD = 'PERIOD';
    public const DATA_TYPE_RECUR = 'RECUR';
    public const DATA_TYPE_TEXT = 'TEXT';
    public const DATA_TYPE_TIME = 'TIME';
    public const DATA_TYPE_URI = 'URI';
    public const DATA_TYPE_UTC_OFFSET = 'UTC-OFFSET';

    public const DISPLAY_BADGE = 'BADGE';
    public const DISPLAY_GRAPHIC = 'GRAPHIC';
    public const DISPLAY_FULLSIZE = 'FULLSIZE';
    public const DISPLAY_THUMBNAIL = 'THUMBNAIL';

    public const FREQUENCY_DAILY = 'DAILY';
    public const FREQUENCY_HOURLY = 'HOURLY';
    public const FREQUENCY_MINUTELY = 'MINUTELY';
    public const FREQUENCY_MONTHLY = 'MONTHLY';
    public const FREQUENCY_SECONDLY = 'SECONDLY';
    public const FREQUENCY_WEEKLY = 'WEEKLY';
    public const FREQUENCY_YEARLY = 'YEARLY';

    public const PARAMETER_ALTERNATE_REPRESENTATION = 'ALTREP';
    public const PARAMETER_COMMON_NAME = 'CN';
    public const PARAMETER_CALENDAR_USER_TYPE = 'CUTYPE';
    public const PARAMETER_DELEGATED_FROM = 'DELEGATED-FROM';
    public const PARAMETER_DELEGATED_TO = 'DELEGATED-TO';
    public const PARAMETER_DIR = 'DIR';
    public const PARAMETER_DISPLAY = 'DISPLAY';
    public const PARAMETER_EMAIL = 'EMAIL';
    public const PARAMETER_ENCODING = 'ENCODING';
    public const PARAMETER_FEATURE = 'FEATURE';
    public const PARAMETER_FORMAT_TYPE = 'FMTTYPE';
    public const PARAMETER_FREEBUSY_TIME_TYPE = 'FBTYPE';
    public const PARAMETER_LABEL = 'LABEL';
    public const PARAMETER_LANGUAGE = 'LANGUAGE';
    public const PARAMETER_MEMBER = 'MEMBER';
    public const PARAMETER_PARTICIPATION_STATUS = 'PARTSTAT';
    public const PARAMETER_RANGE = 'RANGE';
    public const PARAMETER_RELATED = 'RELATED';
    public const PARAMETER_RELATIONSHIP_TYPE = 'RELTYPE';
    public const PARAMETER_ROLE = 'ROLE';
    public const PARAMETER_RSVP = 'RSVP';
    public const PARAMETER_SENT_BY = 'SENT-BY';
    public const PARAMETER_TIME_ZONE_IDENTIFIER = 'TZID';
    public const PARAMETER_VALUE = 'VALUE';

    public const PROPERTY_ATTACH = 'ATTACH';
    public const PROPERTY_ATTENDEE = 'ATTENDEE';
    public const PROPERTY_CALENDAR_SCALE = 'CALSCALE';
    public const PROPERTY_CATEGORIES = 'CATEGORIES';
    public const PROPERTY_CLASSIFICATION = 'CLASS';
    public const PROPERTY_COLOR = 'COLOR';
    public const PROPERTY_COMMENT = 'COMMENT';
    public const PROPERTY_CONFERENCE = 'CONFERENCE';
    public const PROPERTY_CONTACT = 'CONTACT';
    public const PROPERTY_DATETIME_COMPLETED = 'COMPLETED';
    public const PROPERTY_DATETIME_CREATED = 'CREATED';
    public const PROPERTY_DATETIME_DUE = 'DUE';
    public const PROPERTY_DATETIME_END = 'DTEND';
    public const PROPERTY_DATETIME_STAMP = 'DTSTAMP';
    public const PROPERTY_DATETIME_START = 'DTSTART';
    public const PROPERTY_DESCRIPTION = 'DESCRIPTION';
    public const PROPERTY_DURATION = 'DURATION';
    public const PROPERTY_EXCEPTION_DATE = 'EXDATE';
    public const PROPERTY_GEOGRAPHIC_POSITION = 'GEO';
    public const PROPERTY_IMAGE = 'IMAGE';
    public const PROPERTY_LAST_MODIFIED = 'LAST-MODIFIED';
    public const PROPERTY_LOCATION = 'LOCATION';
    public const PROPERTY_METHOD = 'METHOD';
    public const PROPERTY_ORGANIZER = 'ORGANIZER';
    public const PROPERTY_PERCENT_COMPLETE = 'PERCENT-COMPLETE';
    public const PROPERTY_PRIORITY = 'PRIORITY';
    public const PROPERTY_PRODUCT_IDENTIFIER = 'PRODID';
    public const PROPERTY_RECURRENCE_DATETIME = 'RDATE';
    public const PROPERTY_RECURRENCE_ID = 'RECURRENCE-ID';
    public const PROPERTY_RELATED_TO = 'RELATED-TO';
    public const PROPERTY_REPEAT = 'REPEAT';
    public const PROPERTY_REQUEST_STATUS = 'REQUEST-STATUS';
    public const PROPERTY_RESOURCES = 'RESOURCES';
    public const PROPERTY_RECURRENCE_RULE = 'RRULE';
    public const PROPERTY_SEQUENCE = 'SEQUENCE';
    public const PROPERTY_STATUS = 'STATUS';
    public const PROPERTY_SUMMARY = 'SUMMARY';
    public const PROPERTY_TIME_TRANSPARENCY = 'TRANSP';
    public const PROPERTY_UID = 'UID';
    public const PROPERTY_URL = 'URL';
    public const PROPERTY_VERSION = 'VERSION';

    public const RANGE_THIS_AND_FUTURE = 'THISANDFUTURE';

    public const RELATIONSHIP_CHILD = 'CHILD';
    public const RELATIONSHIP_PARENT = 'PARENT';
    public const RELATIONSHIP_SIBLING = 'SIBLING';

    public const ROLE_CHAIR = 'CHAIR';
    public const ROLE_NON_PARTICIPANT = 'NON-PARTICIPANT';
    public const ROLE_OPT_PARTICIPANT = 'OPT-PARTICIPANT';
    public const ROLE_REQ_PARTICIPANT = 'REQ-PARTICIPANT';

    public const RRULE_BY_DAY = 'BYDAY';
    public const RRULE_BY_HOUR = 'BYHOUR';
    public const RRULE_BY_MINUTE = 'BYMINUTE';
    public const RRULE_BY_MONTH = 'BYMONTH';
    public const RRULE_BY_MONTH_DAY = 'BYMONTHDAY';
    public const RRULE_BY_SECOND = 'BYSECOND';
    public const RRULE_BY_SETPOS = 'BYSETPOS';
    public const RRULE_BY_WEEK_NO = 'BYWEEKNO';
    public const RRULE_BY_YEAR_DAY = 'BYYEARDAY';
    public const RRULE_COUNT = 'COUNT';
    public const RRULE_FREQ = 'FREQ';
    public const RRULE_INTERVAL = 'INTERVAL';
    public const RRULE_UNTIL = 'UNTIL';
    public const RRULE_WKST = 'WKST';

    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_DECLINED = 'DECLINED';
    public const STATUS_DELEGATED = 'DELEGATED';
    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_FINAL = 'FINAL';
    public const STATUS_IN_PROCESS = 'IN-PROCESS';
    public const STATUS_NEEDS_ACTION = 'NEEDS-ACTION';
    public const STATUS_TENTATIVE = 'TENTATIVE';

    public const TRANSPARENCY_OPAQUE = 'OPAQUE';
    public const TRANSPARENCY_TRANSPARENT = 'TRANSPARENT';

    public const USER_TYPE_INDIVIDUAL = 'INDIVIDUAL';
    public const USER_TYPE_GROUP = 'GROUP';
    public const USER_TYPE_RESOURCE = 'RESOURCE';
    public const USER_TYPE_ROOM = 'ROOM';
    public const USER_TYPE_UNKNOWN = 'UNKNOWN';

    public const MONDAY = 'MO';
    public const TUESDAY = 'TU';
    public const WEDNESDAY = 'WE';
    public const THURSDAY = 'TH';
    public const FRIDAY = 'FR';
    public const SATURDAY = 'SA';
    public const SUNDAY = 'SU';

    public const JANUARY = 1;
    public const FEBRUARY = 2;
    public const MARCH = 3;
    public const APRIL = 4;
    public const MAY = 5;
    public const JUNE = 6;
    public const JULY = 7;
    public const AUGUST = 8;
    public const SEPTEMBER = 9;
    public const OCTOBER = 10;
    public const NOVEMBER = 11;
    public const DECEMBER = 12;

    public const BEGIN = 'BEGIN';
    public const END = 'END';

    /**
     * @var list<string> $properties
     */
    protected static array $nonStandardProperties = [];
    /**
     * @var list<string> $lines
     */
    protected static array $lines = [];

    /**
     * @var array<string, Property|list<Property>> $properties
     */
    protected array $properties = [];
    /**
     * @var array<string, list<Component>> $components
     */
    private array $components = [];
    private ?Component $parent = null;
    private array $validProperties = [];

    public function __construct()
    {
        $this->validProperties = array_merge(static::CARDINALITY, self::$nonStandardProperties);
    }

    // For tests
    public function __toString(): string
    {
        return $this->getName();
    }

    public function addComponent(Component $component): self
    {
        $this->validateComponent($component);

        $new = clone $this;
        $component->setParent($new);
        $new->components[$component->getName()][] = $component;
        return $new;
    }

    public function setComponent(Component $component, int $index): self
    {
        $this->validateComponent($component);

        $new = clone $this;
        $component->setParent($new);
        $new->components[$component->getName()][$index] = $component;
        return $new;
    }

    public function addProperty(string $name, array|int|string $value, array $parameters = []): self
    {
        $this->validateProperty($name);

        $new = clone $this;

        if (
            in_array($this->getCardinality($name), [self::CARDINALITY_ONE_MAY, self::CARDINALITY_ONE_MUST], true)
            && $new->hasProperty($name)
        ) {
            throw new InvalidPropertyException($new, $name, code: 2);
        }

        $new->properties[$name][] = new Property($this, $name, $value, $parameters);

        return $new;
    }

    public function setProperty(string $name, int $index, array|int|string $value, array $parameters = []): self
    {
        $this->validateProperty($name);

        $new = clone $this;

        if (in_array(
            $this->getCardinality($name),
            [self::CARDINALITY_ONE_MAY, self::CARDINALITY_ONE_MUST],
            true
        )) {
            $index = 0;
        }

        $new->properties[$name][$index] = new Property($this, $name, $value, $parameters);

        return $new;
    }

    /** @return array<string, list<Component>> */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * Gets a child component
     *
     * @param string $name Name of the component
     * @param int|null $index Index of the component to return. If NULL all components of type $name are returned
     * @return list<Component>|Component|null NULL if the named component or $index  does not exist, a component if
     * $index is given or an array of components of type $name if not
     */
    public function getComponent(string $name, ?int $index = null): array|Component|null
    {
        if (!$this->hasComponent($name)) {
            return null;
        }

        if ($index === null) {
            return $this->components[$name];
        }

        if (isset($this->components[$name][$index])) {
            return $this->components[$name][$index];
        }

        return null;
    }


    /**
     * Remove component(s) by name
     *
     * Removing a component also removes all the component's properties and child components
     *
     * @param string $name Name of the component to remove
     * @param int|null $index Index of the component to remove. If NULL all components of type $name are removed
     * @return Component
     */
    public function removeComponent(string $name, ?int $index = null): self
    {
        $new = clone $this;

        if ($index !== null) {
            array_splice($new->components[$name], $index, 1);
            if (count($new->components[$name]) === 0) {
                $index = null;
            }
        }

        if ($index === null) {
            unset($new->components[$name]);
        }

        return $new;
    }

    public function hasComponent(string $name): bool
    {
        return isset($this->components[$name]);
    }

    public function getName(): string
    {
        /** @psalm-suppress UndefinedConstant */
        return (string)static::NAME;
    }

    public function getParent(): ?Component
    {
        return $this->parent;
    }

    /** @return array<string, list<Property>|Property> */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Gets a property of the component
     *
     * @param string $name Name of the property
     * @param int|null $index Index of the property to return. If NULL all properties of type $name are returned
     * @return list<Property>|Property|null NULL if the named property or $index does not exist, a property if $index is
     * given or an array of properties of type $name if not
     */
    public function getProperty(string $name, ?int $index = null): array|Property|null
    {
        if (!$this->hasProperty($name)) {
            return null;
        }

        if ($index === null) {
            return $this->properties[$name];
        }

        if (isset($this->properties[$name][$index])) {
            return $this->properties[$name][$index];
        }

        return null;
    }

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param string $name Name of the property to remove
     * @param int|null $index Index of the property to remove. If NULL all properties of type $name are removed
     * @return Component
     */
    public function removeProperty(string $name, ?int $index = null): self
    {
        $new = clone $this;

        if ($index !== null) {
            array_splice($new->properties[$name], $index, 1);
            if (count($new->properties[$name]) === 0) {
                $index = null;
            }
        }

        if ($index === null) {
            unset($new->properties[$name]);
        }

        return $new;
    }

    public function render(): string
    {
        $this->hasRequiredProperties();

        /** @var list<string> $lines */
        $lines = [self::BEGIN . Property::PROPERTY_SEPARATOR . $this->getName()];

        foreach (array_keys($this->properties) as $name) {
            foreach ($this->properties[$name] as $property) {
                $lines[] = $property->render();
            }
        }

        foreach ($this->getComponents() as $components) {
            foreach ($components as $component) {
                $lines[] = $component->render();
            }
        }

        $lines[] = self::END . Property::PROPERTY_SEPARATOR . $this->getName();

        return implode("\r\n", $lines) . ($this->isRoot() ? "\r\n" : '');
    }

    public function getCardinality(string $property): string
    {
        return $this->validProperties[$property];
    }

    public static function registerNonStandardProperty(
        string $name,
        string $cardinality = self::CARDINALITY_ONE_OR_MORE_MAY
    ): void
    {
        self::$nonStandardProperties[$name] = $cardinality;
    }

    public static function uuidv4($data = '')
    {
        if (strlen($data) < 16) {
            $data .= random_bytes(16 - strlen($data));
        }

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    protected function validateComponent(Component $component): void
    {
        if (!in_array($component->getName(), static::COMPONENTS, true)) {
            throw new InvalidComponentException($this, $component);
        }
    }

    protected function validateProperty(string $name): void
    {
        if (!array_key_exists($name, $this->validProperties)
        ) {
            throw new InvalidPropertyException($this, $name);
        }
    }

    protected function hasRequiredProperties(): void
    {
        foreach ($this->validProperties as $property => $cardinality) {
            if (
                in_array($cardinality, [self::CARDINALITY_ONE_MUST, self::CARDINALITY_ONE_OR_MORE_MUST])
                && !$this->hasProperty($property)
            ) {
                throw new MissingPropertyException($this, $property);
            }
        }
    }

    private function isRoot(): bool
    {
        return $this->getParent() === null;
    }

    private function setParent(Component $parent): void
    {
        $this->parent = $parent;
    }
}
