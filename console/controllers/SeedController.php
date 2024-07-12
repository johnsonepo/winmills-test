<?php

namespace console\controllers;

use yii\console\Controller;
use common\models\User;
use Faker\Factory;

class SeedController extends Controller
{
    public function actionIndex()
    {
        echo "Seeding users...\n";
        $faker = Factory::create();

        for ($i = 0; $i < 2; $i++) {
            $admin = new User();
            if ($i == 0) {
                $admin->username = 'admin';
            } else {
                $admin->username = $faker->userName;
            }
            $admin->email = $faker->email;
            $admin->setPassword('admin'); 
            $admin->generateAuthKey();
            $admin->status = 10; 
            $admin->save();
            echo "Admin user created: {$admin->username}\n";
        }
        

        for ($i = 0; $i < 50; $i++) {
            $password= 'user';
            $user = new User();
            $user->username = $faker->userName;
            $user->email = $faker->email;
            $user->setPassword($password); 
            $user->generateAuthKey();
            if ($i < 41) {
                $user->status = 10; 
                $user->status = 9; 
            }
            $user->save();
            echo "Regular user created: {$user->username}\n";
        }

        echo "Seeding completed.\n";
    }
}
