<?php

namespace App\Controller;

use App\Scrapper\Scraper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Message\NewsSourcesMessage;
use App\Sources\IGN;

class MessageController extends AbstractController
{
    private Scraper $scraper;

    public function __construct(
        Scraper $scraper
    ) 
    {
        $this->scraper = $scraper;
    }

    public function createMessages(MessageBusInterface $bus): JsonResponse
    {
        $ignNewsSource = new IGN();

        $data = $this->scraper->scrap($ignNewsSource);
        
        foreach ($data as $key => $value) {
           
            $message = json_encode([
                'title' => $value->getTitle(),
                'description' => $value->getDescription(),
                'picture' => $value->getPicture(),
                'dateadded' => $value->getDateAdded(),
            ]);
            
            $bus->dispatch(new NewsSourcesMessage($message));
        }

        return new JsonResponse(['status' => 'Success']);
    }
}