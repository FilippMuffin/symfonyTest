<?php

namespace App\Controller\API;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/category")
 */
class CategoryApiController extends AbstractController
{
    /**
     * @Route("/", name="api_category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories);
    }

    /**
     * @Route("/new", name="api_category_new", methods={"POST"})
     */
    public function new(Request $request, Serializer $serializer, EntityManagerInterface $em): Response
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, "json");
        $em->persist($category);
        $em->flush();

        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="api_category_show", methods={"POST"})
     */
    public function show(Category $category): Response
    {
        return $this->json($category);
    }

    /**
     * @Route("/{id}/edit", name="api_category_edit", methods={"POST"})
     */
    public function edit(Request $request, Serializer $serializer, EntityManagerInterface $em): Response
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, "json");
        $em->flush();

        return $this->json($category);
    }

    /**
     * @Route("/{id}", name="api_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json('Ok');
    }
}
