<?php

namespace App\Test\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProductRepository $repository;
    private string $path = '/product/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Product::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'product[nom_produit]' => 'Testing',
            'product[description]' => 'Testing',
            'product[categorie]' => 'Testing',
            'product[price]' => 'Testing',
            'product[image]' => 'Testing',
            'product[user]' => 'Testing',
            'product[baskets]' => 'Testing',
            'product[commande]' => 'Testing',
        ]);

        self::assertResponseRedirects('/product/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setNom_produit('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setPrice('My Title');
        $fixture->setImage('My Title');
        $fixture->setUser('My Title');
        $fixture->setBaskets('My Title');
        $fixture->setCommande('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setNom_produit('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setPrice('My Title');
        $fixture->setImage('My Title');
        $fixture->setUser('My Title');
        $fixture->setBaskets('My Title');
        $fixture->setCommande('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'product[nom_produit]' => 'Something New',
            'product[description]' => 'Something New',
            'product[categorie]' => 'Something New',
            'product[price]' => 'Something New',
            'product[image]' => 'Something New',
            'product[user]' => 'Something New',
            'product[baskets]' => 'Something New',
            'product[commande]' => 'Something New',
        ]);

        self::assertResponseRedirects('/product/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom_produit());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getCategorie());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getImage());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getBaskets());
        self::assertSame('Something New', $fixture[0]->getCommande());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Product();
        $fixture->setNom_produit('My Title');
        $fixture->setDescription('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setPrice('My Title');
        $fixture->setImage('My Title');
        $fixture->setUser('My Title');
        $fixture->setBaskets('My Title');
        $fixture->setCommande('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/product/');
    }
}
