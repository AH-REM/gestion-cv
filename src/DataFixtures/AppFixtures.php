<?php

namespace App\DataFixtures;

use App\Entity\Intervenant;
use App\Entity\Niveau;
use App\Entity\Diplome;
use App\Entity\TypeEmploi;
use App\Entity\Domaine;
use App\Entity\Role;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function createIntervenant(ObjectManager $manager, Diplome $diplome, TypeEmploi $emploi, Array $domaines) {

        $faker = Factory::create('fr_FR');

        $intervenant = new Intervenant();
        $intervenant->setNom($faker->lastName())
                    ->setPrenom($faker->firstName())
                    ->setAdresse($faker->address())
                    ->setCp($faker->postcode())
                    ->setMail($faker->safeEmail())
                    ->setNameCv('test_test_5e7b7bb41ce12.pdf')
                    ->setDateMajCv(new \DateTime())
                    ->setCreatedAt(new \DateTime())
                    ->setDiplome($diplome)
                    ->setEmploi($emploi);

        $count = rand(1, 4);
        foreach ($domaines as $domaine) {
            if ($count <= 0) break;
            $intervenant->addDomaine($domaine);
            $count--;
        }

        $manager->persist($intervenant);

    }

    public function load(ObjectManager $manager)
    {

        // Role
        $admin = new Role();
        $admin->setName('ROLE_ADMIN')->setLibelle('Administrateur');
        $manager->persist($admin);

        $gestion = new Role();
        $gestion->setName('ROLE_GESTION')->setLibelle('Gestionnaire');
        $manager->persist($gestion);

        // User
        $user = new User();
        $hash = '$2y$10$t4AhfrV7OZMYOei8y1ozZOlDxa.gaV4QPqy3EqU6MwiEDU0jOUX4C'; // admin
        $user->setUsername('admin')->setPassword($hash)->setRole($admin);
        $manager->persist($user);

        // Emploi
        $formateur = new TypeEmploi();
        $formateur->setLibelle('Formateur');
        $manager->persist($formateur);

        $administrif = new TypeEmploi();
        $administrif->setLibelle('Administrif');
        $manager->persist($administrif);

        // Domaine
        $info = new Domaine();
        $info->setLibelle('Informatique');
        $manager->persist($info);

        $main = new Domaine();
        $main->setLibelle('Maintenance');
        $manager->persist($main);

        $secu = new Domaine();
        $secu->setLibelle('Sécurité');
        $manager->persist($secu);

        $fr = new Domaine();
        $fr->setLibelle('Français');
        $manager->persist($fr);

        $en = new Domaine();
        $en->setLibelle('Anglais');
        $manager->persist($en);

        $eco = new Domaine();
        $eco->setLibelle('Economie');
        $manager->persist($eco);

        $droit = new Domaine();
        $droit->setLibelle('Droit');
        $manager->persist($droit);

        $bota = new Domaine();
        $bota->setLibelle('Botanique');
        $manager->persist($bota);

        // Niveau
        $bac = new Niveau();
        $bac->setNum(4)->setLibelle('BAC');
        $manager->persist($bac);

        $bts = new Niveau();
        $bts->setNum(5)->setLibelle('BTS');
        $manager->persist($bts);

        $master = new Niveau();
        $master->setNum(7)->setLibelle('Master');
        $manager->persist($master);

        // Diplome
        $sio = new Diplome();
        $sio->setNiveau($bts)->setLibelle('BTS SIO');
        $manager->persist($sio);

        for ($i = 0; $i < rand(15, 35); $i++) {
            $this->createIntervenant($manager, $sio, $formateur, [$info, $secu, $en, $eco, $fr]);
        }

        // -----

        $muc = new Diplome();
        $muc->setNiveau($bts)->setLibelle('BTS MUC');
        $manager->persist($muc);

        for ($i = 0; $i < rand(15, 35); $i++) {
            $this->createIntervenant($manager, $muc, $formateur, [$en, $eco, $fr, $droit]);
        }

        // -----

        $mei = new Diplome();
        $mei->setNiveau($bac)->setLibelle('BAC MEI');
        $manager->persist($mei);

        for ($i = 0; $i < rand(15, 35); $i++) {
            $this->createIntervenant($manager, $mei, $formateur, [$en, $fr, $main, $info]);
        }

        // -----

        $b = new Diplome();
        $b->setNiveau($bac)->setLibelle('BAC Botanique');
        $manager->persist($b);

        for ($i = 0; $i < rand(15, 35); $i++) {
            $this->createIntervenant($manager, $b, $formateur, [$bota, $fr, $en]);
        }

        // -----

        $ing = new Diplome();
        $ing->setNiveau($master)->setLibelle('Master Ingénieur Informatique');
        $manager->persist($ing);

        for ($i = 0; $i < rand(15, 35); $i++) {
            $this->createIntervenant($manager, $ing, $formateur, [$info, $fr, $en, $main, $eco, $droit]);
        }

        // -----

        $manager->flush();
    }
}
