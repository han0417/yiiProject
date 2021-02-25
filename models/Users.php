<?php
namespace app\models;
use yii\db\ActiveRecord;
use Yii;
class Users extends ActiveRecord {
    /**
     * @return string 返回該AR類關聯的資料表名
     */
    public static function tableName() {
        return 'user';
    }
}