<?php

namespace App\Controller;

use App\Entity\Source;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PaginatorService;
use Symfony\Component\HttpFoundation\Request;

class SourceController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('source/index.html.twig', [
            'controller_name' => 'SourceController',
        ]);
    }

    public function createSource(ManagerRegistry $doctrine, Source $source): Response
    {
        $entityManager = $doctrine->getManager();

        $entityManager->persist($source);

        $entityManager->flush();

        return new Response('Saved new source with id '.$source->getId());
    }

    public function showAll(ManagerRegistry $doctrine, Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_ADMIN');
    
        $page = $request->query->get('page');
        if(is_null($page)){
            $page = 1;
        }
        $source = $doctrine->getRepository(Source::class)->getPaginatedSources($page);

        return $this->render('source/list.html.twig', [
            'pagination' => $source
        ]);
    }


    public function remove(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $source = $entityManager->getRepository(Source::class)->find($id);

        if (!$source) {
            throw $this->createNotFoundException(
                'No source found for id '.$id
            );
        }

        $entityManager->remove($source);
        $entityManager->flush();

        return $this->redirectToRoute('fetch_all_sources');
    }


    public function findSourceByTitle(ManagerRegistry $doctrine, string $title): JsonResponse
    {
        $source =  $doctrine->getRepository(Source::class)->findOneBy(['title' => $title]);

        if (!$source) {
            return new JsonResponse(['status' => 'False', 'data' => 0]);
        }

        return new JsonResponse(['status' => 'Success', 'data' => $source->getId()]);
    }

    public function updateDate(ManagerRegistry $doctrine, int $id, string $date): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $source = $entityManager->getRepository(Source::class)->find($id);

        if (!$source) {
            
            return new JsonResponse(['status' => 'No source found for id '.$id]);
        }

        $source->setDateAdded($date);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Success']);
    }

    
}
