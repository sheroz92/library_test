<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Journal;
use app\components\ActiveDataFilter;
use Yii;
use yii\filters\AccessControl;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\OptionsAction;
use yii\rest\ViewAction;
use yii\web\NotFoundHttpException;

class JournalController extends Controller
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
                        'actions' => ['index', 'register', 'view', 'return-book'],
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
                'modelClass' => Journal::class,
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => Journal::class,
                ],
                'prepareSearchQuery' => static function ($query, $requestParams) {
                    return Journal::searchQuery($query, $requestParams);
                },
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'register' => [
                'class' => CreateAction::class,
                'modelClass' => Journal::class,
                'scenario' => 'create',
                'checkAccess' => [$this, 'checkAccess']
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Journal::class,
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

    /**
     * @param $id
     * @return Journal
     * @throws NotFoundHttpException
     */
    public function actionReturnBook($id): Journal
    {
        $model = Journal::findOne($id);
        if ($model) {
            $model->issue_date = date('Y-m-d');
            $model->save();
            return $model;
        } else {
            throw new NotFoundHttpException('Object not found: ' . $id);
        }
    }
}
