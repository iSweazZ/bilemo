<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="app_products", methods="GET")
     */
    public function getAllProducts(ProductRepository $productRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);
        $productsList = $productRepository->findAllWithPagination($page, $limit);

        $context = SerializationContext::create()->setGroups(["getProducts"]);
        $jsonProductsList = $serializer->serialize($productsList, 'json', $context);

        return new JsonResponse($jsonProductsList, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/products/{id}", name="app_product_details", methods="GET")
     * @Entity("product", expr="repository.find(id)")
     */
    public function getProductDetails(Product $product, SerializerInterface $serializer): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(["getProducts"]);
        $jsonProduct = $serializer->serialize($product, 'json', $context);

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }
}
