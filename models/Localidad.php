<?php

namespace app\models;

use Yii;
use \app\models\base\Localidad as BaseLocalidad;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "localidad".
 */
class Localidad extends BaseLocalidad
{    
    const RIO_NEGRO = 16;
    public $provinciaid; #solo sirve para hacer un join

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values);
        $this->nombre = mb_strtoupper(mb_substr($this->nombre,0,1,'UTF-8')).mb_strtolower(mb_substr($this->nombre,1,null,'UTF-8'));
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['nombre','validarLocalidad']
            ]
        );
    }

    public function validarLocalidad(){
        if(!isset($this->departamentoid)){
            $this->addError('departamentoid','Falta asignar el departamento');
        }
        if(!isset($this->codigo_postal)){
            $this->addError('codigo_postal','Se requiere el codigo postal');
        }

        #Validacion si existe localidad
        if($this->isNewRecord){
            #Crear
            $localidad_encontrada = Localidad::findOne(['nombre' => $this->nombre, 'departamentoid' => $this->departamentoid]);
            if($localidad_encontrada != null){
                $this->addError('nombre','Ya existe la localidad '.$this->nombre.' con el departamento '.$this->departamento->nombre);
            }
        }else{
            #Modificar
            $localidad_encontrada = Localidad::find()->where(['nombre' => $this->nombre])->andWhere(['departamentoid' => $this->departamentoid])->andWhere(['not', ['id' => $this->id]])->one();
            if($localidad_encontrada != null){
                $this->addError('nombre','Ya existe la localidad '.$this->nombre.' con el departamento '.$this->departamento->nombre);
            }
        }
    }

    public function fields() {
        $resultado = ArrayHelper::merge(parent::fields(), [
            'departamento'=> function($model){
                return $model->departamento->nombre;
            },
            'provincia' => function($model){
                return $model->departamento->provincia->nombre;
            },
        ]);
            
        return $resultado;
    }
}
