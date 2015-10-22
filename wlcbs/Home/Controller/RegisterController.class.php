<?php
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller {
    public function add(){
        echo $data['username']=$_GET['username'];
        $data['password']=$_GET['password'];
        $data['imei']=$_GET['imei'];
//        $data['mac']=$_GET['mac'];
        $user=D('user');
        $user->create($data);
        $result =$user->add();
        if($result) {
            $this->success('数据添加成功');
        }else{
                $this->error('数据添加失败');
        }
    }


}