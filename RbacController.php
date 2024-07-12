<?php

namespace app\baclend;

use Yii;
use yii\console\Controller;
use yii\rbac\DbManager;

class RbacController extends Controller
{
    public function actionInit()
    {
        /** @var DbManager $auth */
        $auth = Yii::$app->authManager;

        $auth->removeAll();

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $user = $auth->createRole('user');
        $auth->add($user);

        $accessProfile = $auth->createPermission('accessProfile');
        $auth->add($accessProfile);

        $updateProfile = $auth->createPermission('updateProfile');
        $auth->add($updateProfile);

        $createProfile = $auth->createPermission('createProfile');
        $auth->add($createProfile);

        $viewProfile = $auth->createPermission('viewProfile');
        $auth->add($viewProfile);

        $deleteProfile = $auth->createPermission('deleteProfile');
        $auth->add($deleteProfile);

        $adminAccess = $auth->createPermission('adminAccess');
        $auth->add($adminAccess);

        $auth->addChild($admin, $accessProfile);
        $auth->addChild($admin, $updateProfile);
        $auth->addChild($admin, $createProfile);
        $auth->addChild($admin, $viewProfile);
        $auth->addChild($admin, $deleteProfile);
        $auth->addChild($admin, $adminAccess);

        $auth->addChild($user, $accessProfile);
        $auth->addChild($user, $updateProfile);

        $auth->assign($admin, 1); 
        $auth->assign($user, 2);  
    }
}
