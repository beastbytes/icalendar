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
    public const PROPERTY_TZ_NAME = 'TZNAME';
    public const PROPERTY_TZ_OFFSET_FROM = 'TZOFFSETFROM';
    public const PROPERTY_TZ_OFFSET_TO = 'TZOFFSETTO';

    public const CARDINALITY = [
        self::PROPERTY_COMMENT => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_DATETIME_START => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_RECURRENCE_DATETIME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_RECURRENCE_RULE => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TZ_NAME => self::CARDINALITY_ONE_OR_MORE_MAY,
        self::PROPERTY_TZ_OFFSET_FROM => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_TZ_OFFSET_TO => self::CARDINALITY_ONE_MUST,
    ];

    protected const COMPONENTS = [];
}
