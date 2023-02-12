<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar\Exception;

use BeastBytes\ICalendar\Component;
use Throwable;

class MissingPropertyException extends \RuntimeException
{
    public function __construct(Component $component, string $property, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Required property %s not set in %s', $property, $component->getName()),
            $code,
            $previous
        );
    }
}
