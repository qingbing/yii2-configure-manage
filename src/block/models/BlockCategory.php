<?php

namespace YiiConfigureManage\block\models;

use yii\db\ActiveQuery;
use YiiConfigureManage\components\BusinessModel;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "{{%block_category}}".
 *
 * @property string $key 引用标识
 * @property string $type 页面区块类型[content, image, image-link, cloud-words, cloud-words-links, list, list-links, images, image-links]
 * @property string $name 区块名称
 * @property string $description 区块描述
 * @property int $sort_order 排序
 * @property int $is_open 是否开放，否时管理员不可操作（不可见）
 * @property int $is_enable 启用状态
 * @property string $src type为image-link时，为图片地址
 * @property string|null $content type为content时存放内容，为image-link时存放图片链接
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read int $optionCount 拥有的子项数量
 * @property-read BlockOption[] $options 拥有的子项目
 */
class BlockCategory extends BusinessModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%block_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['sort_order', 'is_open', 'is_enable'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
            [['src'], 'string', 'max' => 200],
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
            'key'         => '引用标识',
            'type'        => '区块类型',
            'name'        => '区块名称',
            'description' => '区块描述',
            'sort_order'  => '排序',
            'is_open'     => '公开状态',
            'is_enable'   => '启用状态',
            'src'         => '图片地址',
            'content'     => '内容',
            'created_at'  => '创建时间',
            'updated_at'  => '更新时间',
        ];
    }

    const TYPE_CONTENT           = 'content';
    const TYPE_IMAGE             = 'image';
    const TYPE_IMAGE_LINK        = 'image-link';
    const TYPE_CLOUD_WORDS       = 'cloud-words';
    const TYPE_CLOUD_WORDS_LINKS = 'cloud-words-links';
    const TYPE_LIST              = 'list';
    const TYPE_LIST_LINKS        = 'list-links';
    const TYPE_IMAGES            = 'images';
    const TYPE_IMAGES_LINKS      = 'images-links';

    /**
     * 区块类型
     *
     * @return array
     */
    public static function types()
    {
        return [
            self::TYPE_CONTENT           => '内容',
            self::TYPE_IMAGE             => '图片',
            self::TYPE_IMAGE_LINK        => '链接图片',
            self::TYPE_CLOUD_WORDS       => '云词',
            self::TYPE_CLOUD_WORDS_LINKS => '链接云词',
            self::TYPE_LIST              => '列表',
            self::TYPE_LIST_LINKS        => '链接列表',
            self::TYPE_IMAGES            => '图片集',
            self::TYPE_IMAGES_LINKS      => '链接图片集',
        ];
    }

    /**
     * 支持选项的类型
     */
    const LIST_TYPES = [
        self::TYPE_CLOUD_WORDS       => '云词',
        self::TYPE_CLOUD_WORDS_LINKS => '链接云词',
        self::TYPE_LIST              => '列表',
        self::TYPE_LIST_LINKS        => '链接列表',
        self::TYPE_IMAGES            => '图片集',
        self::TYPE_IMAGES_LINKS      => '链接图片集',
    ];

    /**
     * 支持选项的类型
     */
    const IMAGE_TYPES = [
        self::TYPE_IMAGE             => '图片',
        self::TYPE_IMAGE_LINK        => '链接图片',
        self::TYPE_IMAGES            => '图片集',
        self::TYPE_IMAGES_LINKS      => '链接图片集',
    ];

    /**
     * 保存前执行
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        switch ($this->type) {
            case self::TYPE_CONTENT          :
                $this->src = "";
                break;
            case self::TYPE_IMAGE            :
                $this->content = "";
                break;
            case self::TYPE_IMAGE_LINK       :
                break;
            case self::TYPE_CLOUD_WORDS      :
            case self::TYPE_CLOUD_WORDS_LINKS:
            case self::TYPE_LIST             :
            case self::TYPE_LIST_LINKS       :
            case self::TYPE_IMAGES           :
            case self::TYPE_IMAGES_LINKS     :
                $this->src     = "";
                $this->content = "";
                break;
        }
        return parent::beforeSave($insert);
    }

    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getOptionCount()
    {
        return $this->hasOne(
            BlockOption::class,
            ['key' => 'key']
        )->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(
            BlockOption::class,
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

    /**
     * 返回 toArray 的字段
     * @return array|false
     */
    public function fields()
    {
        $fields                = parent::fields();
        $fields['optionCount'] = 'optionCount';
        return $fields;
    }

    /**
     * 判断当前区块能否支持列表选项
     *
     * @return bool
     */
    public function isSupportList()
    {
        return isset(self::LIST_TYPES[$this->type]) && self::LIST_TYPES[$this->type];
    }
}