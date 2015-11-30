<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public $json=[
        'status'=>1,//数据传输状态 1：成功，0：失败
        'message'=>'',
        'music'=>0,//是否开启音乐 1：播放，0：停止播放，-1暂停播放
        'musicurl'=>'',
        'vedio'=>0//是否进行视频通话 1：开始，0：停止
    ];

    public function index(){
        $action=I('action');//行为规范
        $hdata=I('data');//健康数据
        switch($action){
            case 1:
                $this->putData($hdata);
                break;
            case 2:
                $this->getData(I('name'));
                break;
            default: $this->ajaxReturn([
                'status'=>0,
                'message'=>'sorry,this is error!'
            ]);//status:0返回值错误；1：传输成功
        }
    }

    /**
     * @param $act 音乐操作action，1：开始，0：停止，-1：暂停
     * @param int $url 音乐的地址
     */
    public function music($act,$url=1){
        if($url!=1){
            $data['musicurl']=$url;
        }
        $data['music']=$act;
        $data['ctime']=time();
        $log=M('log');
        $log->add($data);
    }

    /**
     * @param $act 视频操作action 1：启动视频，0停止视频
     */
    public function vedio($act){
        $data['vedio']=$act;
        $data['ctime']=time();
        $log=M('log');
        $log->add($data);
    }

    /**
     * @param $act 睡眠监控操作action，1：开启睡眠监控，0停止睡眠监控
     */
    public function sleep($act){
        $data['sleep']=$act;
        $data['ctime']=time();
        $log=M('log');
        $log->add($data);
    }
    /*
     * get数据模块，用于客户端接收数据
     * 每一次接收从数据库获取最近10条数据
     * @author 莫回首<1@lailin.xyz>
     *
     * */
    public function getData($name){
        $hdata=M('data');
//        $user=M('user');
//        $map['id']=I('uid');
//        $getTime=$user->where($map)->getField('gtime');
//
//        $mapa['uid']=I('uid');
//        $mapa['ctime']=array('gt',$getTime);
        $mapa['uid']=1;
        $mapa['name']=$name;
        $a=$hdata->where($mapa)->limit(1)->order('id desc')->field('data,ctime,name')->select();//从数据库获取客户端上传的健康数
//        print_r($a);
//        print_r($mapa);

        if($a){
            $gTime['gtime']=time();
//            $user->add($gTime);
            $this->ajaxReturn([
                'status'=>1,
                'message'=>'获取数据成功',
                'data'=>$a[0]
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
            $hdata=$this->handle($data1);
            $hdata['ctime']=time();
            $hdata['uid']=1;
            if($hdata['data']){
                $wlcbs=M('data');
                $result=$wlcbs->add($hdata);
                if($result){
                    $this->setJson(1,'恭喜你上传成功');
                    $this->ajaxReturn($this->json);
                }else{
                    $this->setJson(0,"数据格式错误");
                    $this->ajaxReturn($this->json);
                }
            }else{
                $this->setJson(0,"数据格式错误");
                $this->ajaxReturn($this->json);
            }

    }

    /*
     * @author 莫回首 <1@lailin.com>
     * @param int $a 接收的有关健康的数据
     * @return bool
     * @description 用于检测和判定数据类型
     **/
    public function handle($a){
                    switch(floor($a/10000)){
                        //floor取比当前数小的最大整数
                        case 1:
                            $b['data']=$a%10000;
                            $b['name']='temp';
                            break;
                        case 2:
                            $b['data']=$a%10000;
                            $b['name']='noise';
                            break;
                        case 3:
                            $b['data']=$a%10000;
                            $b['name']="light";
                            break;
                        case 4:
                            $b['data']=$a%10000;
                            $b['name']='heart';
                            break;
                        case 5:
                            $b['data']=$a%10000;
                            $b['name']='sleep';
                            break;
                        case 6:
                            $b['data']=$a%10000;
                            $b['name']='danger';
                            break;
                        default:return false;
                    }
            return $b;

    }

    public function setJson($status,$message)
    {
        $this->json['status'] = $status;
        $this->json['message'] = $message;
        $log = M('log');
        $data = $log->order('id DESC')->limit('1')->select();
//        print_r($data);
        $this->json['music'] = $data[0]['music'];
        $this->json['musicurl'] = $data[0]['musicurl'];
        $this->json['vedio'] = $data[0]['vedio'];
        $this->json['sleep']=$data[0]['sleep'];
        if($data[0]['music']!=2||$data[0]['vedio']!=2||$data[0]['sleep']!=2){
            $data1['ctime']=time();
            $log->add($data1);
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
//            foreach($a as $k => $a){
//                if($a<0) return false;
//            }
//            return true;
//        }else{
//            return false;
//        }
//    }





}