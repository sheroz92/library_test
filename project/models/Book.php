<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataFilter;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $created_at
 * @property-read ActiveQuery $journals
 * @property string|null $updated_at
 * @property string $author [varchar(255)]
 * @property string $alias [varchar(100)]
 */
class Book extends ActiveRecord
{

    public $return_date;
    public $issue_date;

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return gmdate("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'name', 'alias', 'author', 'created_at', 'updated_at', 'return_date', 'issue_date'], 'safe'],
            [['id'], 'integer'],
            [['name', 'author'], 'string', 'max' => 255, 'on' => ['default', 'index', 'create', 'update']],
            [['alias'], 'string', 'max' => 100, 'on' => ['default', 'index', 'create', 'update']],
            [['alias'], 'unique', 'on' => ['create', 'update']],
            [['name'], 'required', 'on' => ['create', 'update']],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s', 'on' => ['index']],
            [['return_date', 'issue_date'], 'datetime', 'format' => 'php:Y-m-d', 'on' => ['index']],
        ];
    }

    /**
     * @return array
     */
    public function extraFields(): array
    {
        $fields = parent::extraFields();
        $fields[] = 'journals';
        return $fields;
    }

    /**
     * @return ActiveQuery
     */
    public function getJournals(): ActiveQuery
    {
        return $this->hasMany(Journal::class, ['book_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'author' => 'Автор',
            'alias' => 'Псевдоним',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'return_date' => 'Дата возврата',
            'issue_date' => 'Дата выдачи',
        ];
    }

    /**
     * @param ActiveQuery $query
     * @param array $requestParams
     * @return ActiveQuery
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function searchQuery(ActiveQuery $query, array $requestParams): ActiveQuery
    {
        $df = Yii::createObject([
            'class' => ActiveDataFilter::class,
            'searchModel' => self::class,
            'attributeMap' => [
                'id' => 't.id',
                'name' => 't.name',
                'alias' => 't.alias',
                'author' => 't.author',
                'created_at' => 't.created_at',
                'updated_at' => 't.updated_at',
                'return_date' => 'j.return_date',
                'issue_date' => 'j.issue_date',
            ],
        ]);

        $df->load($requestParams);
        $query->alias('t');
        $query->joinWith(['journals j']);
        $query->where($df->build() ?: []);
        $query->groupBy('t.id');
        //echo $query->createCommand()->getRawSql();exit;
        return $query;
    }

}
