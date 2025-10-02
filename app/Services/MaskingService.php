<?php

namespace App\Services;

class MaskingService
{
    /**
     * Mask Turkish ID number (TC Kimlik No).
     *
     * @param string|null $idNumber
     * @return string|null
     */
    public static function maskIdNumber(?string $idNumber): ?string
    {
        if (!$idNumber || strlen($idNumber) !== 11) return $idNumber;
        return substr($idNumber, 0, 3) . '******' . substr($idNumber, -2);
    }

    /**
     * Mask phone number.
     *
     * @param string|null $phone
     * @return string|null
     */
    public static function maskPhone(?string $phone): ?string
    {
        if (!$phone) return $phone;
        // Turkish phone format: +90 5XX XXX XX XX
        $cleaned = preg_replace('/\D/', '', $phone);
        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '05')) {
            // Mobile: 05XX XXX XX XX -> 05XX *** ** XX
            return substr($cleaned, 0, 4) . '***' . substr($cleaned, -4);
        }
        // Other formats: keep first 3 and last 2 digits
        return substr($cleaned, 0, 3) . str_repeat('*', max(0, strlen($cleaned) - 5)) . substr($cleaned, -2);
    }

    /**
     * Mask email address.
     *
     * @param string|null $email
     * @return string|null
     */
    public static function maskEmail(?string $email): ?string
    {
        if (!$email) return $email;
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;

        $local = $parts[0];
        $domain = $parts[1];

        // Keep first 2 chars of local part, mask the rest
        $maskedLocal = strlen($local) > 2
            ? substr($local, 0, 2) . str_repeat('*', strlen($local) - 2)
            : $local;

        return $maskedLocal . '@' . $domain;
    }

    /**
     * Mask address text.
     *
     * @param string|null $address
     * @return string|null
     */
    public static function maskAddress(?string $address): ?string
    {
        if (!$address) return $address;
        // Mask middle part of address
        $words = explode(' ', $address);
        $maskedWords = array_map(function ($word, $index) use ($words) {
            if ($index === 0 || $index === count($words) - 1) {
                return $word; // Keep first and last words
            }
            return strlen($word) > 2 ? substr($word, 0, 1) . str_repeat('*', strlen($word) - 1) : $word;
        }, $words, array_keys($words));

        return implode(' ', $maskedWords);
    }

    /**
     * Mask general text content.
     *
     * @param string|null $text
     * @return string|null
     */
    public static function maskText(?string $text): ?string
    {
        if (!$text) return $text;
        $length = strlen($text);
        if ($length <= 4) return str_repeat('*', $length);

        // Keep first 2 and last 2 characters
        return substr($text, 0, 2) . str_repeat('*', $length - 4) . substr($text, -2);
    }

    /**
     * Apply masking to an entire data array recursively.
     *
     * @param array $data
     * @param array $sensitiveFields
     * @return array
     */
    public static function maskDataArray(array $data, array $sensitiveFields = []): array
    {
        $defaultSensitive = [
            'national_id', 'phone', 'email', 'address', 'notes', 'description'
        ];

        $sensitiveFields = array_merge($defaultSensitive, $sensitiveFields);

        return self::recursiveMask($data, $sensitiveFields);
    }

    /**
     * Recursively mask sensitive data in array.
     *
     * @param mixed $data
     * @param array $sensitiveFields
     * @return mixed
     */
    private static function recursiveMask($data, array $sensitiveFields)
    {
        if (!is_array($data)) {
            return $data;
        }

        $masked = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $masked[$key] = self::recursiveMask($value, $sensitiveFields);
            } elseif (is_string($value) && in_array(strtolower($key), $sensitiveFields)) {
                $masked[$key] = self::maskByFieldType($key, $value);
            } else {
                $masked[$key] = $value;
            }
        }

        return $masked;
    }

    /**
     * Mask value based on field name.
     *
     * @param string $fieldName
     * @param string $value
     * @return string
     */
    private static function maskByFieldType(string $fieldName, string $value): string
    {
        $fieldName = strtolower($fieldName);

        if (str_contains($fieldName, 'id') && strlen($value) === 11) {
            return self::maskIdNumber($value);
        }

        if (str_contains($fieldName, 'phone')) {
            return self::maskPhone($value);
        }

        if (str_contains($fieldName, 'email')) {
            return self::maskEmail($value);
        }

        if (str_contains($fieldName, 'address')) {
            return self::maskAddress($value);
        }

        return self::maskText($value);
    }
}
