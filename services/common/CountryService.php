<?php

namespace services\common;

use Yii;
use common\enums\CacheEnum;
use common\models\common\Country;
use common\components\Service;
use common\helpers\ArrayHelper;

/**
 * Class CountryService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class CountryService extends Service
{
    /**
     * 获取省市区禁用状态
     *
     * @param array $countryIds
     * @param array $provinceIds
     * @param array $cityIds
     * @return mixed
     */
    public function getAreaTree(array $countryIds, array $provinceIds, array $cityIds)
    {
        $address = $this->findAllInCache();

        $allIds = [];
        foreach ($address as &$item) {
            $allIds[$item['pid']][] = $item['id'];
        }

        // 计算选中状态
        foreach ($address as &$item) {
            $item['is_disabled'] = true;
            $data = $allIds[$item['id']] ?? [];

            if ($item['level'] == 1) {
                foreach ($data as $datum) {
                    !in_array($datum, $provinceIds) && $item['is_disabled'] = false;
                    $citys = $allIds[$datum] ?? [];

                    foreach ($citys as $city) {
                        !in_array($city, $cityIds) && $item['is_disabled'] = false;
                    }

                    unset($citys);
                }
            }

            if ($item['level'] == 2) {
                foreach ($data as $datum) {
                    !in_array($datum, $cityIds) && $item['is_disabled'] = false;
                }
            }

            if ($item['level'] == 3 && !in_array($item['id'], $cityIds)) {
                $item['is_disabled'] = false;
            }

            unset($data);
        }

        // 递归重组省市区
        $address = ArrayHelper::itemsMerge($address, 0);
        // 大区
        $regionalAll = $this->regionalAll();
        $regroupAddress = [];

        foreach ($address as $value) {
            foreach ($regionalAll as $key => $data) {
                foreach ($data as $datum) {
                    $datum == $value['title'] && $regroupAddress[$key][] = $value;
                }
            }
        }

        unset($address, $regionalAll, $allIds);
        return $regroupAddress;
    }

    /**
     * @param int $pid
     * @return int|string
     */
    public function getCountByPid($pid = 0)
    {
        return Country::find()
            ->select(['id'])
            ->where(['pid' => $pid])
            ->count();
    }

    /**
     * @param $ids
     * @return array|\yii\db\ActiveRecord[]
     *
     */
    public function findByIds($ids)
    {
        return Country::find()
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('id asc')
            ->where(['in', 'id', $ids])
            ->asArray()
            ->all();
    }

    /**
     * @param int $pid
     * @param string $level
     * @return array
     */
    public function getProvinceMapByPid($pid = 0, $level = '')
    {
        return ArrayHelper::map($this->getProvinceByPid($pid, $level), 'id', 'title');
    }

    /**
     * 根据父级ID返回信息
     *
     * @param int $pid
     * @return array
     */
    public function getProvinceByPid($pid = 0, $level = '')
    {
        if ($pid === '') {
            return [];
        }

       return Country::find()
            ->where(['pid' => $pid])
            ->orderBy('id asc')
            ->select(['id', 'title', 'pid'])
            ->andFilterWhere(['level' => $level])
            ->orderBy('sort asc')
            ->cache(600)
            ->asArray()
            ->all();
    }

    /**
     * 根据id获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getName($id)
    {
        if ($countries = Country::findOne($id)) {
            return $countries['title'] ?? '';
        }

        return false;
    }

    /**
     * 根据id数组获取区域名称
     *
     * @param $id
     * @return mixed
     */
    public function getProvinceListName(array $ids)
    {
        if ($countries = Country::find()->where(['in', 'id', $ids])->orderBy('id asc')->all()) {
            $address = '';

            foreach ($countries as $country) {
                $address .= $country['title'] . ' ';
            }

            return $address;
        }

        return false;
    }

    /**
     * @return array|mixed|\yii\db\ActiveRecord[]
     */
    public function findAllInCache()
    {
        $cacheKey = CacheEnum::getPrefix('countries');

        // 获取缓存
        if (!($data = Yii::$app->cache->get($cacheKey))) {
            $data = Country::find()
                ->select(['id', 'title', 'pid', 'level'])
                ->where(['<=', 'level', 4])
                ->orderBy('id asc')
                ->asArray()
                ->all();

            Yii::$app->cache->set($cacheKey, $data, 60 * 60 * 24 * 24);
        }

        return $data;
    }

    /**
     * 获取大区
     *
     * @return array
     */
    public function regionalAll()
    {
        $region = [
            '华东' => [
                '江苏省',
                '上海市',
                '浙江省',
                '安徽省',
                '江西省',
            ],
            '华北' => [
                '天津市',
                '河北省',
                '山西省',
                '内蒙古自治区',
                '山东省',
                '北京市',
            ],
            '华南' => [
                '广东省',
                '广西壮族自治区',
                '海南省',
                '福建省',
            ],
            '华中' => [
                '湖南省',
                '河南省',
                '湖北省',
            ],
            '东北' => [
                '辽宁省',
                '吉林省',
                '黑龙江省',
            ],
            '西北' => [
                '陕西省',
                '陕西省',
                '青海省',
                '宁夏回族自治区',
                '新疆维吾尔自治区',
            ],
            '西南' => [
                '重庆市',
                '四川省',
                '贵州省',
                '云南省',
                '西藏自治区',
            ],
            '港澳台' => [
                '香港特别行政区',
                '澳门特别行政区',
                '台湾省',
            ],
        ];

        return $region;
    }
}