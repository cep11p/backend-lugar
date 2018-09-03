<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "lugar".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $calle
 * @property string $altura
 * @property integer $localidadid
 * @property string $latitud
 * @property string $longitud
 * @property string $barrio
 * @property string $piso
 * @property string $depto
 *
 * @property \app\models\Localidad $localidad
 * @property string $aliasModel
 */
abstract class Lugar extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lugar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['localidadid'], 'required'],
            [['localidadid'], 'integer'],
            [['nombre', 'calle', 'altura', 'latitud', 'longitud', 'barrio', 'piso', 'depto'], 'string', 'max' => 200],
            [['localidadid'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Localidad::className(), 'targetAttribute' => ['localidadid' => 'id']]
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
            'calle' => 'Calle',
            'altura' => 'Altura',
            'localidadid' => 'Localidadid',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'barrio' => 'Barrio',
            'piso' => 'Piso',
            'depto' => 'Depto',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(\app\models\Localidad::className(), ['id' => 'localidadid']);
    }




}