<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Book;
use app\components\ActiveDataFilter;
use yii\filters\AccessControl;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\UpdateAction;
use yii\rest\ViewAction;

class BookController extends Controller
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
                'modelClass' => Book::class,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => Book::class,
                ],
                'prepareSearchQuery' => static function ($query, $requestParams) {
                    return Book::searchQuery($query, $requestParams);
                },
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => Book::class,
                'scenario' => 'create',
                'checkAccess' => [$this, 'checkAccess']
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => Book::class,
                'scenario' => 'update',
                'checkAccess' => [$this, 'checkAccess']
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Book::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Book::class,
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
