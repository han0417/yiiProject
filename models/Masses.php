<?php

namespace app\models;

use Yii;
use app\models\Types;

/**
 * This is the model class for table "masses".
 *
 * @property int $id
 * @property string $week 週頻率
 * @property int $day 星期幾
 * @property string $start_time 起始時間
 * @property string $end_time 結束時間
 * @property int $type_id 類別
 * @property int $languages_id 語系
 * @property int $church_id 所屬教堂
 * @property string|null $notes 備註
 * @property int $status 是否被刪除
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Masses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'masses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['week', 'day', 'start_time', 'end_time', 'type_id', 'languages_id', 'church_id'], 'required'],
            [['day', 'type_id', 'languages_id', 'church_id', 'status'], 'integer'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['week'], 'string', 'max' => 50],
            [['notes'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'week' => '頻率',
            'day' => '星期',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'type_id' => 'Type ID',
            'languages_id' => 'Languages ID',
            'church_id' => 'Church ID',
            'notes' => '備註',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'types.name' => '彌撒種類',
            'languages.name' => '語言'
        ];
    }

    public function getTypes()
    {
        return $this->hasOne(Types::className(), ['id' => 'type_id']);
    }

    public function getLanguages()
    {
        return $this->hasOne(Languages::className(), ['id' => 'languages_id']);
    }
}
