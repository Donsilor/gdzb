<?php

namespace addons\Shop\common\models;

use common\helpers\RegularHelper;
use Yii;

/**
 * 
 *
 * @property int $order_id 订单ID
 * @property int $merchant_id
 * @property int $member_id 用户id
 * @property int $country_id 国家ID
 * @property int $province_id 省id
 * @property int $city_id 市id
 * @property string $firstname 名字
 * @property string $lastname 姓氏
 * @property string $country_name
 * @property string $province_name
 * @property string $city_name
 * @property string $address_details 详细地址
 * @property int $zip_code 邮编
 * @property string $mobile 手机号码
 * @property string $mobile_code 手机区号
 * @property string $email 邮箱地址
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class OrderAddress extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return self::tableFullName('order_address');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id', 'merchant_id', 'member_id', 'country_id', 'province_id', 'city_id',  'created_at', 'updated_at'], 'integer'],
            [['realname'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 150],
            [['firstname', 'lastname'], 'string', 'max' => 100],
            [['country_name', 'province_name'], 'string', 'max' => 30],
            [['city_name'], 'string', 'max' => 100],
            [['address_details'], 'string', 'max' => 300],
            [['mobile'], 'string', 'max' => 20],
            [['zip_code'], 'string', 'max' => 20],
            [['mobile_code'], 'string', 'max' => 10],
            [['order_id'], 'unique'],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
            ['email', 'match', 'pattern' => RegularHelper::email(), 'message' => '请输入正确的邮箱'],
            ['email', 'required', 'on' => self::SEND_PAID_EMAIL],
            ['email', 'email', 'on' => self::SEND_PAID_EMAIL],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单ID',
            'merchant_id' => 'Merchant ID',
            'member_id' => '用户id',
            'country_id' => '国家区域',
            'province_id' => '省份',
            'city_id' => '城市市',
            'firstname' => '名字',
            'lastname' => '姓氏',
            'realname' => '收货人',
            'country_name' => '国家',
            'province_name' => '省份',
            'city_name' => '城市',
            'address_details' => '详细地址',
            'zip_code' => '邮编',
            'mobile' => '手机号码',
            'mobile_code' => '手机区号',
            'email' => '邮箱地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
