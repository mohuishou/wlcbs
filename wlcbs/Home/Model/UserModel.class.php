<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {

    protected $_validate    =   array(
        array('username','require','用户名必须'), // 验证用户名是否已经存在
        array('imei','require','抱歉没有获取到IMEI码'), // 验证用户名是否已经存在
//        array('mac','require','抱歉没有获取到设备地址'), // 验证用户名是否已经存在
        array('username','','帐号名称已经存在！',1,'unique',1), // 验证用户名是否已经存在
        array('imei','','帐号名称已经存在！',1,'unique',1), // 验证用户名是否已经存在
        array('mac','','帐号名称已经存在！',1,'unique',1), // 验证用户名是否已经存在
        array('password','6,12','密码长度不正确',0,'length'), // 验证密码是否在指定长度范围
    );

    protected $_auto    =   array(
        array('ctime','time',1,'function'),
        array('gtime','time',1,'function'),
    );

}
