<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\ICalendar;

class Property
{
    public const LIST_SEPARATOR = ',';
    public const PARAMETER_SEPARATOR = ';';
    public const PROPERTY_SEPARATOR = ':';
    public const RECUR_SEPARATOR = ';';
    public const EQUALS = '=';
    private const LINE_LENGTH = 75;

    /** @var array $value */
    private array $value;

    public function __construct(private string $name, array|int|string $value, private array $parameters = [])
    {
        if (is_array($value)) {
            $this->value = $value;
        } else {
            $this->value = [$value];
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): string
    {
        $parameters = [];

        foreach ($this->parameters as $parameter => $value) {
            $parameters[] = $parameter . self::EQUALS . $value;
        }

        return empty($parameters)
            ? ''
            : self::PARAMETER_SEPARATOR . implode(self::PARAMETER_SEPARATOR, $parameters)
        ;
    }

    public function getValue(): string
    {
        switch ($this->getName()) {
            case Vfreebusy::PROPERTY_FREEBUSY:
                return $this->freebusy();
            case Component::PROPERTY_RRULE:
                return $this->recur();
            default:
                return implode(self::LIST_SEPARATOR, $this->value);
        }
    }

    public function render(): string
    {
        return $this->fold(
            $this->getName()
            . $this->getParameters()
            . self::PROPERTY_SEPARATOR
            . $this->getValue()
        );
    }

    private function fold(string $line): string
    {
        if (strlen($line) > self::LINE_LENGTH) {
            $folded = [];

            while (strlen($line) > self::LINE_LENGTH) {
                $folded[] = substr($line, 0, self::LINE_LENGTH);
                $line = ' ' . substr($line, self::LINE_LENGTH);
            }
            $folded[] = $line;

            $line = implode("\r\n", $folded);
        }

        return $line;
    }

    private function freebusy(): string
    {
        $freebusy = [];

        foreach ($this->value as $key => $value) {
            $freebusy[] = is_string($key) ? $key . Vfreebusy::FREEBUSY_SEPARATOR . $value : $value;
        }

        return implode(self::LIST_SEPARATOR, $freebusy);
    }

    private function recur(): string
    {
        $recur = [];

        foreach ($this->value as $key => $value) {
            $recur[] = $key . self::EQUALS . (
                is_array($value) ? implode(self::LIST_SEPARATOR, $value) : $value
            );
        }

        return implode(self::RECUR_SEPARATOR, $recur);
    }
}
