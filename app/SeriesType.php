<?php

namespace App;

enum SeriesType
{
    const MANGA = 'manga';
    const NOVEL = 'novel';
    const MANHWA = 'manhwa';
    const MANHUA = 'manhua';

    public static function all()
    {
        return [
            self::MANGA,
            self::NOVEL,
            self::MANHWA,
            self::MANHUA,
        ];
    }
}
