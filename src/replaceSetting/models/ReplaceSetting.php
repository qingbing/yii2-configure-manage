<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\replaceSetting\models;


use YiiConfigureManage\components\BusinessModel;
use YiiHelper\helpers\Req;
use Zf\Helper\Format;

/**
 * This is the model class for table "{{%replace_setting}}".
 *
 * @property string $code 替换配置标识符
 * @property string $name 替换配置名称
 * @property string $description 内容描述
 * @property string|null $template 默认模板
 * @property string|null $content 模板
 * @property int $sort_order 排序
 * @property int $is_open 是否开放，否时非超级管理员不可操作（不可见）
 * @property string|null $replace_fields 替换字段集(JSON键值对),字段可以从模板中提取
 */
class ReplaceSetting extends BusinessModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%replace_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['template', 'content'], 'string'],
            [['sort_order', 'is_open'], 'integer'],
            [['replace_fields'], 'safe'],
            [['code', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code'           => '标识码',
            'name'           => '配置名称',
            'description'    => '描述',
            'template'       => '母版',
            'content'        => '模版',
            'sort_order'     => '排序',
            'is_open'        => '是否开放',
            'replace_fields' => '替换字段',
        ];
    }

    /**
     * 保存替换模版
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (is_string($this->replace_fields) && is_json($this->replace_fields)) {
            $this->replace_fields = json_decode($this->replace_fields, true);
        } else {
            $this->replace_fields = [];
        }
        // 确保replace的替换字段都携带有模版符号"{{}}"
        $replaceFields = [];
        foreach ($this->replace_fields as $field => $label) {
            if (0 === strpos($field, '{{')) {
                $replaceFields[$field] = $label;
            } else {
                $replaceFields["{{{$field}}}"] = $label;
            }
        }
        $this->replace_fields = $replaceFields;
        return parent::beforeSave($insert);
    }

    /**
     * 模型查询后执行
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->replace_fields = array_merge(self::getReplaceLabels(), $this->replace_fields);
        if (empty($this->content)) {
            $this->content = $this->template;
        }
    }

    const FIELD_NOW_TIME  = '{{now_time}}';
    const FIELD_NOW_DATE  = '{{now_date}}';
    const FIELD_CLIENT_IP = '{{client_ip}}';
    const FIELD_DOMAIN    = '{{domain}}';
    const FIELD_UID       = '{{login_uid}}';

    /**
     * 通用替换字段值
     *
     * @return array
     */
    public static function getReplaces(): array
    {
        return [
            self::FIELD_NOW_TIME  => Format::datetime(),
            self::FIELD_NOW_DATE  => Format::date(),
            self::FIELD_CLIENT_IP => Req::getUserIp(),
            self::FIELD_DOMAIN    => Req::getUserHostInfo(),
            self::FIELD_UID       => Req::getUid(),
        ];
    }

    /**
     * 通过替换字段标签
     *
     * @return array
     */
    public static function getReplaceLabels(): array
    {
        return [
            self::FIELD_NOW_TIME  => '当前时间',
            self::FIELD_NOW_DATE  => '当前日期',
            self::FIELD_CLIENT_IP => '客户IP',
            self::FIELD_DOMAIN    => '域名',
            self::FIELD_UID       => '登录用户UID',
        ];
    }
}