<?php

namespace YiiConfigureManage\form\models;


use yii\db\StaleObjectException;
use YiiConfigureManage\components\BusinessModel;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "{{%form_category}}".
 *
 * @property string $key 表单标志
 * @property string $name 表单名称
 * @property string $description 表单描述
 * @property int $sort_order 排序
 * @property int $is_setting 配置类型[0:搜集表单，1:配置项]
 * @property int $is_open 是否开放，否时非超级管理员不可操作（不可见）
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read int $optionCount 拥有的子项数量
 * @property-read FormOption[] $options 拥有的子项目
 * @property-read FormSetting $setting 配置表单
 */
class FormCategory extends BusinessModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%form_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['sort_order', 'is_setting', 'is_open'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key'         => '表单标志',
            'name'        => '表单名称',
            'description' => '表单描述',
            'sort_order'  => '排序',
            'is_setting'  => '配置类型[0:搜集表单，1:配置项]',
            'is_open'     => '是否开放，否时非超级管理员不可操作（不可见）',
            'created_at'  => '创建时间',
            'updated_at'  => '更新时间',
        ];
    }

    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getOptionCount()
    {
        return $this->hasOne(FormOption::class, ['key' => 'key'])->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(FormOption::class, ['key' => 'key'])->orderBy("sort_order ASC, id ASC");
    }

    /**
     * 获取配置表单
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(FormSetting::class, ['key' => 'key']);
    }

    /**
     * 检查是否可以删除
     *
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete()
    {
        if ($this->optionCount > 0) {
            throw new BusinessException("该类型尚有子项目，不能删除");
        }
        if ($this->setting) {
            // 删除配置表单值
            $this->setting->delete();
        }
        return parent::beforeDelete();
    }
}
