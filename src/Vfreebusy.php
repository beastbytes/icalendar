<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Vfreebusy extends Component
{
    public const NAME = 'VFREEBUSY';
    public const PROPERTY_FREEBUSY = 'FREEBUSY';
    public const TIME_TYPE_BUSY = 'BUSY';
    public const TIME_TYPE_BUSY_TENTATIVE = 'BUSY-TENTATIVE';
    public const TIME_TYPE_BUSY_UNAVAILABLE = 'BUSY-UNAVAILABLE';
    public const TIME_TYPE_FREE = 'FREE';
}
