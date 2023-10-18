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
 * This is the model class for table "journal".
 *
 * @property int $id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $reader_id [int(11)]
 * @property int $book_id [int(11)]
 * @property string $expected_return_date [date]
 * @property string $return_date [date]
 * @property-read ActiveQuery $book
 * @property-read ActiveQuery $reader
 * @property string $issue_date [date]
 */
class Journal extends ActiveRecord
{

    public $reader_name;
    public $book_name;

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
        return 'journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'book_id', 'reader_id'], 'integer', 'on' => ['default', 'index', 'update', 'create']],
            [['reader_name', 'book_name'], 'string', 'on' => ['index', 'default']],
            [['reader_id'], 'required', 'on' => ['create', 'update']],
            [['expected_return_date'], 'required', 'on' => ['create', 'update']],
            ['book_id', 'exist', 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id'], 'on' => ['index', 'update', 'create']],
            ['reader_id', 'exist', 'targetClass' => Reader::class, 'targetAttribute' => ['reader_id' => 'id'], 'on' => ['index', 'update', 'create']],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s', 'on' => ['index', 'update', 'create']],
            [['return_date', 'issue_date', 'expected_return_date'], 'datetime', 'format' => 'php:Y-m-d', 'on' => ['index', 'update', 'create']],
        ];
    }

    /**
     * @return array
     */
    public function extraFields(): array
    {
        $fields = parent::extraFields();
        $fields[] = 'book';
        $fields[] = 'reader';
        return $fields;
    }

    /**
     * @return ActiveQuery
     */
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getReader(): ActiveQuery
    {
        return $this->hasOne(Reader::class, ['id' => 'reader_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'reader_id' => 'Читатель',
            'book_id' => 'Книга',
            'book_name' => 'Название книги',
            'reader_name' => 'Имя читателя',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'return_date' => 'Дата возврата',
            'issue_date' => 'Дата выдачи',
            'expected_return_date' => 'Дата предполагаемого возврата',
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
                'created_at' => 't.created_at',
                'updated_at' => 't.updated_at',
                'return_date' => 't.return_date',
                'issue_date' => 't.issue_date',
                'expected_return_date' => 't.expected_return_date',
                'reader_id' => 't.reader_id',
                'book_id' => 't.book_id',
                'book_name' => 'b.name',
                'reader_name' => 'r.name',
            ],
        ]);

        $df->load($requestParams);
        $query->alias('t');
        $query->joinWith(['book b', 'reader r']);
        $query->where($df->build() ?: []);
        $query->groupBy('t.id');
        //echo $query->createCommand()->getRawSql();exit;
        return $query;
    }

}
