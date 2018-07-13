<?php
class IndexAction extends CommonAction {
	// 框架首页
	public function index() {
		ob_clean();
		$this->_checkUser();
		$this->_Config_name();//调用参数
		C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		C ( 'SHOW_PAGE_TRACE', false );
		$fck = D ('Fck');

		$id = $_SESSION[C('USER_AUTH_KEY')];
		$field = '*';
		$fck_rs = $fck -> field($field) -> find($id);
//		$money1 = $fck_rs['l'] * $fck_rs['cpzj'];
//		$money2 = $fck_rs['r'] * $fck_rs['cpzj'];

		$HYJJ="";
		$this->_levelConfirm($HYJJ,1);
		$this->assign('voo',$HYJJ);//会员级别

		$this -> assign('fck_rs',$fck_rs);



		$fck->emptyTime();

		$ydate = strtotime(date('Y-m-d'));//当天时间
		$end_date =$ydate + (24*3600);//当天结束时间
		$bonus = M('bonus');
		$where = array();
		$where['uid'] = $id;
		$where['e_date'] = array(array('gt',$ydate),array('lt',$end_date));
		$count[1] = $bonus->where($where)->sum('b1');
		if(empty($count[1]))$count[1]=0;
		$count[2] = $bonus->where($where)->sum('b2');
		if(empty($count[2]))$count[2]=0;
		$count[3] = $bonus->where($where)->sum('b3');
		if(empty($count[3]))$count[3]=0;
		$count[4] = $bonus->where($where)->sum('b4');
		if(empty($count[4]))$count[4]=0;
		$count[5] = $bonus->where($where)->sum('b5');
		if(empty($count[5]))$count[5]=0;
		$count[6] = $bonus->where($where)->sum('b6');
		if(empty($count[6]))$count[6]=0;

		$this -> assign('count',$count);

		$fee_rs = M('fee')->field('s2,i4,str29')->find();
		$money1 = $fck_rs['l'] * $fee_rs['s2'];
		$money2 = $fck_rs['r'] * $fee_rs['s2'];

		$fee_i4 = $fee_rs['i4'];

		$gg = $fee_rs['str29'];

		$this -> assign('gg',$gg);
		$this -> assign('money1',$money1);
		$this -> assign('money2',$money2);

		$this -> assign('fee_i4',$fee_i4);
		//dump($money2);exit;
		//八点显示
//		$time = date('H');
//		$tt=0;
//		if($time>=19||$time<8){
//			$tt = 1;
//		}
//		$this -> assign('time',$tt);
		//未查看的信息记录统计
		$map = array();
		$map['isid']    = $id;   //信息所属所属ID
		$map['s_uid']   = $id;   //会员ID
		$map['is_read'] = 0;     // 0 为未读
        $info_count = M ('msg') -> where($map) -> count(); //总记录数
		$this -> assign('info_count',$info_count);


		$arss = $this->_cheakPrem();
        $this->assign('arss',$arss);

		$HYJJ = '';
		$this->_levelConfirm($HYJJ,1);
		$this->assign('voo',$HYJJ);//会员级别

		$this->auto_buy_fh();

//		$this->checkInfo();

		$this->display('index');

	}

	public function checkInfo(){
        if (empty($_SESSION['LoginCheck'])){
            $content  = '=============================告示====================================';
            $content .= '\n\n';
            $content .= '本系统是测试系统,只供测试使用,不得做任何商业用途,';
            $content .= '\n\n';
            $content .= '在测试过程中,数据丢失或者系统有变动,所造成的损失,公司概不负责。';
            $content .= '\n\n';
            $content .= '如要正式使用系统，请尽快测试完善系统，';
            $content .= '\n\n';
            $content .= '测试完成后在通知负责人给您转成正式的使用。谢谢合作。';
            $content .= '\n\n';
            $content .= '=====================================================================';
            $url = '';
            $_SESSION['LoginCheck'] = '2';
            echo "<script>alert('". $content ."');location.href='". $url ."';</script>";
        }
    }

//    //每日自动结算
//	public function aotu_clearings(){
//
//		$fck = D ('Fck');
//		$now_dtime = strtotime(date("Y-m-d"));
//		if(empty($_SESSION['auto_cl_ok'])||$_SESSION['auto_cl_ok']!=$now_dtime){
//			$js_c = $fck->where('is_pay>0 and is_lock=0 and get_date<'.$now_dtime)->count();
//			if($js_c>0){
//				$fck->xingyunjiang($now_dtime);//幸运奖
//				$this->_clearing();//全部奖金结算
//			}
//			$_SESSION['auto_cl_ok'] = $now_dtime;
//		}
//		if(empty($_SESSION['auto_cl_bbok'])||$_SESSION['auto_cl_bbok']!=$now_dtime){
//			$js_ctt = $fck->where('is_pay>0 and is_lock=0 and b0!=0')->count();
//			if($js_ctt>0){
//				$this->_clearing();//全部奖金结算
//			}
//			$_SESSION['auto_cl_bbok'] = $now_dtime;
//		}
//	}


	public function auto_buy_fh(){

		$fee = M('fee');
		$fee_rs = $fee->field('s11')->find();
		$one_m = $fee_rs['s11'];

		$fck = D ('Fck');
		$xiaof = M('xiaof');
		$llrs = $fck->where('is_pay>0 and agent_cf>='.$one_m.'')->field('id,user_id,agent_cf,re_path')->select();
		foreach($llrs as $lrs){
			$myid = $lrs['id'];
			$agent_cf = $lrs['agent_cf'];
			$can_b = floor($agent_cf/$one_m);
			$can_m = $can_b*$one_m;
			$las_m = $agent_cf-$can_m;

			$result = $fck->execute("update __TABLE__ set agent_cf=".$las_m.",gp_num=gp_num+".$can_b." where id=".$myid." and agent_cf=".$agent_cf);
			if($result){
				$data				= array();
				$data['uid']		= $lrs['id'];
				$data['user_id']	= $lrs['user_id'];
				$data['rdt']		= mktime();
				$data['money']		= $can_m;
				$data['money_two']	= $can_b;
				$data['epoint']	= $can_m;
				$data['is_pay']	= 0;
				$xiaof->add($data);
				unset($data);
			}
		}
		unset($fee,$fck,$xiaof,$llrs);
	}

}
?>