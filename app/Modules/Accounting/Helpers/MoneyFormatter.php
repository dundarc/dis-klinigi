<?php

namespace App\Modules\Accounting\Helpers;

class MoneyFormatter
{
    protected static array $currencies = [
        'TRY' => ['symbol' => '₺', 'locale' => 'tr_TR', 'decimals' => 2],
        'USD' => ['symbol' => '$', 'locale' => 'en_US', 'decimals' => 2],
        'EUR' => ['symbol' => '€', 'locale' => 'de_DE', 'decimals' => 2],
        'GBP' => ['symbol' => '£', 'locale' => 'en_GB', 'decimals' => 2],
    ];

    public static function format(float $amount, string $currency = 'TRY', bool $withSymbol = true): string
    {
        $currency = strtoupper($currency);
        $config = self::$currencies[$currency] ?? self::$currencies['TRY'];

        $formatter = new \NumberFormatter($config['locale'], \NumberFormatter::CURRENCY);

        if (!$withSymbol) {
            $formatter = new \NumberFormatter($config['locale'], \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $config['decimals']);
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $config['decimals']);
            return $formatter->format($amount);
        }

        return $formatter->formatCurrency($amount, $currency);
    }

    public static function getCurrencySymbol(string $currency): string
    {
        $currency = strtoupper($currency);
        return self::$currencies[$currency]['symbol'] ?? self::$currencies['TRY']['symbol'];
    }

    public static function getSupportedCurrencies(): array
    {
        return array_keys(self::$currencies);
    }

    public static function isValidCurrency(string $currency): bool
    {
        return isset(self::$currencies[strtoupper($currency)]);
    }

    public static function convert(float $amount, string $fromCurrency, string $toCurrency, array $rates = []): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Simple conversion - in real app, use actual exchange rates
        $rate = $rates[$fromCurrency . '_' . $toCurrency] ?? 1.0;

        return round($amount * $rate, 2);
    }
}