<?php
namespace Acme\HelloBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use bancopruebas\NoticiaBundle\Entity\Noticia;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
       $faker = Faker\Factory::create(); 
       for($n=1;$n<=20;$n++){ 
        $userAdmin = new User();
        $userAdmin->setTitulo($faker->name);
        $userAdmin->setTexto($faker->text);
        $manager->persist($userAdmin);
		}
        $manager->flush();
    }
}
