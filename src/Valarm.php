<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Valarm extends Component
{
    public const ACTION_AUDIO = 'AUDIO';
    public const ACTION_DISPLAY = 'DISPLAY';
    public const ACTION_EMAIL = 'EMAIL';
    public const NAME = 'VALARM';
}
