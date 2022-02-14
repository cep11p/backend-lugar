<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LocalidadExtra;

/**
* LocalidadExtraSearch represents the model behind the search form about `app\models\LocalidadExtra`.
*/
class LocalidadExtraSearch extends LocalidadExtra
{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['localidadid'], 'integer'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
    * Creates data provider instance with search query applied
    *
    * @param array $params
    *
    * @return ActiveDataProvider
    */
    public function search($params)
    {
        $query = LocalidadExtra::find();

        if(!isset($params['pagesize']) || !is_numeric($params['pagesize']) || $params['pagesize']==0){
            $paginacion =false;
        }else{
            $pagesize = intval($params['pagesize']);
            $paginacion = [
                "pagesize"=>$pagesize,
                "page"=>(isset($params['page']) && is_numeric($params['page']))?$params['page']:0
            ];
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $paginacion
        ]);

        $this->load($params,'');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin('localidad as l', 'localidadid=l.id');

        if(isset($params['id'])){
            $query->andFilterWhere([
                'localidadid' => $params['id'],
            ]);
        }

        #Localidad
        if(isset($params['nombre'])){
            $query->andFilterWhere(['like', 'l.nombre', $params['nombre']]);
        }
        #Codigo Postal
        if(isset($params['codigo_postal'])){
            $query->andFilterWhere(['like', 'l.codigo_postal', $params['codigo_postal']]);
        }
        #Departamento
        if(isset($params['departamentoid'])){
            $query->andFilterWhere(['l.departamentoid' => $params['departamentoid']]);
        }

        
        /******* Se obtiene la coleccion******/
        $localidadSeach = new LocalidadSearch();
        $localidad_ids = '';
        foreach ($dataProvider->getModels() as $value) {
            $localidad_ids .= (empty($localidad_ids))?$value->localidadid:','.$value->localidadid;
        }
        $localidad_ids = (empty($localidad_ids))?99999999:$localidad_ids;
        $resultado = $localidadSeach->busquedadGeneral(['ids'=>$localidad_ids]);

        return $resultado;

    }
}