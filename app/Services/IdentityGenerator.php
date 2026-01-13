<?php

namespace App\Services;

class IdentityGenerator
{
    protected static $adjectives = [
        'Neon', 'Silent', 'Cosmic', 'Shadow', 'Electric', 'Velvet', 'Cyber', 'Solar', 'Lunar', 'Arctic',
        'Crimson', 'Azure', 'Golden', 'Silver', 'Obsidian', 'Emerald', 'Violet', 'Mystic', 'Rapid', 'Wild'
    ];

    protected static $nouns = [
        'Tiger', 'Echo', 'Wolf', 'Falcon', 'Phoenix', 'Ghost', 'Storm', 'Hawk', 'Fox', 'Bear',
        'Dragon', 'Eagle', 'Lion', 'Panther', 'Cobra', 'Raven', 'Viper', 'Shark', 'Lynx', 'Owl'
    ];

    public static function generate(): string
    {
        $adjective = self::$adjectives[array_rand(self::$adjectives)];
        $noun = self::$nouns[array_rand(self::$nouns)];
        
        return "{$adjective} {$noun}";
    }
}
