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
    public const PROPERTY_TZID = 'TZID';
    public const PROPERTY_TZURL = 'TZURL';

    protected const CARDINALITY = [
        self::PROPERTY_LAST_MODIFIED => self::CARDINALITY_ONE_MAY,
        self::PROPERTY_TZID => self::CARDINALITY_ONE_MUST,
        self::PROPERTY_TZURL => self::CARDINALITY_ONE_MAY,
    ];
}
