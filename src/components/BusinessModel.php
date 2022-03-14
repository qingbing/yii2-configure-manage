<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\components;


use Yii;
use YiiHelper\abstracts\Model;

/**
 * 业务数据模型基类
 *
 * Class BusinessModel
 * @package YiiConfigureManage\components
 */
class BusinessModel extends Model
{
    /**
     * 业务数据链接
     *
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return Yii::$app->get('db_business');
    }
}