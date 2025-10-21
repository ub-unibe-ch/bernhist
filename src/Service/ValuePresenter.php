<?php

namespace App\Service;

use App\Entity\DataEntry;

class ValuePresenter
{
    /**
     * @var DataEntry[]
     */
    private static array $dataEntries = [];

    private static int $decimals = 0;

    public static function present(?string $value): string
    {
        $value = \is_string($value) ? (float) $value : 0;

        if ([] === self::$dataEntries) {
            return (string) $value;
        }

        return number_format($value, self::$decimals, '.', "'");
    }

    /**
     * @param DataEntry[] $dataEntries
     */
    public static function setDataEntries(array $dataEntries): void
    {
        self::$dataEntries = $dataEntries;
        self::$decimals = 0;

        foreach ($dataEntries as $dataEntry) {
            $arr = explode('.', (string) $dataEntry->getValue());
            $decimals = $arr[1];
            for ($i = 0; $i < \strlen($decimals); ++$i) {
                $digit = substr($decimals, $i, 1);
                if ('0' !== $digit && $i + 1 > self::$decimals) {
                    self::$decimals = $i + 1;
                }
            }
        }
    }
}
