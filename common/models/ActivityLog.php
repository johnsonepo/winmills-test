<?php 
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * ActivityLog model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $action
 * @property string $model
 * @property string $records
 * @property string $created_at
 */
class ActivityLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%activity_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'action', 'model', 'records'], 'required'],
            [['user_id'], 'integer'],
            [['action', 'model', 'records'], 'string'],
            [['created_at'], 'safe'],
        ];
    }
}
