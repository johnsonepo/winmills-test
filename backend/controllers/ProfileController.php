<?php

namespace backend\controllers;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\LoginForm;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ProfileController extends Controller
{
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $model = new User();
                
        $csrf_token = '';
        if (isset(Yii::$app->request->post()["_csrf-backend"])) {
            $csrf_token = Yii::$app->request->post()["_csrf-backend"];
        } 

        $request = Yii::$app->request;
        $request_token = $request->post(Yii::$app->request->csrfParam); 

        $this->verify_csrf_token($csrf_token, $request_token);

        $new_user = [];
        if (isset(Yii::$app->request->post()["User"])) {
            $new_user = Yii::$app->request->post()["User"];
        } 

        foreach ($new_user as $attribute => $value) {
            if ($model->hasProperty($attribute)) {
                $model->$attribute = $value;
            }
        }
       
        $model->generateAuthKey();

        if (!empty($model->password_hash)) {
            //$model->setPassword($model->password_hash);
        } else {
            unset($model->password_hash); 
        }
        $model->status = $model->status ?? 9;

        if ($model->save()) {

            $this->assignRole($model);

            Yii::$app->session->setFlash('success', 'User created successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to create user.');
        }
        
        return $this->goBack();
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::$app->user->identity;
        $model->password_hash = ''; 

        $this->assignRole($model);

        $this->layout = 'main';
        return $this->render('/site/user/profile', ['model' => $model]);
    }
    public function actionNew()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }
        
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $model = new User();

        $this->layout = 'main';
        return $this->render('/site/user/add-new', [
            'model' => $model,
            'roles' => $roles
        ]);
    }
    //update user profile
    public function actionUpdate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (!Yii::$app->user->can('user')) {
            return $this->goHome();
        }

        $model = User::findOne(Yii::$app->user->identity->id);
        
        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }        

        $csrf_token = '';
        if (isset(Yii::$app->request->post()["_csrf-backend"])) {
            $csrf_token = Yii::$app->request->post()["_csrf-backend"];
        } 

        $request = Yii::$app->request;
        $request_token = $request->post(Yii::$app->request->csrfParam); 

        $this->verify_csrf_token($csrf_token, $request_token);

        $new_user = [];
        if (isset(Yii::$app->request->post()["User"])) {
            $new_user = Yii::$app->request->post()["User"];
        } 
        $new_user = (array)$new_user;

        foreach ($new_user as $attribute => $value) {
            if ($model->hasProperty($attribute)) {
                $model->$attribute = $value;
            }
        }

        if (!empty($model->password_hash)) {
            $model->setPassword($model->password_hash);
        } else {
            unset($model->password_hash); 
        }

        if ($model->save()) {

            $authManager = Yii::$app->authManager;
            $roles = $authManager->getRolesByUser($model->id);

            $this->assignRole($model);

            Yii::$app->session->setFlash('success', 'Profile updated successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update profile.');
        }

        $model->password_hash = '';

        $this->layout = 'main';

        return $this->render('/site/user/profile', ['model' => $model]);
        
    }
    private function verify_csrf_token($csrf_token, $request_token)
    {
        if ($csrf_token !== $request_token) {
            Yii::error('CSRF validation failed!');
            throw new BadRequestHttpException('Invalid CSRF token. Please refresh the page and try again.');
        }
    }

    public function actionUsers()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }

        $data_provider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10, 
            ],
        ]);

        $this->layout = 'main';
        return $this->render('/site/user/users', [
            'dataProvider' => $data_provider,
        ]);
    }
    public function actionEdited($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }

        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }        

        $csrf_token = '';
        if (isset(Yii::$app->request->post()["_csrf-backend"])) {
            $csrf_token = Yii::$app->request->post()["_csrf-backend"];
        } 

        $request = Yii::$app->request;
        $request_token = $request->post(Yii::$app->request->csrfParam); 

        $this->verify_csrf_token($csrf_token, $request_token);

        $new_user = [];
        if (isset(Yii::$app->request->post()["User"])) {
            $new_user = Yii::$app->request->post()["User"];
        } 
        $new_user = (array)$new_user;

        foreach ($new_user as $attribute => $value) {
            if ($model->hasProperty($attribute)) {
                $model->$attribute = $value;
            }
        }

        if (!empty($model->password_hash)) {
            $model->setPassword($model->password_hash);
        } else {
            unset($model->password_hash); 
        }

        if ($model->save()) {

            $this->assignRole($model);

            Yii::$app->session->setFlash('success', 'Profile updated successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update profile.');
        }

        $model->password_hash = '';

        $this->layout = 'main';

        return $this->render('/site/user/edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }

        $model = User::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }

        $model->password_hash = '';
        
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $current_role = array_keys($auth->getRolesByUser($model->id));
        $model->role = $current_role;

        $this->layout = 'main';

        return $this->render('/site/user/edit', [
            'model' => $model,
            'roles' => $roles
        ]);

    }
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
    
        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }
    
        $user = $this->findModel($id);
    
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }
    
        $authManager = Yii::$app->authManager;
        $authManager->revokeAll($user->id);
    
        $user->delete();
    
        return $this->redirect(['user/users']);
    }
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function assignRole($model)
    {
        Yii::$app->db->createCommand()->update('auth_assignment', ['item_name' => $model->type], ['user_id' => $model->id])->execute();
        
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($model->id);

        if (empty($roles)) {
            if ($model->type === 'admin') {
                $role = $authManager->getRole('admin');
            } else {
                $role = $authManager->getRole('user');
            }

            if ($role) {
                $authManager->assign($role, $model->id);
            } else {
                Yii::warning('Role not found for assignment: ' . $model->type);
            }
        }
    }

}
