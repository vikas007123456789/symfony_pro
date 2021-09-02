<?php

namespace App\DataFixtures;

use App\Entity\Make;
use App\Entity\Model;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MakeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $file_path = getcwd().'/../assignment/make-model.json';

        $read_csv = $str = file_get_contents($file_path);
        if($read_csv){
            $data_decode =  json_decode($read_csv);

            foreach($data_decode as $_data){

                $make = new Make();
                $make->setName($_data->make);

                foreach($_data->models as $_models){
                    $model = new Model();
                    $model->setName($_models);
                    $manager->persist($model);
                    $make->addModel($model);
                }
                $manager->persist($make);
                $manager->flush();

            }

        }


    }
}
