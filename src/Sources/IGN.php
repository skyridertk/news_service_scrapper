<?php

namespace App\Sources;

use App\Scrapper\Contracts\SourceInterface;

class IGN implements SourceInterface
{
    public function getUrl(): string
    {
        return "https://za.ign.com/article/news";
    }

    public function getName(): string
    {
        return 'IGN';
    }

    public function getWrapperSelector(): string
    {
        return 'body > section.broll.wrap > div.tbl';
    }

    public function getTitleSelector(): string
    {
        return 'div.m > h3 > a';
    }

    public function getDateSelector(): string
    {
        return 'div.m > div > time';
    }

    public function getDescSelector(): string
    {
        return 'div.m > p';
    }

    public function getImageSelector(): string
    {
        return 'img';
        
    }
}