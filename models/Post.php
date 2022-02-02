<?php

namespace app\models;
use app\components\Helpers;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $text
 * @property string|null $user_id
 * @property string|null $date
 *
 * @property CategoryPost[] $categoryPosts
  */
class Post extends \yii\db\ActiveRecord
{
    public $category_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['text'], 'string', 'max' => 2000],
            [['user_id'], 'string', 'max' => 45],
            [['category_ids', 'category_search'], 'safe'],
            [['title', 'date', 'user_id', 'category_ids', 'text'], 'required']

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'user_id' => 'User name',
            'date' => 'Date',
            'category_ids' => 'Categories',
            'category_search' => 'Categories'
        ];
    }

    /**
     * Gets query for [[CategoryPosts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryPosts(): \yii\db\ActiveQuery
    {
        return $this->hasMany(CategoryPost::className(), ['post_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->via('categoryPosts');
    }

    public function search()
    {
        $query = Post::find();
//        $query->with = "categoryPost";

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
        ]);


        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['user_id' => $this->user_id])
            ->leftJoin('category_post', 'post.id=category_post.post_id')
            ->andFilterWhere(['category_post.category_id'=> $this->category_ids]);

//        Helpers::pd($this->category_ids);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
//        Helpers::pd($dataProvider->getModels());
        return $dataProvider;
    }

    public function loadCategories()
    {
        $this->category_ids = [];
        if (!empty($this->id)) {
            $rows = CategoryPost::find()
                ->select(['category_id'])
                ->where(['post_id' => $this->id])
                ->asArray()
                ->all();
            foreach($rows as $row) {
                $this->category_ids[] = $row['category_id'];
            }
        }
    }
    public function saveCategories()
    {
        CategoryPost::deleteAll(['post_id' => $this->id]);
        if (is_array($this->category_ids)) {
            foreach($this->category_ids as $category_id) {
                $pc = new CategoryPost();
                $pc->post_id = $this->id;
                $pc->category_id = $category_id;
                $pc->save();
            }
        }
   }
}
