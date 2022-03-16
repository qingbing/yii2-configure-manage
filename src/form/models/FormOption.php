<?php

namespace YiiConfigureManage\form\models;

use YiiConfigureManage\components\BusinessModel;
use YiiHelper\helpers\AppHelper;

/**
 * This is the model class for table "{{%form_option}}".
 *
 * @property int $id 自增ID
 * @property string $key 表单分类
 * @property string $field 字段
 * @property string $label 字段名
 * @property string $input_type 表单类型
 * @property string $default 默认值
 * @property string $description 分类配置描述
 * @property int $sort_order 当前分类排序
 * @property int $is_enable 表单项目启用状态
 * @property string|null $exts 扩展信息
 * @property string|null $rules 验证规则
 * @property int $is_required 是否必填
 * @property string $required_msg 必填时信息为空的提示
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read FormCategory $category
 */
class FormOption extends BusinessModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%form_option}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'field', 'label'], 'required'],
            [['input_type'], 'string'],
            [['sort_order', 'is_enable', 'is_required'], 'integer'],
            [['exts', 'rules', 'created_at', 'updated_at'], 'safe'],
            [['key', 'field', 'label', 'default'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['required_msg'], 'string', 'max' => 200],
            [['field', 'key'], 'unique', 'targetAttribute' => ['field', 'key']],
            [['label', 'key'], 'unique', 'targetAttribute' => ['label', 'key']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'key'          => '表单分类',
            'field'        => '字段',
            'label'        => '字段名',
            'input_type'   => '表单类型',
            'default'      => '默认值',
            'description'  => '分类配置描述',
            'sort_order'   => '当前分类排序',
            'is_enable'    => '表单项目启用状态',
            'exts'         => '扩展信息',
            'rules'        => '验证规则',
            'is_required'  => '是否必填',
            'required_msg' => '必填时信息为空的提示',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }

    /**
     * 获取归属的菜单类型
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(FormCategory::class, ['key' => 'key']);
    }

    /**
     * 保存成功后执行事件
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 表单项输入类型
     */
    const INPUT_TYPE_VIEW_TEXT      = 'view-text';
    const INPUT_TYPE_INPUT_TEXT     = 'input-text';
    const INPUT_TYPE_INPUT_PASSWORD = 'input-password';
    const INPUT_TYPE_INPUT_AREA     = 'input-area';
    const INPUT_TYPE_INPUT_NUMBER   = 'input-number';
    const INPUT_TYPE_INPUT_RADIO    = 'input-radio';
    const INPUT_TYPE_INPUT_CHECKBOX = 'input-checkbox';
    const INPUT_TYPE_INPUT_SELECT   = 'input-select';
    const INPUT_TYPE_ELE_SWITCH     = 'ele-switch';
    const INPUT_TYPE_ELE_CASCADER   = 'ele-cascader';
    const INPUT_TYPE_ELE_SLIDER     = 'ele-slider';
    const INPUT_TYPE_ELE_RATE       = 'ele-rate';
    const INPUT_TYPE_ELE_COLOR      = 'ele-color';
    const INPUT_TYPE_ELE_UPLOADER   = 'ele-uploader';
    const INPUT_TYPE_TIME_PICKER    = 'time-picker';
    const INPUT_TYPE_DATE_PICKER    = 'date-picker';
    const INPUT_TYPE_AUTO_COMPLETE  = 'auto-complete';
    const INPUT_TYPE_JSON_EDITOR    = 'json-editor';
    const INPUT_TYPE_VUE_EDITOR     = 'vue-editor';

    /**
     * 返回所有支持的表单输入类型
     *
     * @return array
     */
    public static function inputTypes()
    {
        return [
            self::INPUT_TYPE_VIEW_TEXT      => '显示', // view-text
            self::INPUT_TYPE_INPUT_TEXT     => '文本框', // input-text
            self::INPUT_TYPE_INPUT_PASSWORD => '密码框', // input-password
            self::INPUT_TYPE_INPUT_AREA     => '文本域', // input-area
            self::INPUT_TYPE_INPUT_NUMBER   => '文本数字', // input-number
            self::INPUT_TYPE_INPUT_RADIO    => '单选组', // input-radio
            self::INPUT_TYPE_INPUT_CHECKBOX => '复选组', // input-checkbox
            self::INPUT_TYPE_INPUT_SELECT   => '下拉框', // input-select
            self::INPUT_TYPE_ELE_SWITCH     => '开关按钮', // ele-switch
            self::INPUT_TYPE_ELE_CASCADER   => '级联', // ele-cascader
            self::INPUT_TYPE_ELE_SLIDER     => '滑块', // ele-slider
            self::INPUT_TYPE_ELE_RATE       => '评分', // ele-rate
            self::INPUT_TYPE_ELE_COLOR      => '颜色', // ele-color
            self::INPUT_TYPE_ELE_UPLOADER   => '上传', // ele-uploader
            self::INPUT_TYPE_TIME_PICKER    => '时间组件', // time-picker
            self::INPUT_TYPE_DATE_PICKER    => '日期组件', // date-picker
            self::INPUT_TYPE_AUTO_COMPLETE  => '建议输入', // auto-complete
            self::INPUT_TYPE_JSON_EDITOR    => 'JSON编辑', // json-editor
            self::INPUT_TYPE_VUE_EDITOR     => '富文本编辑', // vue-editor
        ];
    }

    /**
     * 获取激活状态下的选项
     *
     * @param string $key
     * @return array|$this[]
     */
    public static function getEnableOptions(string $key)
    {
        return self::find()
            ->andWhere(['=', 'key', $key])
            ->andWhere(['=', 'is_enable', 1])
            ->orderBy('sort_order ASC, id ASC')
            ->all();
    }
}
