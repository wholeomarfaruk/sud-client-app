<?php
//Helpers/Helpers.php
use App\Models\File;

if (!function_exists('file_path')) {
    function file_path($id, $type = 'original')
    {
        $file = File::with('items')->find($id);

        if (!$file) {
            return null;
        }

        $item = $file->items->firstWhere('type', $type);

        return $item ? asset('storage/' . $item->path) : null;
    }
}

if (!function_exists('bdt_money')) {
    // Bengali-style digit grouping, e.g. 3850000 -> "৳ 38,50,000"
    function bdt_money($amount, bool $withSymbol = true): string
    {
        $negative = (float) $amount < 0;
        $intPart = (string) (int) round(abs((float) $amount));

        if (strlen($intPart) > 3) {
            $lastThree = substr($intPart, -3);
            $rest = substr($intPart, 0, -3);
            $groups = [];

            while (strlen($rest) > 2) {
                $groups[] = substr($rest, -2);
                $rest = substr($rest, 0, -2);
            }

            if ($rest !== '') {
                $groups[] = $rest;
            }

            $formatted = implode(',', array_reverse($groups)) . ',' . $lastThree;
        } else {
            $formatted = $intPart;
        }

        $formatted = ($negative ? '-' : '') . $formatted;

        return $withSymbol ? '৳ ' . $formatted : $formatted;
    }
}

if (!function_exists('bdt_lakh')) {
    // Compact lakh notation, e.g. 3850000 -> "৳ 38.5L", 2000000 -> "৳ 20L"
    function bdt_lakh($amount, bool $withSymbol = true): string
    {
        $negative = (float) $amount < 0;
        $lakh = abs((float) $amount) / 100000;

        $formatted = rtrim(rtrim(number_format($lakh, 2, '.', ''), '0'), '.');
        $formatted = ($negative ? '-' : '') . $formatted . 'L';

        return $withSymbol ? '৳ ' . $formatted : $formatted;
    }
}

if (!function_exists('unit_type_label')) {
    // ERP's App\Enums\Property\UnitType values -> display label
    function unit_type_label(string $type): string
    {
        return match ($type) {
            'flat' => 'Flat',
            'shop' => 'Shop',
            'community_center' => 'Community Center',
            'office' => 'Office',
            'parking' => 'Parking',
            'showroom' => 'Showroom',
            'warehouse' => 'Warehouse',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }
}

if (!function_exists('unit_type_variant')) {
    // CSS-safe bucket: only flat/shop/parking/community_center have dedicated
    // thumbnail colors in the design; anything else falls back to 'flat'.
    function unit_type_variant(string $type): string
    {
        return in_array($type, ['flat', 'shop', 'parking', 'community_center'], true) ? $type : 'flat';
    }
}

if (!function_exists('unit_status_label')) {
    // 'on_installment' -> 'ON INSTALLMENT' (matches the pill copy in the design)
    function unit_status_label(string $status): string
    {
        return match ($status) {
            'booked' => 'BOOKED',
            'on_installment' => 'ON INSTALLMENT',
            'purchased' => 'PURCHASED',
            'handover' => 'HANDOVER',
            'rented' => 'RENTED',
            default => strtoupper(str_replace('_', ' ', $status)),
        };
    }
}

if (!function_exists('payment_status_label')) {
    function payment_status_label(string $status): string
    {
        return match ($status) {
            'paid' => 'PAID',
            'partial' => 'PARTIAL',
            'pending' => 'PENDING',
            default => strtoupper($status),
        };
    }
}

if (!function_exists('initials')) {
    // "Md. Rafiqul Islam" -> "RI" (skips honorific prefixes like Md./Mr./Mrs./Ms.)
    function initials(string $name): string
    {
        $words = array_filter(
            preg_split('/\s+/', trim($name)),
            fn ($word) => ! in_array(mb_strtolower(rtrim($word, '.')), ['md', 'mr', 'mrs', 'ms'], true)
        );

        $letters = array_map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: '?';
    }
}
