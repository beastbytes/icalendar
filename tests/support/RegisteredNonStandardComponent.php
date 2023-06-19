<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Tests\support;

use BeastBytes\ICalendar\Component;

class RegisteredNonStandardComponent extends Component
{
    public const NAME = 'REGISTERED-NON-STANDARD-COMPONENT';

    protected const CARDINALITY = [];
}
