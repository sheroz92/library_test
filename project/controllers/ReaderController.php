<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Reader;
use app\components\ActiveDataFilter;
use yii\filters\AccessControl;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\UpdateAction;
use yii\rest\ViewAction;

class ReaderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        //добавить экшен если надо что не проверялся на права
        //$behaviors['authenticator']['except'] = ['action'];
        $behaviors = array_merge($behaviors, [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ]);

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'options' => [
                'class' => OptionsAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Reader::class,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => Reader::class,
                ],
                'prepareSearchQuery' => static function ($query, $requestParams) {
                    return Reader::searchQuery($query, $requestParams);
                },
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => Reader::class,
                'scenario' => 'create',
                'checkAccess' => [$this, 'checkAccess']
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => Reader::class,
                'scenario' => 'update',
                'checkAccess' => [$this, 'checkAccess']
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Reader::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Reader::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
        ];
    }

    /**
     * @param $action
     * @param $model
     * @param array $params
     * @return bool
     */
    public function checkAccess($action, $model = null, array $params = []): bool
    {
        // TODO implement checkAccess
        return true;
    }

}
