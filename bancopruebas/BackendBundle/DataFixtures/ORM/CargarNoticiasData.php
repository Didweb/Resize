<?php
namespace bancopruebas\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use bancopruebas\BackendBundle\Entity\Noticia;

class CargarNoticiasData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
       $faker = \Faker\Factory::create(); 
       for($n=1;$n<=20;$n++){ 
        $userAdmin = new Noticia();
        $numcarac = rand(25,40);
        $tit = substr($faker->text,0,$numcarac);
        $userAdmin->setTitulo($tit);
        $userAdmin->setTexto($faker->text);
        $manager->persist($userAdmin);
		}
        $manager->flush();
    }
}
