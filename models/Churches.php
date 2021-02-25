<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "churches".
 *
 * @property int $id
 * @property string $country 國家
 * @property string $name 教堂名稱
 * @property int $parish_id 教區
 * @property int $deanery_id 鐸區
 * @property int|null $address_id 地址
 * @property string|null $phone 連絡電話
 * @property int|null $priest_id 神父
 * @property int|null $mass_id 彌撒
 * @property string|null $other
 * @property string|null $url 網址
 * @property string|null $mass_info
 * @property string|null $img
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status 修改狀態
 * @property string|null $alias 別名
 */
class Churches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'churches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country', 'name', 'parish_id', 'deanery_id'], 'required'],
            [['parish_id', 'deanery_id', 'address_id', 'priest_id', 'mass_id', 'status'], 'integer'],
            [['other', 'mass_info'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['country'], 'string', 'max' => 20],
            [['name', 'phone'], 'string', 'max' => 100],
            [['url', 'alias'], 'string', 'max' => 255],
            [['img'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'name' => 'Name',
            'parish_id' => 'Parish ID',
            'deanery_id' => 'Deanery ID',
            'address_id' => 'Address ID',
            'phone' => 'Phone',
            'priest_id' => 'Priest ID',
            'mass_id' => 'Mass ID',
            'other' => 'Other',
            'url' => 'Url',
            'mass_info' => 'Mass Info',
            'img' => 'Img',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'alias' => 'Alias',
        ];
    }
}
