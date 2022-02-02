<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $title
 *
 * @property CategoryPost[] $categoryPosts
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 100],
            [['title'], 'required']
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
        ];
    }

    /**
     * Gets query for [[CategoryPosts]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getCategoryPosts()
    {
        return $this->hasMany(CategoryPost::className(), ['category_id' => 'id']);
    }

    public function getPosts()
    {
        
        return $this->hasMany(Post::className(), ['id' => 'post_id'])
            ->via('categoryPosts');
    }
    public function search()
    {
        $query = Category::find();

        // add conditions that should always apply here

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],

        ]);
        return $dataProvider;
    }

    public static function listCategory(){
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }
}
