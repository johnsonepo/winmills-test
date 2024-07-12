<?php

use yii\db\Migration;
use yii\rbac\DbManager;
use app\models\User;

/**
 * Class m240710_151836_init_rbac
 */
class m240710_151836_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         /** @var DbManager $auth */
         $auth = Yii::$app->authManager;

         // Clear all existing RBAC data
         $auth->removeAll();
 
         $adminRole = $auth->createRole('admin');
         $auth->add($adminRole);
 
         $userRole = $auth->createRole('user');
         $auth->add($userRole);
 
         $adminPermission = $auth->createPermission('adminPermission');
         $auth->add($adminPermission);
 
         $userPermission = $auth->createPermission('userPermission');
         $auth->add($userPermission);
 
       
         $auth->addChild($adminRole, $adminPermission);
         $auth->addChild($userRole, $userPermission);

         $auth->assign($adminRole, 1); 

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240710_151836_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
