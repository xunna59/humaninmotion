<?php

namespace App\Support;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as PHPMoney;

final class Money
{
    public static function make(float|int|string $amount, string $currency = 'GBP'): PHPMoney
    {
        return new PHPMoney((int) round(((float) $amount) * 100), new Currency($currency));
    }

    public static function formatFloat(float|int|string $amount, string $currency = 'GBP'): string
    {
        return self::format(self::make($amount, $currency));
    }

    public static function format(PHPMoney $money): string
    {
        $fmt = new \NumberFormatter('en_GB', \NumberFormatter::CURRENCY);

        return (new IntlMoneyFormatter($fmt, new ISOCurrencies()))->format($money);
    }

    public static function fromInt(int $amount, string $currency = 'GBP'): PHPMoney
    {
        return new PHPMoney($amount, new Currency($currency));
    }
}