<?php

namespace noetikosgroup\smsbat\models;

use Yii;

/**
 * This is the model class for table "smsbat_message".
 *
 * @property integer $id
 * @property string $date_sent
 * @property string $text
 * @property string $phone
 * @property string $status
 * @property string $message_id
 */
class SmsBatMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%smsbat_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_sent'], 'safe'],
            [['text'], 'string'],
            [['status'], 'integer'],
            [['phone', 'message_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'date_sent' => Yii::t('app', 'Date Sent'),
            'text' => Yii::t('app', 'Text'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'message_id' => Yii::t('app', 'Message ID'),
        ];
    }
}
