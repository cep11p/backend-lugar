<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Localidad;
use yii\helpers\ArrayHelper;

/**
* LocalidadSearch represents the model behind the search form about `app\models\Localidad`.
*/
class LocalidadSearch extends Localidad
{
    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['id', 'regionid', 'departamentoid', 'municipioid','provinciaid'], 'integer'],
            [['nombre'], 'safe'],
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
        $query = Localidad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'regionid' => $this->regionid,
            'departamentoid' => $this->departamentoid,
            'municipioid' => $this->municipioid,
        ]);
        
        $query->andFilterWhere(['like', 'nombre', $this->nombre]);
        
        return $dataProvider;
    }
    
    public function busquedadGeneral($params)
    {
        $query = Localidad::find();

        if(!isset($params['pagesize']) || !is_numeric($params['pagesize']) || $params['pagesize']==0){
            $pagesize = 1000;
            $paginacion = [
                "pagesize"=>$pagesize,
                "page"=>(isset($params['page']) && is_numeric($params['page']))?$params['page']:0
            ];
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
        
        
        if(isset ($params['ids']) && !empty ($params['ids'])){
            $lista_id = explode(",", $params['ids']);
            $query->andWhere(array('in', 'id', $lista_id));
        }else if(isset($this->provinciaid)){
            $query->leftJoin("departamento as d", "departamentoid=d.id");
            
            $query->andFilterWhere([
                'id' => $this->id,
                'regionid' => $this->regionid,
                'departamentoid' => $this->departamentoid,
                'municipioid' => $this->municipioid,
                'd.provinciaid' => $this->provinciaid,
            ]);

            $query->andFilterWhere(['like', 'localidad.nombre', $this->nombre]);
        }else{
            $query->andFilterWhere([
                'id' => $this->id,
                'regionid' => $this->regionid,
                'departamentoid' => $this->departamentoid,
                'municipioid' => $this->municipioid,
            ]);

            $query->andFilterWhere(['like', 'nombre', $this->nombre]);
        }
        
        #predeterminadamente vamos a ordenar el nombre alfabeticamente
        if(!isset($params['sort']) || empty($params['sort'])){
            $query->orderBy(['nombre' => SORT_ASC]);
        }

        // print_r($query->createCommand()->sql);die();
        /******* Se obtiene la coleccion******/
        $coleccion = array();
        foreach ($dataProvider->getModels() as $value) {
            $coleccion[] = $value->toArray();
        }

        #incorporamos las localidades extras
        if($params['extra'] == true){
            $localidad_extras = LocalidadExtraSearch::search([]);
            if($localidad_extras['resultado']>0){
                $coleccion = ArrayHelper::merge($coleccion, $localidad_extras['resultado']);
            }

        }
               
        $resultado['pagesize']=$pagesize;            
        $resultado['pages']=ceil($dataProvider->totalCount/$pagesize);                 
        $resultado['total_filtrado']=$dataProvider->totalCount;
        $resultado['resultado']=$coleccion;

        return $resultado;
    }
}