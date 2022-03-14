<?php

namespace YiiConfigureManage\tableHeader\models;


use YiiConfigureManage\components\BusinessModel;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "{{%header_category}}".
 *
 * @property string $key 表头标记
 * @property string $name 表头标志别名
 * @property string $description 表头描述
 * @property int $sort_order 排序
 * @property int $is_open 是否开放表头，否时非超级管理员不可操作（不可见）
 *
 * @property-read int $optionCount 拥有的子项数量
 * @property-read HeaderOption[] $options 拥有的子项目
 */
class HeaderCategory extends BusinessModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%header_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['sort_order', 'is_open'], 'integer'],
            [['key', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key'         => '表头标志',
            'name'        => '表头别名',
            'description' => '描述',
            'sort_order'  => '排序',
            'is_open'     => '公开表头',
        ];
    }

    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getOptionCount()
    {
        return $this->hasOne(
            HeaderOption::class,
            ['key' => 'key']
        )->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(
            HeaderOption::class,
            ['key' => 'key']
        )->orderBy("sort_order ASC, id ASC");
    }

    /**
     * 检查是否可以删除
     *
     * @return bool
     * @throws BusinessException
     */
    public function beforeDelete()
    {
        if ($this->optionCount > 0) {
            throw new BusinessException("该类型尚有子项目，不能删除");
        }
        return parent::beforeDelete();
    }
}
