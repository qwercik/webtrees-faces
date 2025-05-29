<?php

namespace UksusoFF\WebtreesModules\Faces\Helpers;

use Fisharebest\Webtrees\Individual;

class GedcomHelper
{
    public static function getBirthName(Individual $individual): ?string
    {
        $names = $individual->getAllNames();
        foreach ($names as $name) {
            if ($name['type'] === 'NAME') {
                return $name['fullNN'] ?? null;
            }
        }
    
        return null;
    }
}
