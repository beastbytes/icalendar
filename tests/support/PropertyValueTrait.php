<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests\support;

use BeastBytes\ICalendar\Component;
use BeastBytes\ICalendar\Property;
use BeastBytes\ICalendar\Standard;
use BeastBytes\ICalendar\Valarm;
use BeastBytes\ICalendar\Vevent;
use BeastBytes\ICalendar\Vfreebusy;
use BeastBytes\ICalendar\Vjournal;
use BeastBytes\ICalendar\Vtimezone;
use BeastBytes\ICalendar\Vtodo;

use const STR_PAD_LEFT;

trait PropertyValueTrait
{
    private function getValidValue(Component $component, string $property): mixed
    {
        return match ($property) {
            Valarm::PROPERTY_ACTION => array_rand(array_flip([
                Valarm::ACTION_AUDIO,
                Valarm::ACTION_DISPLAY,
                Valarm::ACTION_EMAIL
            ])),
            Component::PROPERTY_ATTACH => '',
            Component::PROPERTY_ATTENDEE => '',
            Component::PROPERTY_CALENDAR_SCALE => '',
            Component::PROPERTY_CATEGORIES => $this->getList(3),
            Component::PROPERTY_CLASSIFICATION => array_rand(array_flip([
                Component::CLASSIFICATION_CONFIDENTIAL,
                Component::CLASSIFICATION_PRIVATE,
                Component::CLASSIFICATION_PUBLIC
            ])),
            Component::PROPERTY_COLOR => array_rand(array_flip([
                Component::COLOR_ALICE_BLUE,
                Component::COLOR_ANTIQUE_WHITE,
                Component::COLOR_AQUA,
                Component::COLOR_AQUAMARINE,
                Component::COLOR_AZURE,
                Component::COLOR_BEIGE,
                Component::COLOR_BISQUE,
                Component::COLOR_BLACK,
                Component::COLOR_BLANCHED_ALMOND,
                Component::COLOR_BLUE,
                Component::COLOR_BLUE_VIOLET,
                Component::COLOR_BROWN,
                Component::COLOR_BURLYWOOD,
                Component::COLOR_CADET_BLUE,
                Component::COLOR_CHARTREUSE,
                Component::COLOR_CHOCOLATE,
                Component::COLOR_CORAL,
                Component::COLOR_CORNFLOWER_BLUE,
                Component::COLOR_CORNSILK,
                Component::COLOR_CRIMSON,
                Component::COLOR_CYAN,
                Component::COLOR_DARK_BLUE,
                Component::COLOR_DARK_CYAN,
                Component::COLOR_DARK_GOLDENROD,
                Component::COLOR_DARK_GRAY,
                Component::COLOR_DARK_GREEN,
                Component::COLOR_DARK_GREY,
                Component::COLOR_DARK_KHAKI,
                Component::COLOR_DARK_MAGENTA,
                Component::COLOR_DARK_OLIVE_GREEN,
                Component::COLOR_DARK_ORANGE,
                Component::COLOR_DARK_ORCHID,
                Component::COLOR_DARK_RED,
                Component::COLOR_DARK_SALMON,
                Component::COLOR_DARK_SEA_GREEN,
                Component::COLOR_DARK_SLATE_BLUE,
                Component::COLOR_DARK_SLATE_GRAY,
                Component::COLOR_DARK_SLATE_GREY,
                Component::COLOR_DARK_TURQUOISE,
                Component::COLOR_DARK_VIOLET,
                Component::COLOR_DEEP_PINK,
                Component::COLOR_DEEP_SKY_BLUE,
                Component::COLOR_DIM_GRAY,
                Component::COLOR_DIM_GREY,
                Component::COLOR_DODGER_BLUE,
                Component::COLOR_FIREBRICK,
                Component::COLOR_FLORAL_WHITE,
                Component::COLOR_FOREST_GREEN,
                Component::COLOR_FUCHSIA,
                Component::COLOR_GAINSBORO,
                Component::COLOR_GHOST_WHITE,
                Component::COLOR_GOLD,
                Component::COLOR_GOLDENROD,
                Component::COLOR_GRAY,
                Component::COLOR_GREEN,
                Component::COLOR_GREEN_YELLOW,
                Component::COLOR_GREY,
                Component::COLOR_HONEYDEW,
                Component::COLOR_HOT_PINK,
                Component::COLOR_INDIAN_RED,
                Component::COLOR_INDIGO,
                Component::COLOR_IVORY,
                Component::COLOR_KHAKI,
                Component::COLOR_LAVENDER,
                Component::COLOR_LAVENDER_BLUSH,
                Component::COLOR_LAWN_GREEN,
                Component::COLOR_LEMON_CHIFFON,
                Component::COLOR_LIGHT_BLUE,
                Component::COLOR_LIGHT_CORAL,
                Component::COLOR_LIGHT_CYAN,
                Component::COLOR_LIGHT_GOLDENROD_YELLOW,
                Component::COLOR_LIGHT_GRAY,
                Component::COLOR_LIGHT_GREEN,
                Component::COLOR_LIGHT_GREY,
                Component::COLOR_LIGHT_PINK,
                Component::COLOR_LIGHT_SALMON,
                Component::COLOR_LIGHT_SEA_GREEN,
                Component::COLOR_LIGHT_SKY_BLUE,
                Component::COLOR_LIGHT_SLATE_GRAY,
                Component::COLOR_LIGHT_SLATE_GREY,
                Component::COLOR_LIGHT_STEEL_BLUE,
                Component::COLOR_LIGHT_YELLOW,
                Component::COLOR_LIME,
                Component::COLOR_LIME_GREEN,
                Component::COLOR_LINEN,
                Component::COLOR_MAGENTA,
                Component::COLOR_MAROON,
                Component::COLOR_MEDIUM_AQUAMARINE,
                Component::COLOR_MEDIUM_BLUE,
                Component::COLOR_MEDIUM_ORCHID,
                Component::COLOR_MEDIUM_PURPLE,
                Component::COLOR_MEDIUM_SEA_GREEN,
                Component::COLOR_MEDIUM_SLATE_BLUE,
                Component::COLOR_MEDIUM_SPRING_GREEN,
                Component::COLOR_MEDIUM_TURQUOISE,
                Component::COLOR_MEDIUM_VIOLET_RED,
                Component::COLOR_MIDNIGHT_BLUE,
                Component::COLOR_MINT_CREAM,
                Component::COLOR_MISTY_ROSE,
                Component::COLOR_MOCCASIN,
                Component::COLOR_NAVAJO_WHITE,
                Component::COLOR_NAVY,
                Component::COLOR_OLDLACE,
                Component::COLOR_OLIVE,
                Component::COLOR_OLIVE_DRAB,
                Component::COLOR_ORANGE,
                Component::COLOR_ORANGE_RED,
                Component::COLOR_ORCHID,
                Component::COLOR_PALE_GOLDENROD,
                Component::COLOR_PALE_GREEN,
                Component::COLOR_PALE_TURQUOISE,
                Component::COLOR_PALE_VIOLET_RED,
                Component::COLOR_PAPAYA_WHIP,
                Component::COLOR_PEACH_PUFF,
                Component::COLOR_PERU,
                Component::COLOR_PINK,
                Component::COLOR_PLUM,
                Component::COLOR_POWDER_BLUE,
                Component::COLOR_PURPLE,
                Component::COLOR_RED,
                Component::COLOR_ROSY_BROWN,
                Component::COLOR_ROYAL_BLUE,
                Component::COLOR_SADDLE_BROWN,
                Component::COLOR_SALMON,
                Component::COLOR_SANDY_BROWN,
                Component::COLOR_SEA_GREEN,
                Component::COLOR_SEASHELL,
                Component::COLOR_SIENNA,
                Component::COLOR_SILVER,
                Component::COLOR_SKY_BLUE,
                Component::COLOR_SLATE_BLUE,
                Component::COLOR_SLATE_GRAY,
                Component::COLOR_SLATE_GREY,
                Component::COLOR_SNOW,
                Component::COLOR_SPRING_GREEN,
                Component::COLOR_STEEL_BLUE,
                Component::COLOR_TAN,
                Component::COLOR_TEAL,
                Component::COLOR_THISTLE,
                Component::COLOR_TOMATO,
                Component::COLOR_TURQUOISE,
                Component::COLOR_VIOLET,
                Component::COLOR_WHEAT,
                Component::COLOR_WHITE,
                Component::COLOR_WHITE_SMOKE,
                Component::COLOR_YELLOW,
                Component::COLOR_YELLOW_GREEN,
                ])),
            Component::PROPERTY_COMMENT => $this->getText(random_int(16, 32)),
            Component::PROPERTY_CONFERENCE => '',
            Component::PROPERTY_CONTACT => '',
            Component::PROPERTY_DATETIME_COMPLETED => $this->getDatetime(),
            Component::PROPERTY_DATETIME_CREATED => $this->getDatetime(),
            Component::PROPERTY_DATETIME_DUE => $this->getDatetime(),
            Component::PROPERTY_DATETIME_END => $this->getDatetime(),
            Component::PROPERTY_DATETIME_STAMP => $this->getDatetime(),
            Component::PROPERTY_DATETIME_START => $this->getDatetime(),
            Component::PROPERTY_DESCRIPTION => $this->getText(random_int(50, 100)),
            Component::PROPERTY_DURATION => $this->getDuration(),
            Component::PROPERTY_EXCEPTION_DATE => $this->getDatetime(),
            Vfreebusy::PROPERTY_FREEBUSY => $this->getFreebusy(),
            Component::PROPERTY_GEOGRAPHIC_POSITION => $this->getGeo(),
            Component::PROPERTY_IMAGE => '',
            Component::PROPERTY_LAST_MODIFIED => $this->getDatetime(),
            Component::PROPERTY_LOCATION => '',
            Component::PROPERTY_METHOD => '',
            Component::PROPERTY_ORGANIZER => '',
            Component::PROPERTY_PERCENT_COMPLETE => random_int(0, 100),
            Component::PROPERTY_PRIORITY => random_int(0, 10),
            Component::PROPERTY_PRODUCT_IDENTIFIER => $this->getText(random_int(10, 25)),
            Component::PROPERTY_RECURRENCE_DATETIME => '',
            Component::PROPERTY_RECURRENCE_ID => $this->getDatetime(),
            Component::PROPERTY_RELATED_TO => '',
            Component::PROPERTY_REPEAT => random_int(0, 10),
            Component::PROPERTY_REQUEST_STATUS => '',
            Component::PROPERTY_RESOURCES => $this->getList(random_int(1, 5)),
            Component::PROPERTY_RECURRENCE_RULE => '',
            Component::PROPERTY_SEQUENCE => random_int(0, 50),
            Component::PROPERTY_STATUS => $this->getComponentStatus($component),
            Component::PROPERTY_SUMMARY => $this->getText(random_int(16, 48)),
            Component::PROPERTY_TIME_TRANSPARENCY => array_rand(array_flip([
                Component::TRANSPARENCY_OPAQUE,
                Component::TRANSPARENCY_TRANSPARENT
            ])),
            Valarm::PROPERTY_TRIGGER => $this->getDatetime(),
            Vtimezone::PROPERTY_TZ_ID => $this->getText(15),
            Standard::PROPERTY_TZ_NAME => $this->getText(5),
            Standard::PROPERTY_TZ_OFFSET_FROM => $this->getTzOffset(),
            Standard::PROPERTY_TZ_OFFSET_TO => $this->getTzOffset(),
            Vtimezone::PROPERTY_TZ_URL => '',
            Component::PROPERTY_UID => '',
            Component::PROPERTY_URL => '',
            Component::PROPERTY_VERSION => '',
        };
    }

    private function getComponentStatus(Component $component): string
    {
        return match ($component->getName()) {
            Vevent::NAME => array_rand(array_flip([
                Component::STATUS_CANCELLED,
                Component::STATUS_CONFIRMED,
                Component::STATUS_TENTATIVE,
            ])),
            Vjournal::NAME => array_rand(array_flip([
                Component::STATUS_CANCELLED,
                Component::STATUS_DRAFT,
                Component::STATUS_FINAL,
            ])),
            Vtodo::NAME => array_rand(array_flip([
                Component::STATUS_CANCELLED,
                Component::STATUS_COMPLETED,
                Component::STATUS_IN_PROCESS,
                Component::STATUS_NEEDS_ACTION,
            ])),
        };
    }

    private function getFreebusy()
    {
        return $this->getDatetime() . Vfreebusy::FREEBUSY_SEPARATOR . $this->getDuration();
    }

    private function getList(int $items): string
    {
        $list = [];

        for ($i = 0; $i < $items; $i++) {
            $list[] = $this->getText(random_int(4, 10));
        }

        return implode(Property::LIST_SEPARATOR, $list);
    }

    private function getText(int $length): string
    {
        $chars = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($chars) - 1;

        $text = '';

        do {
            $l = $length - strlen($text);

            for($i = 0; $i < $l; $i++) {
                $text .= $chars[random_int(0, $max)];
            }

            $text = trim($text, ' ');
        } while (strlen($text) < $length);

        return $text;
    }

    private function getTzOffset()
    {
        $ve = random_int(0, 1);
        $h = random_int(0, 11);
        $m = random_int(0, 59);

        return ($ve ? '+' : '-')
            . str_pad((string) $h, 2, '0')
            . str_pad((string) $m, 2, '0')
            ;
    }

    private function getDatetime(bool $dt = true, bool $utc = true): string
    {
        $y = (string) random_int(2000, 2050);
        $m = (string) random_int(1, 12);
        $d = (string) random_int(1, match ($m) {
            '2' => 28,
            '4', '6', '9', '11' => 30,
            default => 31
        });

        $date = $y
            . str_pad($m, 2, '0', STR_PAD_LEFT)
            . str_pad($d, 2, '0', STR_PAD_LEFT);

        if ($dt) {
            $h = str_pad((string) random_int(0, 23), 2, '0', STR_PAD_LEFT);
            $i = str_pad((string) random_int(0, 59), 2, '0', STR_PAD_LEFT);
            $s = str_pad((string) random_int(0, 59), 2, '0', STR_PAD_LEFT);

            return $date . 'T' . "$h$i$s" . ($utc ? 'Z' : '');
        }

        return $date;
    }

    private function getDuration(): string
    {
        $interval = random_int(5, 50);
        $units = array_rand(array_flip(['W', 'D', 'H', 'M']));
        return 'P' . (in_array($units, ['H', 'M']) ? 'T' : '') . $interval;
    }

    private function getGeo(): string
    {
        $latitude = random_int(-90000, 90000) / 1000;
        $longitude = random_int(-180000, 180000) / 1000;
        return $latitude . Property::GEO_SEPARATOR . $longitude;
    }
}