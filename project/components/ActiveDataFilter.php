<?php

namespace app\components;

use yii\base\Model;
use yii\data\ActiveDataFilter as BaseActiveDataFilter;
/**
 * Класс дополнен что бы указать сценарий для searchModel
 * */
class ActiveDataFilter extends BaseActiveDataFilter
{
    public string $searchModelScenario = 'index';

    public function getSearchModel(): callable|Model|array|string
    {
        $model = parent::getSearchModel();
        if (!is_null($this->searchModelScenario)) {
            $model->setScenario($this->searchModelScenario);
        }
        return $model;
    }
}
