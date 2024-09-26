<?php

namespace App\Service;

class StringUtils
{
    public function capitalize($string)
    {
        return ucfirst(mb_strtolower($string));
    }
}