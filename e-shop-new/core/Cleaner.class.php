<?php
class Cleaner{
    static function int($data): int{
        return (int) $data;
    }
    static function uint($data): int{
        return abs(self::int($data));
    }
    static function str($data): string{
        return trim(strip_tags($data));
    }
    static function str2db($data, PDO $db): string{
        return $db->quote(self::str($data));
    }
    static function str2quote($data, PDO $db): string{
        return $db->quote($data);
    }
}