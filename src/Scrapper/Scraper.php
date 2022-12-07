<?php

namespace App\Scrapper;

use App\Scrapper\Contracts\SourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
use App\Entity\Source;

class Scraper
{
    public function scrap(SourceInterface $source): Collection
    {
        $collection = [];
        $data_collection = array();
        $client = new Client();
        $crawler = $client->request('GET', $source->getUrl());
        
        
        $crawler->filter($source->getWrapperSelector())->each(function (Crawler $cell) use ($source, &$data_collection){
          
            $cell->filter(".article")->each(function (Crawler $c) use ($source, &$data_collection){
                
                $source_entity = new Source();

                $title = $c->filter($source->getTitleSelector())->text();
                $source_entity->setTitle($title);

                $dateTime = $c->filter($source->getDateSElector())->text();
                $source_entity->setDateAdded($dateTime);

                $description = ($c->filter($source->getDescSelector())->text());
                $source_entity->setDescription($description);

                $image = ($c->filter($source->getImageSelector())->text());
                $source_entity->setPicture($image);

                array_push($data_collection, $source_entity);
            });
           
        });


        return new ArrayCollection($data_collection);
    }
}