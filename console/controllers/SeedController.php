<?php

namespace console\controllers;

use common\constants\UserStatus;
use yii\console\Controller;
use common\models\User;
use Faker\Factory;
use Yii;
use common\services\UserService;

class SeedController extends Controller
{
    public function actionIndex()
    {
        echo "Seeding users...\n";
        $faker = Factory::create();
        $userService = new UserService();

        // Seed admin user
        $admin = new User();
        $admin->username = 'admin';
        $admin->email = 'admin@envoos.com'; 
        $admin->setPassword('admin');
        $admin->type = 'admin';
        $admin->generateAuthKey();
        $admin->status = UserStatus::STATUS_ACTIVE; 
        if (!$admin->save()) {
            print_r($admin->errors); 
        } else {
            $userService->assignRole($admin);
            echo "Admin created: {$admin->username}\n";
        }

        // Seed regular users
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->username = $this->generateValidUsername($faker);
            $user->email = $faker->email;
            $user->setPassword('user');
            $user->generateAuthKey();
            $user->status = $i < 41 ? UserStatus::STATUS_ACTIVE : UserStatus::STATUS_INACTIVE;
            if (!$user->save()) {
                print_r($user->errors); 
            } else {
                $userService->assignRole($user);
                echo "Regular user created: {$user->username}\n";
            }
        }

        echo "Seeding completed.\n";
    }
    private function generateValidUsername($faker)
    {
        $unique = false;
        $username = '';

        while (!$unique) {
            $username = $faker->userName;
            $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);
            
            if (!User::find()->where(['username' => $username])->exists()) {
                $unique = true;
            }
        }

        return $username;
    }
}
