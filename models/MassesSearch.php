<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Masses;
use Yii;

/**
 * TypesSearch represents the model behind the search form of `app\models\Types`.
 */
class MassesSearch extends Masses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['id', 'week', 'day', 'start_time', 'end_time', 'type_id', 'languages_id', 'notes', 'status', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Masses::find()
            ->where(['masses.status' => '0', 'masses.church_id' => $params['id']])
            //->leftJoin('types', 'masses.type_id = types.id')
            ->with('types');
        //Yii::error('query: ' . print_r($query, true));

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'week' => $this->week,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'types' => $this->types,
            'languages' => $this->languages,
            'notes' => $this->notes,
            //'church_id' => $this->church_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        //$query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
