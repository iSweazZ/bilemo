<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", name="app_users", methods="GET")
     */
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        /** @var Client $client **/
        $client = $this->getUser();

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);
        $usersList = $userRepository->findAllWithPaginationByClient($client->getId(), $page, $limit);

        $context = SerializationContext::create()->setGroups(["getUsers"]);
        $jsonUsersLists = $serializer->serialize($usersList, 'json', $context);

        return new JsonResponse($jsonUsersLists, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/users/{id}", name="app_user_details", methods="GET")
     * @Entity("user", expr="repository.find(id)")
     */
    public function getUserDetails(User $user, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        if ($user->getClient() !== $this->getUser()) {
            throw new AccessDeniedHttpException("Vous ne pouvez pas voir les détails de cet utilisateur.");
        }

        $context = SerializationContext::create()->setGroups(["getUsers"]);
        $jsonUser = $serializer->serialize($user, 'json', $context);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/users", name="app_create_user", methods="POST")
     */
    public function createUser(Request $request, SerializerInterface $serializer, ClientRepository $clientRepository, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $content = $request->toArray();

        if (!(filter_var($content['email'], FILTER_VALIDATE_EMAIL))) {
            throw new AccessDeniedHttpException("L'adresse email que vous avez renseigné n'est pas valide.");
        }

        $user->setEmail($content['email']);
        $user->setCreationDate(new DateTime());
        $user->setClient($this->getUser());

        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $em->persist($user);
        $em->flush();

        $context = SerializationContext::create()->setGroups(["getUsers"]);
        $jsonUser = $serializer->serialize($user, 'json', $context);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("api/users/{id}", name="app_delete_user", methods="DELETE")
     * @Entity("user", expr="repository.find(id)")
     */
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        if ($user->getClient() !== $this->getUser()) {
            throw new AccessDeniedHttpException("Vous n'avez pas les droits pour supprimer cet utilisateur.");
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
