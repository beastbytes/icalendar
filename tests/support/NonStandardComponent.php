<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests\support;

use BeastBytes\ICalendar\Component;

class NonStandardComponent extends Component
{
    public const NAME = 'NONSTANDARDCOMPONENT';

    protected const CARDINALITY = [];
}