<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;

class MovieController
{
    public function __construct(
        private RouteCollectorInterface $routeCollector,
        private Environment $twig,
        private EntityManagerInterface $em
    ) {}

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $data = $this->twig->render('movie/index.html.twig', [
                'trailers' => $this->fetchData(),
                'dateTime' => date("Y/m/d") .' - '. date("h:i:s"),
            ]);

        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $id = (int)$request->getAttribute('id') > 0 ? (int)$request->getAttribute('id') : 0;
            $trailer = $this->em->getRepository(Movie::class)->find($id);
            if ($trailer) {
                $data = $this->twig->render('movie/show.html.twig', [
                    'trailer' => $trailer
                ]);
            } else {
                $data = $this->twig->render('404.html.twig');
            }

        } catch (\Exception $e) {

            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

    protected function fetchData(): Collection
    {
        $data = $this->em->getRepository(Movie::class)->findAll();

        return new ArrayCollection($data);
    }

    public function updateLike(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $id = (int)$request->getAttribute('id') > 0 ? (int)$request->getAttribute('id') : 0;
            $trailer = $this->em->getRepository(Movie::class)->find($id);
            if ($trailer) {
                $trailer->setLiked(true);
                $this->em->persist($trailer);
                $this->em->flush();
                $data = 'is liked';
            } else {
                $data = 'error';
            }

        } catch (\Exception $e) {

            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

}
