<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {


    public function index(){
        $action=I('action');//行为规范
        $hdata=I('data');//健康数据
        switch($action){
            case 1:
                $this->putData($hdata);
                break;
            case 2:
                $this->getData();
                echo $hdata;
                break;
            default: $this->ajaxReturn([
                'status'=>0,
                'message'=>'sorry,this is error!'
            ]);//status:0返回值错误；1：传输成功
        }
    }

    /*
     * get数据模块，用于客户端接收数据
     * 每一次接收从数据库获取最近10条数据
     * @author 莫回首<1@lailin.xyz>
     *
     * */
    public function getData(){
        $hdata=M('data');
        $user=M('user');
        $map['id']=I('uid');
        $getTime=$user->where($map)->getField('gtime');

        $mapa['uid']=I('uid');
        $mapa['ctime']=array('gt',$getTime);
        $a=$hdata->where($mapa)->limit(4)->order('id desc')->field('data,ctime')->select();//从数据库获取客户端上传的健康数据

        $b=$this->handle($a);//处理过后的健康数据

        if($b){
            $gTime['gtime']=time();
//            $user->add($gTime);
            $this->ajaxReturn([
                'status'=>1,
                'message'=>'获取数据成功',
                'data'=>$b
            ]);
        }else{
            $this->ajaxReturn([
                    'status'=>0,
                    'message'=>'获取数据出错！'
                ]
            );
        }
    }

    /*
     * put数据模块，用于接收发送端发送的数据
     * @author 莫回首 <1@lailin.com>
     *
     **/
    public function putData($data1){
        $hdata['data']=$data1;
        $hdata['ctime']=time();
        $hdata['uid']=1;
        if($hdata['data']>10000&&$hdata['data']<49999){
            $wlcbs=M('data');
            $result=$wlcbs->add($hdata);
            if($result){
                $this->ajaxReturn([
                    'status'=>1,
                    'message'=>'恭喜你上传成功！'
                ]);
            }else{
                $this->ajaxReturn([
                    'status'=>0,
                    'message'=>'sorry,this is error!'
                ]);
            }
        }else{
            $this->ajaxReturn([
                'status'=>0,
                'message'=>'sorry,this is error!'
            ]);
        }

    }

    /*
     * @author 莫回首 <1@lailin.com>
     * @param int $a 接收的有关健康的数据
     * @return bool
     * @description 用于检测和判定数据类型
     **/
    public function handle($a){
        if(is_array($a)){
            foreach($a as $key => $val){
                    $v=$val['data'];
                    $b['ctime']=$val['ctime'];
                    switch(floor($v/10000)){
                        //floor取比当前数小的最大整数
                        case 1:
                            $b['temp']=$v%10000;
                            break;
                        case 2:
                            $b['noise']=$v%10000;
                            break;
                        case 3:
                            $b['light']=$v%10000;
                            break;
                        case 4:
                            $b['heart']=$v%10000;
                            break;
                        default:return false;
                    }

            }
            return $b;
        }

    }

//    /*
//     * 用于检测数据数组是否有4个值，并且每一个值大于0
//     * @author 莫回首<1@lailin.xyz>
//     * @parame int a 需要判断的数组
//     * @return bool
//     **/
//    public function checkData($a){
//        if(is_array($a)){
//            if(count($a)!=4){
//                return false;
//            }
//            foreach($a as $k => $v){
//                if($v<0) return false;
//            }
//            return true;
//        }else{
//            return false;
//        }
//    }





}