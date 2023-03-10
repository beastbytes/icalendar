<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Vtimezone extends Component
{
    public const NAME = 'VTIMEZONE';
    public const PROPERTY_TZ_ID = 'TZID';
    public const PROPERTY_TZ_URL = 'TZURL';

    public const CARDINALITY = [
        self::PROPERTY_LAST_MODIFIED => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_TZ_ID => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_TZ_URL => self::CARDINALITY_ONE_MAY,
    ];

    protected const COMPONENTS = [
        Daylight::NAME,
        Standard::NAME,
    ];
}
