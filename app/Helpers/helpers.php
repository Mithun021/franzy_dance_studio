<?php

if (!function_exists('formatAmount')) {

    function formatAmount($amount, $decimals = 2)
    {
        $amount = (float) $amount;

        // Less than 1000
        if ($amount < 1000) {
            return number_format($amount, 0);
        }

        // Thousand
        if ($amount < 100000) {

            $value = $amount / 1000;

            return rtrim(
                rtrim(number_format($value, $decimals), '0'),
                '.'
            ) . 'K';
        }

        // Lakh
        if ($amount < 10000000) {

            $value = $amount / 100000;

            return rtrim(
                rtrim(number_format($value, $decimals), '0'),
                '.'
            ) . 'L';
        }

        // Crore
        $value = $amount / 10000000;

        return rtrim(
            rtrim(number_format($value, $decimals), '0'),
            '.'
        ) . 'Cr';
    }
}
