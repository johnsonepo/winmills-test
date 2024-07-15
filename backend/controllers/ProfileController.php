<?php

namespace backend\controllers;

use common\constants\UserStatus;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\services\UserService;
use stdClass;

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

        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $statuses = $model->getStatusLabels();

        // $this->verify_csrf_token(Yii::$app->request);  manually verify again if need be

        $new_user = $this->getUserData(Yii::$app->request->post());

        $this->validateInput($model, $new_user);
        $model->generateAuthKey();
        $model->status = $model->status ?? UserStatus::STATUS_INACTIVE;

        if ($model->save()) {
            $this->asignRole($model);
            $model->logActivity('created', get_class($model), json_encode($model->attributes));
            Yii::$app->session->setFlash('success', 'User created successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to create user.');
        }
        
        return $this->render('/site/user/add-new', [
            'model' => $model,
            'roles' => $roles,
            'statuses' => $statuses
        ]);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::$app->user->identity;
        $model->password_hash = ''; 
        $model->scenario = User::SCENARIO_UPDATE;

        
        $this->layout = 'main';
        return $this->render('/site/user/profile', [
            'model' => $model,
        ]);
    }
    public function actionNew()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!Yii::$app->user->can('admin')) {
            return $this->goHome();
        }
        
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        $statuses = $model->getStatusLabels();

        $this->layout = 'main';
        $model->password_hash = '';

        return $this->render('/site/user/add-new', [
            'model' => $model,
            'roles' => $roles,
            'statuses' => $statuses
        ]);
    }
    //update user profile
    public function actionUpdate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = User::findOne(Yii::$app->user->identity->id);
        $model->scenario = User::SCENARIO_UPDATE;
        
        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }        

        $new_user = $this->getUserData(Yii::$app->request->post());
        $new_attributes =  $this->validateInput($model, $new_user);

        if ($model->save()) {
            $this->asignRole($model);
            $model->logActivity('updated', get_class($model), json_encode($new_attributes));
            Yii::$app->session->setFlash('success', 'Profile updated successfully.');
            return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update profile.');
        }

        $model->password_hash = '';

        $this->layout = 'main';

        return $this->render('/site/user/profile', ['model' => $model]);
        
    }
    private function verify_csrf_token($request)
    {
        if (!$request->validateCsrfToken($request->post(Yii::$app->request->csrfParam))) {
            throw new BadRequestHttpException('Invalid CSRF token.');
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

    $deletedStatus = UserStatus::STATUS_DELETED;

    $dataProvider = new ActiveDataProvider([
        'query' => User::find()->where(['not', ['status' => $deletedStatus]]),
        'pagination' => [
            'pageSize' => 10,
        ],
    ]);

    $this->layout = 'main';

    return $this->render('/site/user/users', [
        'dataProvider' => $dataProvider,
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
        $model->scenario = User::SCENARIO_UPDATE;

        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }        

        $new_user = $this->getUserData(Yii::$app->request->post());

        $new_attributes = $this->validateInput($model, $new_user);

        if ($model->save()) {
            $this->asignRole($model);
            $model->logActivity('updated', get_class($model), json_encode($new_attributes));
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

        $current_user_role = array_keys($auth->getRolesByUser($model->id));
        $model->role = $current_user_role;

        $statuses = $model->getStatusLabels();
        //dd($statuses);
    
        $this->layout = 'main';

        return $this->render('/site/user/edit', [
            'model' => $model,
            'roles' => $roles,
            'statuses' => $statuses
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

        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException('User not found.');
        }

        $model->status = UserStatus::STATUS_DELETED;
        
        if ($model->save(false)) { 
            $model->logActivity('deleted', get_class($model), json_encode($id));
            Yii::$app->session->setFlash('success', 'User profile deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete user profile.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['view', 'id' => $model->id]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function validateInput($model, $post_data)
    {
        $post_data = array_map('trim', $post_data); 
        $post_data = array_map('htmlspecialchars', $post_data); 
        $new = new \stdClass();

        foreach ($post_data as $attribute => $value) {
            if ($value === '' || $value === null) {
                unset($post_data[$attribute]);
                continue; 
            }
            
            if ($model->hasProperty($attribute) ) {
                if ($attribute === 'password_hash') {
                    $model->setPassword($value);
                }else{
                    if($model->$attribute != $value) {
                        $model->$attribute = $value;
                        $new->$attribute = $value;
                    }
                }
                
            }
        }

        return $new;
    }
    private function getUserData($post)
    {
        if (isset($post["User"])) {
            return $post["User"];
        }
        return [];
    }
    private function asignRole($model)
    {
        $user_service = new UserService();
        $user_service->assignRole($model);
    }

}
