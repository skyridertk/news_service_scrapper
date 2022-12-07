<?php
// src/MessageHandler/SmsNotificationHandler.php
namespace App\MessageHandler;

use App\Controller\SourceController;
use App\Entity\Source;
use App\Message\NewsSourcesMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\Persistence\ManagerRegistry;

class NewsSourcesMessageHandler implements MessageHandlerInterface
{
    private SourceController $sourceController;
    private ManagerRegistry $doctrine;

    public function __construct(
        SourceController $sourceController,
        ManagerRegistry $doctrine
    ) 
    {
        $this->sourceController = $sourceController;
        $this->doctrine = $doctrine;
    }

    public function __invoke(NewsSourcesMessage $newsSourcesMessage)
    {
       
        $output = json_decode($newsSourcesMessage->getContent());

        $source = new Source();
        $source->setTitle($output->title);
        $source->setDescription($output->description);
        $source->setPicture($output->picture);
        $source->setDateAdded($output->dateadded);

        $resp = $this->sourceController->findSourceByTitle($this->doctrine, $source->getTitle());
        
        $id = json_decode($resp->getContent())->data;
        if($id !=0){
            $this->sourceController->updateDate($this->doctrine, $id, $source->getDateAdded());
        } else {

            $this->sourceController->createSource($this->doctrine, $source);
        }

    }
}