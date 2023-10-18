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
 * This is the model class for table "reader".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $created_at
 * @property-read ActiveQuery $journals
 * @property string|null $updated_at
 */
class Reader extends ActiveRecord
{

    public $return_date;

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
        return 'reader';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'name', 'created_at', 'updated_at', 'return_date'], 'safe'],
            [['id'], 'integer'],
            [['name'], 'string', 'max' => 255, 'on' => ['default', 'index', 'create', 'update']],
            [['name'], 'required', 'on' => ['create', 'update']],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s', 'on' => ['index']],
            [['return_date'], 'datetime', 'format' => 'php:Y-m-d', 'on' => ['index']],
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
        return $this->hasMany(Journal::class, ['reader_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'return_date' => 'Дата возврата',
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
                'created_at' => 't.created_at',
                'updated_at' => 't.updated_at',
                'return_date' => 'j.return_date'
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
