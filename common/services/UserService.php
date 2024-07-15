<?php

namespace common\services;

use Yii;
use common\models\User;

class UserService
{
    public function assignRole(User $user)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->type);
   
        if ($role) {
            $current_roles = $auth->getRolesByUser($user->id);
            if (array_key_exists($user->type, $current_roles)) {
                return;
            }
            $auth->revokeAll($user->id);
            $auth->assign($role, $user->id);
        }
    }
}
