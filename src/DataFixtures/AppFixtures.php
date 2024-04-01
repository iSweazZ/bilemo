<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $clientList = [];

        for ($i = 0; $i < 3; $i++) {
            $client = new Client();
            $client->setEmail('client' . $i . '@bilemo.com');
            $client->setRoles(['ROLE_USER']);
            $client->setPassword($this->userPasswordHasher->hashPassword($client, '123456'));
            $manager->persist($client);
            $clientList[] = $client;
        }

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@bilemo.com');
            $user->setCreationDate(new DateTimeImmutable());
            $user->setClient($clientList[array_rand($clientList)]);
            $manager->persist($user);
        }

        $brands = ['Apple', 'Samsung', 'Google', 'Xiaomi', 'Oppo'];
        $models = ['14', '7 Plus', 'S22', '13 Ultra', 'X6 Pro'];
        $colors = ['Black', 'White', 'Grey', 'Red', 'Blue', 'Yellow', 'Green'];
        $memory = [32, 64, 128, 256, 512];
        $descriptions = ['Our brand new flagship', 'This is the product you need', 'Once you\'ve tried it, you can\'t go back', 'The best smartproduct overall', 'Simply different'];
        $prices = [249.99, 499.99, 999.99, 1299.99, 149.99];

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setBrand($brands[array_rand($brands)]);
            $product->setModel($models[array_rand($models)]);
            $product->setColor($colors[array_rand($colors)]);
            $product->setMemory($memory[array_rand($memory)]);
            $product->setDescription($descriptions[array_rand($descriptions)]);
            $product->setPrice($prices[array_rand($prices)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
