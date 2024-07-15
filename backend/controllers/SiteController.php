<?php

namespace backend\controllers;

use common\behaviors\CommonModelBehavior;
use common\models\ActivityLog;
use common\models\LoginForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
            CommonModelBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (!Yii::$app->session->get('login_logged')) {
                $this->logActivity('login', get_class($this), 'logged in');
                Yii::$app->session->set('login_logged', true); 
            }
        }

        $data_provider = new ActiveDataProvider([
            'query' => ActivityLog::find()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10, 
            ],
        ]);

        return $this->render('index', [
            'data_provider' => $data_provider,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';
        
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $this->logActivity('logout', get_class($this), 'logged out');
        Yii::$app->user->logout();
        return $this->goHome();
    }
    public function actionError()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']); 
        }
        return $this->redirect(['site/index']);
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
