<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiConfigureManage\block\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口: 区块类型管理
 *
 * Interface IBlockCategoryService
 * @package YiiConfigureManage\block\interfaces
 */
interface IBlockCategoryService extends ICurdService
{
    /**
     * 区块类型map
     *
     * @param array|null $params
     * @return array
     */
    public function typeMap(): array;

    /**
     * 支持选项的类型列表
     *
     * @return array
     */
    public function listTypes(): array;

    /**
     * 图片类型列表
     *
     * @return array
     */
    public function imageTypes(): array;
}