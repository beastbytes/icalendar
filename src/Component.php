<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

abstract class Component
{
    public const CLASSIFICATION_CONFIDENTIAL = 'CONFIDENTIAL';
    public const CLASSIFICATION_PRIVATE = 'PRIVATE';
    public const CLASSIFICATION_PUBLIC = 'PUBLIC';
    public const FREQUENCY_DAILY = 'DAILY';
    public const FREQUENCY_HOURLY = 'HOURLY';
    public const FREQUENCY_MINUTELY = 'MINUTELY';
    public const FREQUENCY_MONTHLY = 'MONTHLY';
    public const FREQUENCY_SECONDLY = 'SECONDLY';
    public const FREQUENCY_WEEKLY = 'WEEKLY';
    public const FREQUENCY_YEARLY = 'YEARLY';
    public const PARAMETER_ALTREP = 'ALTREP';
    public const PARAMETER_CN = 'CN';
    public const PARAMETER_CUTYPE = 'CUTYPE';
    public const PARAMETER_DELEGATED_FROM = 'DELEGATED-FROM';
    public const PARAMETER_DELEGATED_TO = 'DELEGATED-TO';
    public const PARAMETER_DIR = 'DIR';
    public const PARAMETER_ENCODING = 'ENCODING';
    public const PARAMETER_FMTTYPE = 'FMTTYPE';
    public const PARAMETER_FBTYPE = 'FBTYPE';
    public const PARAMETER_LANGUAGE = 'LANGUAGE';
    public const PARAMETER_MEMBER = 'MEMBER';
    public const PARAMETER_PARTSTAT = 'PARTSTAT';
    public const PARAMETER_RANGE = 'RANGE';
    public const PARAMETER_RELATED = 'RELATED';
    public const PARAMETER_RELTYPE = 'RELTYPE';
    public const PARAMETER_ROLE = 'ROLE';
    public const PARAMETER_RSVP = 'RSVP';
    public const PARAMETER_SENT_BY = 'SENT-BY';
    public const PARAMETER_TZID = 'TZID';
    public const PARAMETER_VALUE = 'VALUE';
    public const PARTICIPANT_ACCEPTED = 'ACCEPTED';
    public const PARTICIPANT_COMPLETED = 'COMPLETED';
    public const PARTICIPANT_DECLINED = 'DECLINED';
    public const PARTICIPANT_DELEGATED = 'DELEGATED';
    public const PARTICIPANT_IN_PROCESS = 'IN-PROCESS';
    public const PARTICIPANT_NEEDS_ACTION = 'NEEDS-ACTION';
    public const PROPERTY_ATTACH = 'ATTACH';
    public const PROPERTY_ATTENDEE = 'ATTENDEE';
    public const PROPERTY_CALSCALE = 'CALSCALE';
    public const PROPERTY_CATEGORIES = 'CATEGORIES';
    public const PROPERTY_CLASS = 'CLASS';
    public const PROPERTY_COMMENT = 'COMMENT';
    public const PROPERTY_COMPLETED = 'COMPLETED';
    public const PROPERTY_CONTACT = 'CONTACT';
    public const PROPERTY_CREATED = 'CREATED';
    public const PROPERTY_DESCRIPTION = 'DESCRIPTION';
    public const PROPERTY_DTEND = 'DTEND';
    public const PROPERTY_DTSTAMP = 'DTSTAMP';
    public const PROPERTY_DTSTART = 'DTSTART';
    public const PROPERTY_DUE = 'DUE';
    public const PROPERTY_DURATION = 'DURATION';
    public const PROPERTY_EXDATE = 'EXDATE';
    public const PROPERTY_GEO = 'GEO';
    public const PROPERTY_LAST_MODIFIED = 'LAST-MODIFIED';
    public const PROPERTY_LOCATION = 'LOCATION';
    public const PROPERTY_METHOD = 'METHOD';
    public const PROPERTY_ORGANIZER = 'ORGANIZER';
    public const PROPERTY_PERCENT_COMPLETE = 'PERCENT-COMPLETE';
    public const PROPERTY_PRIORITY = 'PRIORITY';
    public const PROPERTY_PRODID = 'PRODID';
    public const PROPERTY_RDATE = 'RDATE';
    public const PROPERTY_RECURRENCE_ID = 'RECURRENCE-ID';
    public const PROPERTY_RELATED_TO = 'RELATED-TO';
    public const PROPERTY_REPEAT = 'REPEAT';
    public const PROPERTY_REQUEST_STATUS = 'REQUEST-STATUS';
    public const PROPERTY_RESOURCES = 'RESOURCES';
    public const PROPERTY_RRULE = 'RRULE';
    public const PROPERTY_SEQUENCE = 'SEQUENCE';
    public const PROPERTY_STATUS = 'STATUS';
    public const PROPERTY_SUMMARY = 'SUMMARY';
    public const PROPERTY_TRANSP = 'TRANSP';
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
    public const STATUS_CANCELLED = 'CANCELLED';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_IN_PROCESS = 'IN-PROCESS';
    public const STATUS_NEEDS_ACTION = 'NEEDS-ACTION';
    public const TRANSP_OPAQUE = 'OPAQUE';
    public const TRANSP_TRANSPARENT = 'TRANSPARENT';
    public const USER_TYPE_INDIVIDUAL = 'INDIVIDUAL';
    public const USER_TYPE_GROUP = 'GROUP';
    public const USER_TYPE_RESOURCE = 'RESOURCE';
    public const USER_TYPE_ROOM = 'ROOM';
    public const USER_TYPE_UNKNOWN = 'UNKNOWN';
    public const VALUE_DATA_TYPE_BINARY = 'BINARY';
    public const VALUE_DATA_TYPE_BOOLEAN = 'BOOLEAN';
    public const VALUE_DATA_TYPE_CAL_ADDRESS = 'CAL-ADDRESS';
    public const VALUE_DATA_TYPE_DATE = 'DATE';
    public const VALUE_DATA_TYPE_DATE_TIME = 'DATE-TIME';
    public const VALUE_DATA_TYPE_DURATION = 'DURATION';
    public const VALUE_DATA_TYPE_FLOAT = 'FLOAT';
    public const VALUE_DATA_TYPE_INTEGER = 'INTEGER';
    public const VALUE_DATA_TYPE_PERIOD = 'PERIOD';
    public const VALUE_DATA_TYPE_RECUR = 'RECUR';
    public const VALUE_DATA_TYPE_TEXT = 'TEXT';
    public const VALUE_DATA_TYPE_TIME = 'TIME';
    public const VALUE_DATA_TYPE_URI = 'URI';
    public const VALUE_DATA_TYPE_UTC_OFFSET = 'UTC-OFFSET';

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

    protected const CARDINALITY_ONE_MAY = '*1';
    protected const CARDINALITY_ONE_MUST = '1';
    protected const CARDINALITY_ONE_OR_MORE_MAY = '*';
    protected const CARDINALITY_ONE_OR_MORE_MUST = '1*';

    /** @var list<string> $lines */
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

    public function addComponent(Component $component): self
    {
        $new = clone $this;
        $component->setParent($new);
        $new->components[] = $component;
        return $new;
    }

    public function addProperty(string $name, array|int|string $value, array $parameters = []): self
    {
        $new = clone $this;

        if (in_array($this->cardinality($name), [self::CARDINALITY_ONE_MAY, self::CARDINALITY_ONE_MUST], true)) {
            $new->properties[$name] = new Property($name, $value, $parameters);
        } else {
            $new->properties[$name][] = new Property($name, $value, $parameters);
        }

        return $new;
    }

    /** @return list<Component> */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function getComponent(int $index): ?Component
    {
        return $this->components[$index] ?? null;
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
     * @param string $name
     * @return list<Property>|Property|null
     */
    public function getProperty(string $name): array|Property|null
    {
        return $this->properties[$name] ?? null;
    }

    public function render(): string
    {
        /** @var list<string> $lines */
        $lines = [self::BEGIN . Property::PROPERTY_SEPARATOR . $this->getName()];

        foreach (array_keys($this->properties) as $name) {
            if (is_array($this->properties[$name])) {
                foreach ($this->properties[$name] as $property) {
                    $lines[] = $property->render();
                }
            } else {
                $lines[] = $this
                    ->properties[$name]
                    ->render()
                ;
            }
        }

        foreach ($this->getComponents() as $component) {
            $lines[] = $component->render();
        }

        $lines[] = self::END . Property::PROPERTY_SEPARATOR . $this->getName();

        return implode("\r\n", $lines) . ($this->isRoot() ? "\r\n" : '');
    }

    protected function cardinality($name)
    {
        return static::CARDINALITY[$name];
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
