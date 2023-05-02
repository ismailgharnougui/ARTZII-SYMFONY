<?php

namespace App\Test\Controller;

use App\Entity\Echange;
use App\Repository\EchangeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EchangeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EchangeRepository $repository;
    private string $path = '/echange/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Echange::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Echange index');

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
            'echange[lieu_echange]' => 'Testing',
            'echange[lieu_offre]' => 'Testing',
            'echange[statut]' => 'Testing',
            'echange[produit_echange]' => 'Testing',
            'echange[produit_offert]' => 'Testing',
            'echange[livreur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/echange/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Echange();
        $fixture->setLieu_echange('My Title');
        $fixture->setLieu_offre('My Title');
        $fixture->setStatut('My Title');
        $fixture->setProduit_echange('My Title');
        $fixture->setProduit_offert('My Title');
        $fixture->setLivreur('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Echange');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Echange();
        $fixture->setLieu_echange('My Title');
        $fixture->setLieu_offre('My Title');
        $fixture->setStatut('My Title');
        $fixture->setProduit_echange('My Title');
        $fixture->setProduit_offert('My Title');
        $fixture->setLivreur('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'echange[lieu_echange]' => 'Something New',
            'echange[lieu_offre]' => 'Something New',
            'echange[statut]' => 'Something New',
            'echange[produit_echange]' => 'Something New',
            'echange[produit_offert]' => 'Something New',
            'echange[livreur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/echange/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getLieu_echange());
        self::assertSame('Something New', $fixture[0]->getLieu_offre());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getProduit_echange());
        self::assertSame('Something New', $fixture[0]->getProduit_offert());
        self::assertSame('Something New', $fixture[0]->getLivreur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Echange();
        $fixture->setLieu_echange('My Title');
        $fixture->setLieu_offre('My Title');
        $fixture->setStatut('My Title');
        $fixture->setProduit_echange('My Title');
        $fixture->setProduit_offert('My Title');
        $fixture->setLivreur('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/echange/');
    }
}
