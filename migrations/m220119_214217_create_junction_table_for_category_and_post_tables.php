<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_post}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%category}}`
 * - `{{%post}}`
 */
class m220119_214217_create_junction_table_for_category_and_post_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category_post', [
            'id' => $this->primaryKey(),
            'category_id'=>$this->smallInteger(8),
            'post_id'=>$this->smallInteger(8)
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            'idx-category_post-category_id',
            'category_post',
            'category_id'
        );

        // add foreign key for table `{{%category}}`
        $this->addForeignKey(
            'chain_to_category',
            'category_post',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `post_id`
        $this->createIndex(
            'idx-category_post-post_id',
            'category_post',
            'post_id'
        );

        // add foreign key for table `{{%post}}`
        $this->addForeignKey(
            'chain_to_post',
            'category_post',
            'post_id',
            'post',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            'chain_to_category',
            'category_post',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-category_post-category_id',
            'category_post'
        );

        // drops foreign key for table `{{%post}}`
        $this->dropForeignKey(
            'chain_to_post',
            'category_post',
            'post_id',
            'post',
            'id',
            'CASCADE'
        );

        // drops index for column `post_id`
        $this->dropIndex(
            'idx-category_post-post_id',
            'category_post'
        );

        $this->dropTable('category_post');
    }
}
