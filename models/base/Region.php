<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "region".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $delegacionid
 *
 * @property \app\models\Localidad[] $localidads
 * @property string $aliasModel
 */
abstract class Region extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delegacionid'], 'integer'],
            [['nombre'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'delegacionid' => 'Delegacionid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidads()
    {
        return $this->hasMany(\app\models\Localidad::className(), ['regionid' => 'id']);
    }




}