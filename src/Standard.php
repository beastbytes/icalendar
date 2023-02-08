<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Standard extends Component
{
    public const NAME = 'STANDARD';
    public const PROPERTY_TZNAME = 'TZNAME';
    public const PROPERTY_TZOFFSETFROM = 'TZOFFSETFROM';
    public const PROPERTY_TZOFFSETTO = 'TZOFFSETTO';

    protected const CARDINALITY = [
        self::PROPERTY_COMMENT => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_DTSTART => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_RDATE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_RRULE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TZNAME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TZOFFSETFROM => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_TZOFFSETTO => self::CARDINALITY_ONE_MUST,
    ];

    protected const COMPONENTS = [];
}
