<?php 

namespace common\behaviors;

use common\constants\UserStatus;
use common\models\ActivityLog;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class CommonModelBehavior extends Behavior implements UserStatus
{
    protected function getIsDeleted()
    {
        return isset($this->owner->status) && $this->owner->status === UserStatus::STATUS_DELETED;
    }

    public function logActivity($action, $model, $records)
    {
        $log = new ActivityLog();
        $log->user_id = Yii::$app->user->id; 
        $log->created_at = date('Y-m-d H:i:s');
        $log->records = $records;
        $log->model =  $model;
        $log->action = $action; 

        if (!$log->save()) {
            dd($log->errors);
        } 
        
    }

    public function getStatusLabels()
    {
        return [
            UserStatus::STATUS_DELETED => 'Deleted',
            UserStatus::STATUS_INACTIVE => 'Inactive',
            UserStatus::STATUS_ACTIVE => 'Active',
        ];
    }

}
