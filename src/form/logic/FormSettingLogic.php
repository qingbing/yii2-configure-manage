<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\form\logic;


use Yii;
use YiiConfigureManage\form\models\FormCategory;
use YiiConfigureManage\form\models\FormOption;
use YiiConfigureManage\form\models\FormSetting;
use YiiHelper\helpers\AppHelper;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 工具: 配置表单
 *
 * Class FormSettingLogic
 * @package YiiConfigureManage\form\logic
 */
class FormSettingLogic
{
    /**
     * 获取实例
     *
     * @param string $code
     * @return $this
     */
    public static function getInstance(string $code)
    {
        return new self($code);
    }

    /**
     * @var string
     */
    protected $key;
    /**
     * @var FormCategory
     */
    protected $category;
    /**
     * @var FormSetting
     */
    protected $setting;
    /**
     * @var string
     */
    private $_cacheKey;

    /**
     * 获取配置表单实例
     *
     * FormSetting constructor.
     * @param string $key
     */
    final private function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * 实例基本信息初始化
     *
     * @throws BusinessException
     * @throws \yii\base\InvalidConfigException
     */
    protected function init()
    {
        $category = FormCategory::findOne([
            'key' => $this->key,
        ]);
        if (null === $category) {
            throw new BusinessException("不存在的表单类型");
        }
        if ($category->is_setting != 1) {
            throw new BusinessException(replace('表单"{key}"不是配置表单', [
                "{key}" => $this->key,
            ]));
        }
        $this->category = $category;
        $setting        = $category->setting;
        if (null === $setting) {
            $setting      = Yii::createObject(FormSetting::class);
            $setting->key = $category->key;
        }
        $this->setting = $setting;
    }

    /**
     * 获取参数的默认值
     *
     * @return array
     */
    protected function getDefaults()
    {
        $options = FormOption::getEnableOptions($this->key);
        $R       = [];
        foreach ($options as $option) {
            $R[$option->field] = $option->default;
        }
        return $R;
    }

    /**
     * 合并配置表单值
     *
     * @param array|null $values
     * @return array
     */
    protected function mergeSetting(?array $values = null)
    {
        // 获取参数的默认值
        $defaults = $this->getDefaults();
        if (null === $values || 0 === count($defaults)) {
            return $defaults;
        }
        $R = [];
        foreach ($defaults as $field => $default) {
            $R[$field] = $values[$field] ?? $default;
        }
        return $R;
    }

    /**
     * 获取配置表单数据
     *
     * @param string|null $formKey
     * @param string|null $default
     * @return bool|mixed|string|null
     * @throws BusinessException
     */
    public function get(?string $formKey = null, ?string $default = null)
    {
        // 基本数据初始化
        $this->init();
        // 获取 setting 参数
        if (is_array($this->setting->values) || null === $this->setting->values) {
            $values = $this->setting->values;
        } else {
            $values = json_decode($this->setting->values, true);
        }
        $settings = $this->mergeSetting($values);

        if (empty($settings)) {
            throw new BusinessException("配置表单尚未设置表单项目");
        }
        if (null === $formKey) {
            return $settings;
        }
        if (isset($settings[$formKey])) {
            return $settings[$formKey];
        }
        return $default;
    }

    /**
     * 保存配置表单数据
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function save(array $params)
    {
        // 基本数据初始化
        $this->init();
        $settings        = $this->mergeSetting($params);
        $setting         = $this->setting;
        $setting->values = $settings;
        return $setting->saveOrException();
    }
}
