<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%activity_log}}`.
 */
class m240714_120249_create_activity_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%activity_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'action' => $this->string()->notNull(),
            'model' => $this->string(255)->notNull(),
            'records' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-activity_log-user_id',
            '{{%activity_log}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-activity_log-user_id', '{{%activity_log}}');

        $this->dropTable('{{%activity_log}}');
    }
}
