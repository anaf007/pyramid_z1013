<?php
class TreeAction extends CommonAction {
	public function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		//$this->_checkUser();
		//$this->injectCheck(1);//调用过滤函数
		$this->_Config_name();//调用参数
	}

	//会员级别颜色
	function ji_Color(){
		$Color = array();
		$Color['1'] = "#E6E8FA";
		$Color['2'] = "#5CACEE";//"#E6E6FA";//"#DDA0DD";
		$Color['3'] = "#D9D919";
		$Color['4'] = "#FF5555";//"#FFFF00";
//		$Color['5'] = "#9BCD9B";
//		$Color['6'] = "#FFFF00";
		return $Color;
	}

	function AC_Color(){
		$HYJJ="";
		$this->_levelConfirm($HYJJ);
		$Color = array();
		$Color['1'] = $HYJJ[1];
		$Color['2'] = $HYJJ[2];
		$Color['3'] = $HYJJ[3];
		$Color['4'] = $HYJJ[4];
//		$Color['5'] = "空单";
//		$Color['6'] = "业务部长";
//		$Color['7'] = "#0066FF";
		return $Color;
	}

	//开通 未开通 报单中心
	function Mi_Cheng(){
		$Color['0']  = '临时会员';
		$Color['1']  = '正式会员';
		$Color['2']  = '报单中心';//'报单中心';
		return $Color;
	}

	function kd_Color(){
		$Color['0']    = '#C0C0C0';
		$Color['1']    = '#F5FFFA';
		$Color['2']    = '#DDA0DD';
		return $Color;
	}

//	//二级验证
//	public function Cody(){
//		//$this->_checkUser();
//		$UrlID = (int) $_GET['UrlID'];
//		if (empty($UrlID)){
//			$this->error('二级密码错误!');
//			exit;
//		}
//		$cody   =  M ('cody');
//        $list	=  $cody->where("c_id=$UrlID")->getField('c_id');
//		if (!empty($list)){
//			$this->assign('vo',$list);
//			$this->display('../public/cody');
//			exit;
//		}else{
//			$this->error('二级密码错误!');
//			exit;
//		}
//	}

	public function cody(){
		//===================================二级验证
		$UrlID = (int) $_GET['c_id'];
		if (empty($UrlID)){
			$this->error('二级密码错误!');
			exit;
		}
		if(!empty($_SESSION['user_pwd2'])){
			$url = __URL__."/codys/Urlsz/$UrlID";
			$this->_boxx($url);
			exit;
		}
		$cody   =  M ('cody');
        $list	=  $cody->where("c_id=$UrlID")->field('c_id')->find();
		if ($list){
			$this->assign('vo',$list);
			$this->display('../Public/cody');
			exit;
		}else{
			$this->error('二级密码错误!');
			exit;
		}
	}


	//二级验证后调转页面
	public function Codys(){
		$this->_checkUser();
		$Urlsz = $_POST['Urlsz'];
		if(empty($_SESSION['user_pwd2'])){
			$pass  = $_POST['oldpassword'];
			$fck   =  M ('fck');
			if (!$fck->autoCheckToken($_POST)){
				$this->error('页面过期请刷新页面!');
				exit();
			}
			if (empty($pass)){
				$this->error('二级密码错误!');
				exit();
			}
			$where =array();
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
			$where['passopen'] = md5($pass);
			$list = $fck->where($where)->field('id')->find();
			if($list == false){
				$this->error('二级密码错误!');
				exit();
			}
			$_SESSION['user_pwd2'] = 1;
		}else{
			$Urlsz = $_GET['Urlsz'];
		}
		switch ($Urlsz){
			case 1;
				$_SESSION['UrlszUserpass'] = 'MyssPuTao';
				$bUrl = __URL__.'/PuTao';
				$this->_boxx($bUrl);
				break;
			case 2;
				$_SESSION['UrlszUserpass'] = 'Myssindex';
				$bUrl = __URL__.'/index';
				$this->_boxx($bUrl);
				break;
			case 3;
				$_SESSION['UrlszUserpass'] = 'MyssQiCheng';
				$bUrl = __URL__.'/QiCheng';
				$this->_boxx($bUrl);
				break;
			case 5;
				$_SESSION['UrlszUserpass'] = 'MyssTreePass';
				$bUrl = __URL__.'/Tree2';
				$this->_boxx($bUrl);
				break;
			case 6;
				$_SESSION['UrlszUserpass'] = 'MyssTreeRe';
				$bUrl = __URL__.'/Tree';
				$this->_boxx($bUrl);
				break;
			default;
				$this->error('二级密码错误1!');
				break;
		}
	}

	//跳转到注册页面
	public function KaiBoLuo(){
		$time = date('H');
		$url = __APP__;
//		if($time<19&&$time>8){
////			$bUrl = __APP__.'/index';//审核会员
////			$this->_boxx($bUrl);
//			echo "<script>window.top.location.href=\"$url\"</script> ";
//		}
		$RID = (int) $_GET['RID'];//推荐人
		$TPL = (int) $_GET['TPL'];//左区右区
		$FID = (int) $_GET['FID'];//安置人
		$_SESSION['Urlszpass'] = 'MyssBoLuo';
		$bUrl = __APP__."/Fck/users/RID/". $RID ."/TPL/". $TPL ."/FID/". $FID;
		redirect($bUrl);//URL 重定向
		exit;
	}

	//推荐图
	public function Tree() {
		$this->_checkUser();
		$fck = M("fck");
		$ID  = (int) $_GET['UID'];
		$Mmid=$_SESSION[C('USER_AUTH_KEY')];
		if (empty($ID))$ID = $_SESSION[C('USER_AUTH_KEY')];
		if (!is_numeric($ID) || strlen($ID) > 20 ) $ID = $_SESSION[C('USER_AUTH_KEY')];
		$UserID = $_POST['UserID'];

		if (strlen($UserID) > 20 ){
			$this->error( '错误操作！');
			exit;
		}
		if (!empty($UserID)){
			if (!$fck->autoCheckToken($_POST)){
				$this->error( '页面过期，请刷新页面！');
				exit;
			}
			$fwhere = "re_path like '%,". $Mmid .",%' and user_id='". $UserID ."' ";
//			$fwhere = "user_id='".$UserID."'";
			$frs = $fck->where($fwhere)->field('id')->find();
			if (!$frs){
				$this->error('没有找到该会员！');
				exit;
			}else{
				$ID = $frs['id'];
			}
		}
		$where = array();
		$where['id'] = $ID;
		$where['_string'] = "(re_path like '%,".$_SESSION[C('USER_AUTH_KEY')].",%' or id = ".$_SESSION[C('USER_AUTH_KEY')].")";
		$rs = $fck->where($where)->find();
		if (!$rs){
			$this->error('没有找到该会员！');
			exit;
		}else{
			$UID		= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$FatherID	= $rs['father_id'];
			$FatherName	= $rs['father_name'];
			$ReID		= $rs['re_id'];
			$ReName		= $rs['re_name'];
			$isPay		= $rs['is_pay'];
			$isAgent	= $rs['is_agent'];
			$isJB	= $rs['is_jb'];
			$isLock		= $rs['is_lock'];
			$uLevel		= $rs['u_level'];
			$NanGua		= 'aappleeva';
			$ReNUMS		= $rs['re_nums'];
			$QiCheng_l	= $rs['l'];
			$QiCheng_r  = $rs['r'];
		}
		$tree_Images = __PUBLIC__ .'/Images/tree/';//图片所在文件夹
		$rows = array();
		$rows['0'] .= "<SCRIPT LANGUAGE='JavaScript'>" . chr(13) . chr(10);
		$rows['0'] .= "var tree = new MzTreeView('tree');" . chr(13) . chr(10);
		$rows['0'] .= "tree.icons['property'] = 'property.gif';" . chr(13) . chr(10);
		$rows['0'] .= "tree.icons['Trial'] = 'trial.gif';" . chr(13) . chr(10);//试用
		$rows['0'] .= "tree.icons['Official']  = 'Official.gif';" . chr(13) . chr(10);//正试成员
		$rows['0'] .= "tree.iconsExpand['book'] = 'bookopen.gif';" . chr(13) . chr(10); //展开时对应的图片
		$rows['0'] .= "tree.icons['Center']  = 'center.gif';" . chr(13) . chr(10);//报单中心成员
		$rows['0'] .= "tree.setIconPath('". $tree_Images ."'); " . chr(13) . chr(10);//可用相对路径
		$i = -1;
		$j = 1;

		$fee = M('fee');
		$fee_rs = $fee->field('s10')->find();
		$Level = explode('|',$fee_rs['s10']);
		$uLe    = $uLevel-1;
		if ($isAgent >= 2) {
			$rows['0'] .= "tree.nodes['" . $i . "_" . $j . "'] = 'text:" . $UserID . "[". $Level[$uLe] ."];icon:Center;url:Tree/UID/" . $UID .";';" . chr(13) . chr(10) ;
		}else{
			if ($isPay == 1){
				$rows['0'] .= "tree.nodes['" . $i . "_" . $j . "'] = 'text:" . $UserID . "[". $Level[$uLe] ."];icon:Official;url:Tree/UID/". $UID . ";';" . chr(13) . chr(10) ;
			}else{
				$rows['0'] .= "tree.nodes['" . $i . "_" . $j . "'] = 'text:" . $UserID. "[". $Level[$uLe] ."];icon:Trial;url:Tree/UID/". $UID . ";';" . chr(13). chr(10);
			}
		}
		$this->_MakeTree($UID, 1, $isPay, 1, $j, $rows);
		$rows['0'] .= "tree.setTarget('_self');" . chr(13) . chr(10);
		//document.write(tree.toString());    //亦可用 obj.innerHTML = tree.toString();
		$rows['0'] .= "thisTree.innerHTML = tree.toString();" . chr(13) . chr(10);
		//$rows['0'] .= "MzTreeView.prototype.expandAll.call(tree);";
		$rows['0'] .= "</SCRIPT>";
		$this->assign('rs', $rows);
		$this->assign('ID', $ID);
		$this->display('Tree');
	}
	//推荐图_调用函数
	private function _MakeTree($ID,$FatherId,$IsZs,$N,$j,&$rows){
		$fck = M("fck");
		$fee = M('fee');

		$fee_rs = $fee->field('s10')->find();
		$Level = explode('|',$fee_rs['s10']);
		global $j;
		if ($j <= 1)$j = 1;
		$N++;
		if ($N <= 100){
			$k = 1;
			$where 			= array();
			$where['re_id']	= $ID;
			$rs = $fck->where($where)->order('is_pay desc,pdt asc,id asc')->select();
			foreach ($rs as $rss){
				$j		= $j+1;
				$uUser	= $rss['user_id'];
				$uName	= $rss['nickname'];
				$uIsPay	= $rss['is_pay'];
				$ID		= $rss['id'];
				$uLevel	= $rss['u_level'];
				$misjb	= $rss['is_jb'];
				$Agent	= $rss['is_agent'];
				$ReNUMS	= $rss['re_nums'];
				$QiCheng_l	= $rss['l'];
				$QiCheng_r  = $rss['r'];
				//级别

				$uLe    = $uLevel-1;
				if ($Agent >= 2){
					$rows['0'] .= "tree.nodes['" .  $FatherId .  "_" . $j . "'] = 'text:" . $uUser . "[". $Level[$uLe] ."];icon:Center;url:Tree/UID/" . $ID . ";';" ;
				}else{
					if ($uIsPay == 1){
						$rows['0'] .= "tree.nodes['" .  $FatherId .  "_" . $j . "'] = 'text:" . $uUser . "[". $Level[$uLe] ."];icon:Official;url:Tree/UID/" . $ID . ";';" ;
					}else{
						$rows['0'] .= "tree.nodes['" .  $FatherId .  "_" . $j . "'] = 'text:" . $uUser . "[". $Level[$uLe] ."];icon:Trial;url:Tree/UID/" . $ID . ";';" ;
					}
				}
				$k = $j;
				$this->_MakeTree($ID, $k, $uIsPay, $N, $j, $rows);
			}
		}
	}



	//一线图
	public function Tree1(){
		$this->_checkUser();
		$kd_c = $this->kd_Color();  //是否开通

		$fck = M ('fck');
		$id     = $_SESSION[C('USER_AUTH_KEY')];
		$UID    = (int) $_GET['ID'];
		if (empty($UID)) $UID = $id ;
		$UserID = $_POST['UserID'];  //跳转到 X 用户
		if (!empty($UserID)){
			if (strlen($UserID) > 20 ){
				$this->error( '错误操作！');
				exit;
			}
			$where = " user_id='". $UserID ."' ";
			$field = 'id';
			$rs = $fck ->where($where)->field($field)->find();
			if($rs == false){
				$this->error('没有该用户!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}

		$where = array();
		$where['id']     = array('gt',$UID);
		$field = '*';
		$rs = $fck->where($where)->order('rdt asc')->find();

		$out_where = array();
		$out_where['id'] = array(array('egt',$id),array('egt',$UID),'and');
		$out_rs = $fck->where($out_where)->field('id,user_id,is_pay,is_agent,u_level')->order('id asc')->limit(9)->select();
		//dump($fck->getLastSql());
		$lhe = 30;
		$tps = __PUBLIC__ .'/Images/tree1/';
		$i = 0;
		$Treex = "<table width='92' border='0' align='left' cellpadding='0' cellspacing='0'>";
		foreach ($out_rs as $vo){
			$i++;
			if ($vo['is_pay']>0){
				$is_color = 1;
			}else{
				$is_color = 0;
			}
			if ($vo['is_agent']>0){
				$is_color = 2;
			}
			$Level  = explode('|',C('Member_Level'));
			$uLe    = $vo['u_level']-1;
			$Treex .= "<tr align='center'><td width='90' bgcolor='#FFFFFF'><table width='90' border='0' cellpadding='0' cellspacing='1' bgcolor='#ADBA84'>
			<tr align='center'><td width='90' height='25' style='background:".$kd_c[$is_color]."'>
			<a href='". __URL__ ."/Tree1/ID/". $vo['id']."'>". $vo['user_id'] ."</a> [". $i ."]</td></tr>
			<tr align='center'><td height='25'> ". $Level[$uLe] ." </td></tr></table></td></tr>
			<tr align='center'><td height='25'><img src='". $tps ."bottom.gif' height='". $lhe ."'>
			</td></tr>";
		}
		for($u=$i+1;$u<=10;$u++){
			$Treex .= "<tr align='center'><td width='90' bgcolor='#FFFFFF'>
			<table width='90' border='0' cellpadding='0' cellspacing='1' bgcolor='#ADBA84'>
			<tr align='center'><td>[ ". $u ." ]</td></tr></table></td></tr>";
			if ($u<10){$Treex .= "<tr align='center'><td><img src='". $tps ."bottom.gif' height='". $lhe ."'></td></tr>";}
		}
		$Treex .= "</table>";

		$this->assign('Treex',$Treex);
		$this->display('Tree1');
	}



	//双轨图
	public function Tree2(){
		$time = date('H');
//		$url = __APP__;
//		if($time<20&&$time>8){
////			$bUrl = __APP__.'/index';
////			$this->_boxx($bUrl);
//			echo "<script>window.top.location.href=\"$url\"</script> ";
////			exit;
//		}
		$this->_checkUser();
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称
		$ac_c = $this->AC_Color();  //级别名称

//		$HYJJ = '';
//        $this->_levelConfirm($HYJJ,1);
//		//商户级别颜色
//		$ColorUA = array();
//		$ColorUA['2'] = "#DC143C";  //红色
//		$ColorUA['3'] = "#4169E1";  //蓝色
////		$ColorUA['3'] = "#FF3366";  //红色
////		$ColorUA['4'] = "#336666";  //青色
////		$ColorUA['5'] = "#FF3300";  //橙色
//		//开通未开通
//		$TU_MiCheng = array();
//		foreach ($HYJJ as $svo){
//		    $TU_MiCheng[]  = $svo;
//		}

		$fee = M ('fee');
		$fee_rs = $fee->field('i4')->find();

		$i4 = $fee_rs['i4'];
		if ($i4==0){
			$openm=1;
		}else{
			$openm=0;
		}

		$fck   =  M('fck');
		$fee_rs = $fck -> find();
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$myid=$_SESSION[C('USER_AUTH_KEY')];
		$UID = (int) $_GET['ID'];
		if (empty($UID)){$UID = $id;}
		$UserID = $_POST['UserID'];  //跳转到 X 用户
		if (!empty($UserID)){
			if (strlen($UserID) > 20 ){
				$this->error( '错误操作！');
				exit;
			}
			$where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
//			$where = "user_id='". $UserID ."' ";
			$field = 'id,is_boss';
			$rs = $fck ->where($where)->field($field)->find();
			if($rs == false){
				$this->error('没有该用户!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}

		$where =array();
		$where['ID'] = $UID;
		$where['_string'] = 'id>='.$id;
		$field = '*';
		$rs = $fck ->where($where)->field($field)->find();
		if (!$rs){
			$this->error('没有该用户!');
			exit();
		}else{
			$ID			= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
			if($ID==$id){
				$FatherID = $id;
			}else{
				$FatherID	= $rs['father_id'];    //安置人ID
			}

			$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
			$isLock		= $rs['is_lock'];	  //锁定(是否可以登录系统)
			$uLevel		= $rs['u_level'];      //级别
			$pPath		= $rs['p_path'];       //自已的路径
			$pLevel		= $rs['p_level'];	  //层数(数字)
			$Rid		= $rs['id'];
			$L			= $rs['l'];
			$R			= $rs['r'];
			$benqiL		= $rs['benqi_l'];//本期新增
			$benqiR		= $rs['benqi_r'];
			$SpareL		= $rs['shangqi_l'];//上期剩余
			$SpareR		= $rs['shangqi_r'];
			$zjj        = $rs['zjj'];        //总奖金

			$isagent	= $rs['is_agent'];  //
		}
		if ($isPay>1) $uLevel=5;
		if ($isPay>1) $isPay=1;


		if($rs['is_agent'] > 1){
			$isPay = 2;    //报单中心
		}

		//显示层数
		$uLev = (int) $_GET['uLev'];		//$Lev 记录显示层数
		if (is_numeric($uLev) == false) $uLev = $_SESSION['uLev2'];
		if (is_numeric($uLev) == false) $uLev = 3;
		if ($uLev < 2 || $uLev > 11)    $uLev = 3;
		$_SESSION['uLev2']=$uLev;
		for ($i=1;$i<=$uLev;$i++){
			$Nums = $Nums + pow(2,$i);		//pow(x,y) 返回x的y次方
		}
		global $TreeArray;
		$TreeArray = array();

		for ($i=1;$i<=$Nums;$i++){
			$TreeArray[$i] = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位 </td></tr></table>";
		}
		$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
		$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
		$MyYJ = "</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$R</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
		$MyYJ .= "</table>";

		$ZiJi   = $StTab."<a href='#'>". $UserID ."</a>". $MyYJ;
		$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
		$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/RID/". $myid ."/TPL/";
		$Str4C4 = "</td></tr></table>";
		if ($isPay > 0){
			$i = pow(2,$uLev);
			$TreeArray['1'] = $Str4C0.$Str4C1."0/FID/". $ID ."' target='_self'>点击注册</a>". $Str4C4;
			$TreeArray[$i]  = $Str4C0.$Str4C1."1/FID/". $ID ."' target='_self'>点击注册</a>". $Str4C4;
		}else{
			$TreeArray['1']=$Str4C0.$Str4C1."0/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$i] =$Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}

		$TreeArray['0'] = $ZiJi;

		$this->Tree2_MtKass($UID, 0, $pLevel, $uLev, $Str4C0, $Str4C1, $Str4C4,  $TreeArray, $Nums);
		//会员ID,0,绝对层次,显示层高,表开始,表内链接,表结束  ,级别颜色数组,所有空位表格,显示多少会员数(包括空位数)
		$wop = '';
		$this->Tree2_showTree($uLev, $TreeArray, $wop);

		$fee = M('fee');
		$fee_rs = $fee->field('s10')->find();
		$Level = explode('|',$fee_rs['s10']);
		$this->assign('Level',$Level);
		$this->assign('openm',$openm);
		$this->assign('ColorUA',$ji_c);
		$this->assign('TU_Color',$kd_c);
		$this->assign('TU_MiCheng',$mi_c);
		$this->assign('AC_Color',$ac_c);
		$this->assign('UID',$UID);
		$this->assign('uLev',$uLev);
		$this->assign('FatherID',$FatherID);
		$this->assign('wop',$wop);
		$this->display('Tree2');

	}
	//双轨图---生成下层会员内容
	private function Tree2_MtKass($FatherID,$iL,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,&$TreeArray,$Nums){
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称
		if (!empty($FatherID)){
			$fck = M("fck");
			$where = array();
			$where = "father_id=". $FatherID ." And p_level-". $pLevel ."<=". $uLev ." And treeplace<2 Order By treeplace Asc";
			$field = '*';
			$rs    = $fck->where($where)->field($field)->select();
			foreach($rs as $rss){
				if ($rss['treeplace'] == 0){
					$k = $iL + 1;
				}else{
					$i = ($pLevel + $uLev) - $rss['p_level'] + 1;
					$j = pow(2,$i);
					$k = $j + $iL;
				}
				$i			= ($pLevel + $uLev) - $rss['p_level'];
				$Uo			= $k + 1;
				$Yo			= $k + pow(2,($pLevel + $uLev) - $rss['p_level']);
				$Leve		= $rss['u_level'];	//用户级别
				$uisLock	= $rss['is_lock'];	//是否为正式会员
				$Lo			= $rss['l'];		//
				$Ro			= $rss['r'];		//
				$SpareLo	= $rss['shangqi_l'];
				$SpareRo	= $rss['shangqi_r'];
				$benqiLo	= $rss['benqi_l'];
				$benqiRo	= $rss['benqi_r'];
				$Rid		= $rss['id'];
				$uUserID	= $rss['user_id'];
				$uisPay		= $rss['is_pay'];
				$upLevel	= $rss['p_level'];
				$zjj        = $rss['zjj'];
				$uis_agent	= $rss['is_agent'];

				if ($uisPay>1) $Leve=5;
				if ($uisPay>1) $uisPay=1;
				if($rss['is_agent'] > 0){
					$uisPay = 2;    //报单中心
				}
				$bj = "style='background:". $kd_c[$uisPay] .";'";  //表格背景色
				$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:".$ji_c[$Leve].";font-weight:bold;'>";
				$MyYJ = "</td></tr>";
                $MyYJ .= "<tr><td class='tu_l' $bj>$Lo</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$Ro</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$SpareLo</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareRo</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$benqiLo</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiRo</td></tr>";
				$MyYJ .= "</table>";

	//			$Str = $StTab."<a href='". __URL__ ."/PuTao/ID/". $Rid ."'>会员编号：". $uUserID ."</a>". $MyYJ;
				$Str = $StTab."<a href='". __URL__ ."/Tree2/ID/". $Rid ."'>". $uUserID ."</a>". $MyYJ;
				$Str4C2 = "/FID/". $Rid ."'>点击注册</a>";

				if ($uisPay > 0){
					if ($Yo <= $Nums + 1 && $i>0){
						$TreeArray[$Uo] = $Str4C0. $Str4C1 ."0". $Str4C2 . $Str4C4;
						$TreeArray[$Yo] = $Str4C0. $Str4C1 ."1". $Str4C2 . $Str4C4;
					}
				}else{
					if ($Yo<=$Nums+1 && $i>0){
						$TreeArray[$Uo]=$Str4C0.$Str4C1."0".$Str4C2.$Str4C4;
						//$TreeArray[$Yo]=$Str4C0.$Str4C1."1".$Str4C2.$Str4C4;
					}
				}
				$TreeArray[$k] = $Str;
			if ($upLevel < $pLevel + $uLev){
	//查出来的下级的绝对层	 //上级的绝对层,显示层数
				$this->Tree2_MtKass($Rid, $k, $pLevel, $uLev, $Str4C0, $Str4C1, $Str4C4,  $TreeArray, $Nums, $ColorUA);
			}
			}

		}
	}
	//双轨图----生成表格内容
	private function Tree2_showTree($uLev,$TreeArray,&$wop){
					       //显示层高,所有空位表格,空
		for ($i = 1;$i <= $uLev;$i++){
			$Nums = $Nums + pow(2,$i);    //要显示用户的数量
		}
		$wid = 12;
		$arr = array();
		global $arrs;
		$arrs = array();

		for ($i = 0;$i <= $Nums;$i++){
			$arr[$i] = $TreeArray[$i];
		}

		$arrs[0][0] = $arr;

		for ($i = 1;$i <= $uLev;$i++){
			for ($u = 1 ; $u <= pow(2,($i-1)) ; $u++){
				$yyyo = $arrs[$i-1][$u-1];
				$ta = array();
				$tar = count($yyyo);
				//echo $tar."<br>";
				for ($ti = 0 ; $ti < $tar ; $ti++){
					$ta[$ti] = $yyyo[$ti+1];
				}
				$to    = floor($tar/2)-1;
				$tarr1 = array();
				$tarr2 = array();

				for ($tj = 0 ; $tj <= $to ; $tj++){
					$tarr1[$tj] = $ta[$tj];
					$tarr2[$tj] = $ta[$to+$tj+1];
				}
				$arrs[$i][($u-1)*2]   = $tarr1;
				$arrs[$i][($u-1)*2+1] = $tarr2;
			}
		}

		$lhe = 20;//行高
		$tps = __PUBLIC__ .'/Images/tree/';
		$strL = "<img src='". $tps ."t_tree_bottom_l.gif' height='". $lhe ."'><img src='". $tps ."t_tree_line.gif' width='25%' height='". $lhe ."'><img src='". $tps . "t_tree_top.gif' height='". $lhe ."' alt='顶层'><img src='". $tps ."t_tree_line.gif' width='25%' height='". $lhe ."'><img src='". $tps ."t_tree_bottom_r.gif' height='". $lhe ."'>";

		$strW = "<img width='" . $wid . "' height='0'><br />";

        $wop = '';

		for ($i = 0  ;  $i <= $uLev  ;  $i++){
			$wop .= "<table width='100%' border='0' cellpadding='1' cellspacing='1'>";
			for ($t = 0  ;  $t <= 1  ;  $t++){
				if ($t != 1 || $i != $uLev){
					$wop.="<tr align='center'>";
					$oop= pow(2,$i)-1;
					for ($j = 0  ;  $j <= $oop ;  $j++){
						$eop=100/pow(2,$i);
						if ($t==1){
							$wop.="<td class='borderno' width='". $eop ."%' valign='top'>";
							$wop.=$strL;
						}else{
							$bcxx=$arrs[$i][$j][0];
							$rp=$i+1;
							$wop.="<td class='borderlrt' width='". $eop ."%' valign='top' title='第" . $rp . "层'>";
							$wop.=$strW;
							$wop.=$bcxx;
						}
						$wop.="</td>";
					}
					$wop.="</tr>";
				}
			}
			$wop.="</table>";
		}
		$wop.="</td></tr></table>";
	}

	public function todayindan($uid=0,&$danL=0,&$danR=0){
		$fck=M('fck');
		$dayt=strtotime(date('Y-m-d'));
		$tomt=strtotime(date('Y-m-d'))+3600*24;
		$insql=' and pdt>='.$dayt.' and pdt<'.$tomt;
		$where_r['id']=$uid;
		$rs=$fck->where($where_r)->find();
		if($rs){
			$rs_l=$fck->where('father_id='.$uid.' and treeplace=0')->field('id')->find();
			if($rs_l){
				$lid=$rs_l['id'];
				$suml=$fck->where('(id='.$lid.' or p_path like "%'.$lid.'%") and is_pay=1'.$insql)->sum('f4');
				if($suml!=false){
					$danL=$suml;
				}
			}else{
				$danL=0;
			}

			$rs_r=$fck->where('father_id='.$uid.' and treeplace=1')->field('id')->find();
			if($rs_r){
				$rid=$rs_r['id'];
				$sumr=$fck->where('(id='.$rid.' or p_path like "%'.$rid.'%") and is_pay=1'.$insql)->sum('f4');
				if($sumr!=false){
					$danR=$sumr;
				}
			}else{
				$danR=0;
			}
		}else{
			return;
		}
	}

	//  三轨图
	public function Tree3(){
		$this->_checkUser();
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称

		$fck   =  M ("fck");
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$myid = $_SESSION[C('USER_AUTH_KEY')];
		$UID = (int) $_GET['ID'];
		if (empty($UID)) $UID = $id;
			$UserID=$_POST['UserID'];
			if (!empty($UserID)){
			if (strlen($UserID)>10 ){
				$this->error( "错误操作！");
				exit;
			}
			//$where = "p_path like '%,". $UID .",%' and (user_id='". $UserID ."' or nickname='". $UserID ."') ";  //帐号的昵称都可以查询
			$where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
			$field ='*';
			$rs = $fck ->where($where)->field($field)->find();
			//dump($fck->getLastSql());
			//exit;
			if($rs==false){
				$this->error('没有该用户1!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}
		$_SESSION['showUID'] = $UID;
		$where =array();
		$where['id'] = $UID;
		$field ='*';
		$rs = $fck ->where($where)->field($field)->find();
		if (!$rs){
			$this->error('没有该用户2!');
			exit();
		}else{
			$ID			= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
			$FatherID	= $rs['father_id'];    //安置人ID
			$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
			$uLevel		= $rs['u_level'];      //级别
			$pPath		= $rs['p_path'];       //自已的路径
			$pLevel		= $rs['p_level'];	  //层数(数字)
			$L			= $rs['l'];
			$R			= $rs['r'];
			$LR			= $rs['lr'];
			$SpareL			= $rs['shangqi_l'];
			$SpareR			= $rs['shangqi_r'];
			$SpareLR			= $rs['shangqi_lr'];
			$benqiL			= $rs['benqi_l'];
			$benqiR			= $rs['benqi_r'];
			$benqiLR			= $rs['benqi_lr'];
		}
		if($myid==$ID)$FatherID=$myid;
		if ($isPay>1) $isPay=1;
		if($rs['is_agent'] > 1){
			$isPay = 2;    //报单中心颜色
		}

		//显示层数
		$uLev = (int) $_GET['uLev'];		//$Lev 记录显示层数
		if (is_numeric($uLev) == false) $uLev = $_SESSION['uLev3'];
		if (is_numeric($uLev) == false) $uLev = 2;
		if ($uLev < 2 || $uLev > 11)    $uLev = 2;
		$_SESSION['uLev3']=$uLev;
		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(3,$i);
		}
		global $TreeArray;
		$TreeArray=array();

		for ($i=0;$i<=$Nums;$i++){
			$TreeArray[$i]="<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位 </td></tr></table>";
		}
		$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
		$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
		$MyYJ = "</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>$SpareR</td><td class='tu_r' $bj>$SpareLR</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>$benqiR</td><td class='tu_r' $bj>$benqiLR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
		$MyYJ .= "</table>";

		$ZiJi   = $StTab."<a href='#'>". $UserID ."</a>". $MyYJ;
		$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
		$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/TPL/";
		$Str4C4 = "</td></tr></table>";

		if ($isPay>0){
			$i=pow(3,$uLev);
			$j=($i+1)/2;
			$TreeArray['1']=$Str4C0.$Str4C1."0/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray[$j]=$Str4C0.$Str4C1."1/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray[$i]=$Str4C0.$Str4C1."2/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}else{
			$TreeArray['1']=$Str4C0.$Str4C1."0/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$j]=$Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$i]=$Str4C0.$Str4C1."2/FID/".$ID."' target='_self'>点击注册</a>".$Str4
		}

		$wop = '';
		$TreeArray['0']=$ZiJi;
		$this->Tree3_MtKass($UID,0,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
		$this->Tree3_showTree($uLev,$TreeArray,$wop);

		$fee_rs = M('fee')->field('s10')->find();
		$Level = explode('|',$fee_rs['s10']);
		$this->assign('ColorUA',$ji_c);
		$this->assign('TU_Color',$kd_c);
		$this->assign('TU_MiCheng',$mi_c);
		$this->assign('UID',$UID);
		$this->assign('uLev',$uLev);
		$this->assign('FatherID',$FatherID);
		$this->assign('wop',$wop);
		$this->display();
	}  // end function

	//  三轨图---生成下层会员内容
	private function Tree3_MtKass($FatherID,$iL,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,&$TreeArray,$Nums){
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称
		if (!empty($FatherID)){
			$fck = M('fck');
			$where = array();
			$where = "father_id=". $FatherID ." and p_level-". $pLevel ."<=". $uLev ." And treeplace<3 order by treeplace asc";
			$field = '*';
			$rss = $fck->where($where)->field($field)->select();
			//dump($rss);
			foreach($rss as $rs){
				if ($rs['treeplace']==0){
					$k=$iL+1;
				}elseif($rs['treeplace']==1){
					$i=($pLevel+$uLev)-$rs['p_level']+1;
					$j=pow(3,$i);
					$k=($j+1)/2+$iL;
				}else{
					$i=($pLevel+$uLev)-$rs['p_level']+1;
					$j=pow(3,$i);
					$k=$j+$iL;
				}

				$i=($pLevel+$uLev)-$rs['p_level'];
				$Uo=$k+1;   //  1线
				$To=pow(3,$i)+$k;  //  3线
				$Yo=($Uo+$To)/2;   //  2线

				$Rid		= $rs['id'];
				$UserID		= $rs['user_id'];
				$NickName	= $rs['nickname'];
				$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
				$FatherID	= $rs['father_id'];    //安置人ID
				$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
				$uLevel		= $rs['u_level'];      //级别
				$upLevel	= $rs['p_level'];	  //层数(数字)
				$L			= $rs['l'];
				$R			= $rs['r'];
				$LR			= $rs['lr'];

				$L			= $rs['l'];
				$R			= $rs['r'];
				$LR			= $rs['lr'];
				$SpareL		= $rs['shangqi_l'];
				$SpareR		= $rs['shangqi_r'];
				$SpareLR	= $rs['shangqi_lr'];
				$benqiL		= $rs['benqi_l'];
				$benqiR		= $rs['benqi_r'];
				$benqiLR	= $rs['benqi_lr'];


				if ($isPay>1) $isPay=1;
				if($rs['is_agent'] > 1){
					$isPay = 2;    //报单中心颜色
				}

				$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
				$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
				$MyYJ  = "</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>$SpareR</td><td class='tu_r' $bj>$SpareLR</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>$benqiR</td><td class='tu_r' $bj>$benqiLR</td></tr>";
				$MyYJ .= "</table>";

				$Str=$StTab."<a href='".__URL__."/Tree3/ID/".$Rid."'>".$UserID."</a>".$MyYJ;
				$Str4C2="/RID/". $Rid ."/FID/".$Rid."' target='_self'>点击注册</a>";

				if ($isPay > 0){
					if ($Yo<=$Nums+1 && $i>0){
						$TreeArray[$Uo]=$Str4C0.$Str4C1."0".$Str4C2.$Str4C4;
						$TreeArray[$Yo]=$Str4C0.$Str4C1."1".$Str4C2.$Str4C4;
						$TreeArray[$To]=$Str4C0.$Str4C1."2".$Str4C2.$Str4C4;
					}
				}else{
					if ($Yo<=$Nums+1 && $i>0){
					$TreeArray[$Uo]=$Str4C0.$Str4C1."0".$Str4C2.$Str4C4;
					//$TreeArray[$Yo]=$Str4C0.$Str4C1."1".$Str4C2.$Str4C4;
					//$TreeArray[$To]=$Str4C0.$Str4C1."2".$Str4C2.$Str4C4;
					}
				}
				$TreeArray[$k]=$Str;
				if ($upLevel < $pLevel + $uLev){
					$this->Tree3_MtKass($Rid,$k,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
				}
			}  //end for
		}  //end if
	}  //end function

	// 三轨图----生成表格内容
	private function Tree3_showTree($uLev,$TreeArray,&$wop){
		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(3,$i);
		}
		$arr=array();
		global $arrs;
		$arrs=array();
		for ($i=0;$i<=$Nums;$i++){
			$arr[$i]=$TreeArray[$i];
		}
		$arrs[0][0]=$arr;
		for ($i=1;$i<=$uLev;$i++){
			for ($u = 1 ; $u <= pow(3,($i-1)) ; $u++){
				$yyyo=$arrs[$i-1][$u-1];
				$ta=array();
				$tar=count($yyyo);
				for ($ti=0 ; $ti<$tar ; $ti++){
					$ta[$ti] = $yyyo[$ti+1];
				}
				$to=floor($tar/3)-1;
				$tarr1=array();
				$tarr2=array();
				$tarr3=array();
				for ($tj=0 ; $tj<=$to ; $tj++){
					$tarr1[$tj] = $ta[$tj];
					$tarr2[$tj] = $ta[$to+$tj+1];
					$tarr3[$tj] = $ta[2*$to+$tj+2];
				}
				$sq=($u-1)*3;
				$arrs[$i][$sq] = $tarr1;
				$arrs[$i][$sq+1] = $tarr2;
				$arrs[$i][$sq+2] = $tarr3;
			}
		}
		$wid = '33%';
		$lhe = 30;
		$tps = __ROOT__.'/public/Images/tree/';
		$strL = "<img src='".$tps."t_tree_bottom_l.gif' height='".$lhe."'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_mid.gif' height='".$lhe."' alt='顶层'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom_r.gif' height='".$lhe."'>";
        $wop="";
		for ($i = 0  ;  $i <= $uLev  ;  $i++){
			$wop.="<table width='100%' border='0' cellpadding='1' cellspacing='1'>";
			for ($t = 0  ;  $t <= 1  ;  $t++){
				if ($t != 1 or $i != $uLev){
					$wop.="<tr align='center'>";
					$oop= pow(3,$i)-1;
					for ($j = 0  ;  $j <= $oop ;  $j++){
						$eop=100/pow(3,$i);
						if ($t==1){
							$wop.="<td class='borderno' width='". $eop ."%' valign='top'>";
							$wop.=$strL;
						}else{
							$bcxx=$arrs[$i][$j][0];
							$rp=$i+1;
							$wop.="<td class='borderlrt' width='". $eop ."%' valign='top' title='第" . $rp . "层'>";
							$wop.=$strW;
							$wop.=$bcxx;
							$wop.="</td>";
						}
						$wop.="</td>";
					}
					$wop.="</tr>";
				}
			}
			$wop.="</table>";
		}
		$wop.="</td></tr></table>";
	}


	//  四轨图
	public function Tree4(){
		$this->_checkUser();
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称

		$fck   =  M ("fck");
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$UID = (int) $_GET['ID'];
		if (empty($UID)) $UID = $id;
			$UserID=$_POST['UserID'];
			if (!empty($UserID)){
			if (strlen($UserID)>10 ){
				$this->error( "错误操作！");
				exit;
			}
			//$where = "p_path like '%,". $UID .",%' and (user_id='". $UserID ."' or nickname='". $UserID ."') ";  //帐号的昵称都可以查询
			$where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
			$field ='*';
			$rs = $fck ->where($where)->field($field)->find();
			//dump($fck->getLastSql());
			//exit;
			if($rs==false){
				$this->error('没有该用户1!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}
		$_SESSION['showUID'] = $UID;
		$where =array();
		$where['id'] = $UID;
		$field ='*';
		$rs = $fck ->where($where)->field($field)->find();
		if (!$rs){
			$this->error('没有该用户2!');
			exit();
		}else{
			$ID			= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
			$FatherID	= $rs['father_id'];    //安置人ID
			$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
			$uLevel		= $rs['u_level'];      //级别
			$pPath		= $rs['p_path'];       //自已的路径
			$pLevel		= $rs['p_level'];	  //层数(数字)
			$L			= $rs['l'];
			$R			= $rs['r'];
			$LR			= $rs['lr'];
		}
		if ($isPay>1) $isPay=1;
		if($rs['is_agent'] > 1){
			$isPay = 2;    //报单中心颜色
		}

		//显示层数
		$uLev = (int) $_GET['uLev'];		//$Lev 记录显示层数
		$uLev = 1;
		if (is_numeric($uLev) == false) $uLev = $_SESSION['uLev3'];
		if (is_numeric($uLev) == false) $uLev = 1;
		if ($uLev < 1 || $uLev > 11)    $uLev = 1;
		$_SESSION['uLev3']=$uLev;

		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(4,$i);
		}
		global $TreeArray;
		$TreeArray=array();

		for ($i=0;$i<=$Nums;$i++){
			$TreeArray[$i]="<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位 </td></tr></table>";
		}
		$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
		$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
		$MyYJ = "</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
		$MyYJ .= "</table>";

		$ZiJi   = $StTab."<a href='#'>". $UserID ."</a>". $MyYJ;
		$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
		$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/TPL/";
		$Str4C4 = "</td></tr></table>";

		if ($isPay>0){
			//$i=pow(4,$uLev);
			//$j=($i+1)/2;
			$TreeArray['1']=$Str4C0.$Str4C1."0/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['2']=$Str4C0.$Str4C1."1/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['3']=$Str4C0.$Str4C1."2/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['4']=$Str4C0.$Str4C1."3/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}else{
			//$TreeArray['1']=$Str4C0.$Str4C1."0/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$j]=$Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$i]=$Str4C0.$Str4C1."2/FID/".$ID."' target='_self'>点击注册</a>".$Str4
		}

		$wop = '';
		$TreeArray['0']=$ZiJi;
		$this->Tree4_MtKass($UID,0,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
		$this->Tree4_showTree($uLev,$TreeArray,$wop);

		$Level = explode('|',C("Member_Level"));
		$this->assign('Level',$Level);
		$this->assign('ColorUA',$ji_c);
		$this->assign('TU_Color',$kd_c);
		$this->assign('TU_MiCheng',$mi_c);
		$this->assign('UID',$UID);
		$this->assign('uLev',$uLev);
		$this->assign('FatherID',$FatherID);
		$this->assign('wop',$wop);
		$this->display('Tree4');
	}  // end function

	//  四轨图---生成下层会员内容
	private function Tree4_MtKass($FatherID,$iL,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,&$TreeArray,$Nums){
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称
		if (!empty($FatherID)){
			$fck = M('fck');
			$where = array();
			$where = "father_id=". $FatherID ." and p_level-". $pLevel ."<=". $uLev ." And treeplace<4 order by treeplace asc";
			$field = '*';
			$rss = $fck->where($where)->field($field)->select();
			//dump($rss);
			foreach($rss as $rs){
				$Rid		= $rs['id'];
				$UserID		= $rs['user_id'];
				$NickName	= $rs['nickname'];
				$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
				$FatherID	= $rs['father_id'];    //安置人ID
				$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
				$uLevel		= $rs['u_level'];      //级别
				$upLevel	= $rs['p_level'];	  //层数(数字)
				$L			= $rs['l'];
				$R			= $rs['r'];
				$LR			= $rs['lr'];
				if ($isPay>1) $isPay=1;
				if($rs['is_agent'] > 1){
					$isPay = 2;    //报单中心颜色
				}

				if ($TreePlace == 0){
					$k = 1;
				}elseif ($TreePlace == 1){
					$k = 2;
				}elseif ($TreePlace == 2){
					$k = 3;
				}elseif ($TreePlace == 3){
					$k = 4;
				}


				$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
				$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
				$MyYJ  = "</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
				$MyYJ .= "</table>";

				$Str=$StTab."<a href='".__URL__."/Tree4/ID/".$Rid."'>".$UserID."</a>".$MyYJ;

				$TreeArray[$k]=$Str;
				if ($upLevel < $pLevel + $uLev){
					$this->Tree3_MtKass($Rid,$k,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
				}
			}  //end for
		}  //end if
	}  //end function

	//  四轨图----生成表格内容
	private function Tree4_showTree($uLev,$TreeArray,&$wop){
		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(4,$i);
		}
		$arr=array();
		global $arrs;
		$arrs=array();
		for ($i=0;$i<=$Nums;$i++){
			$arr[$i]=$TreeArray[$i];
		}
		$arrs[0][0]=$arr;
		for ($i=1;$i<=$uLev;$i++){
			for ($u = 1 ; $u <= pow(4,($i-1)) ; $u++){
				$yyyo=$arrs[$i-1][$u-1];
				$ta=array();
				$tar=count($yyyo);
				for ($ti=0 ; $ti<$tar ; $ti++){
					$ta[$ti] = $yyyo[$ti+1];
				}
				$to=floor($tar/4)-1;
				$tarr1=array();
				$tarr2=array();
				$tarr3=array();
				$tarr4=array();
				for ($tj=0 ; $tj<=$to ; $tj++){
					$tarr1[$tj] = $ta[$tj];
					$tarr2[$tj] = $ta[$to+$tj+1];
					$tarr3[$tj] = $ta[2*$to+$tj+2];
					$tarr4[$tj] = $ta[3*$to+$tj+3];
				}
				$sq=($u-1)*4;
				$arrs[$i][$sq] = $tarr1;
				$arrs[$i][$sq+1] = $tarr2;
				$arrs[$i][$sq+2] = $tarr3;
				$arrs[$i][$sq+3] = $tarr4;
			}
		}
		$wid = '25%';
		$lhe = 8;
		$tps = __ROOT__.'/public/Images/tree4/';

		$strL = "<img src='".$tps."/t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'>";


		//$strL = "<img src='".$tps."t_tree_bottom.gif' height='".$lhe."'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif' height='".$lhe."'><img src='".$tps."t_tree_mid.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_line.gif' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif' height='".$lhe."'>";

        $wop="";
		for ($i = 0  ;  $i <= $uLev  ;  $i++){
			$wop.="<table width='100%' border='0' cellpadding='1' cellspacing='1'>";
			for ($t = 0  ;  $t <= 1  ;  $t++){
				if ($t != 1 or $i != $uLev){
					$wop.="<tr align='center'>";
					$oop= pow(4,$i)-1;
					for ($j = 0  ;  $j <= $oop ;  $j++){
						$eop=100/pow(4,$i);
						if ($t==1){
							$wop.="<td class='borderno' width='". $eop ."%' valign='top'>";
							$wop.=$strL;
						}else{
							$bcxx=$arrs[$i][$j][0];
							$rp=$i+1;
							$wop.="<td class='borderlrt' width='". $eop ."%' valign='top' title='第" . $rp . "层'>";
							$wop.=$strW;
							$wop.=$bcxx;
							$wop.="</td>";
						}
						$wop.="</td>";
					}
					$wop.="</tr>";
				}
			}
			$wop.="</table>";
		}
		$wop.="</td></tr></table>";
	}


	//  五轨图
	public function Tree5(){
		$this->_checkUser();
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称

		$fck   =  M ("fck");
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$UID = (int) $_GET['ID'];
		if (empty($UID)) $UID = $id;
			$UserID=$_POST['UserID'];
			if (!empty($UserID)){
			if (strlen($UserID)>10 ){
				$this->error( "错误操作！");
				exit;
			}
			//$where = "p_path like '%,". $UID .",%' and (user_id='". $UserID ."' or nickname='". $UserID ."') ";  //帐号的昵称都可以查询
			$where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
			$field ='*';
			$rs = $fck ->where($where)->field($field)->find();
			//dump($fck->getLastSql());
			//exit;
			if($rs==false){
				$this->error('没有该用户1!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}
		$_SESSION['showUID'] = $UID;
		$where =array();
		$where['id'] = $UID;
		$field ='*';
		$rs = $fck ->where($where)->field($field)->find();
		if (!$rs){
			$this->error('没有该用户2!');
			exit();
		}else{
			$ID			= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
			$FatherID	= $rs['father_id'];    //安置人ID
			$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
			$uLevel		= $rs['u_level'];      //级别
			$pPath		= $rs['p_path'];       //自已的路径
			$pLevel		= $rs['p_level'];	  //层数(数字)
			$L			= $rs['l'];
			$R			= $rs['r'];
			$LR			= $rs['lr'];
		}
		if ($isPay>1) $isPay=1;
		if($rs['is_agent'] > 1){
			$isPay = 2;    //报单中心颜色
		}

		//显示层数
		$uLev = (int) $_GET['uLev'];		//$Lev 记录显示层数
		$uLev = 1;
		if (is_numeric($uLev) == false) $uLev = $_SESSION['uLev3'];
		if (is_numeric($uLev) == false) $uLev = 1;
		if ($uLev < 1 || $uLev > 11)    $uLev = 1;
		$_SESSION['uLev3']=$uLev;

		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(5,$i);
		}
		global $TreeArray;
		$TreeArray=array();

		for ($i=0;$i<=$Nums;$i++){
			$TreeArray[$i]="<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位 </td></tr></table>";
		}
		$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
		$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
		$MyYJ = "</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
		//$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
		$MyYJ .= "</table>";

		$ZiJi   = $StTab."<a href='#'>". $UserID ."</a>". $MyYJ;
		$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
		$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/TPL/";
		$Str4C4 = "</td></tr></table>";

		if ($isPay>0){
			$TreeArray['1']=$Str4C0.$Str4C1."0/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['2']=$Str4C0.$Str4C1."1/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['3']=$Str4C0.$Str4C1."2/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['4']=$Str4C0.$Str4C1."3/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$TreeArray['5']=$Str4C0.$Str4C1."4/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}else{
			//$TreeArray['1']=$Str4C0.$Str4C1."0/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$j]=$Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$TreeArray[$i]=$Str4C0.$Str4C1."2/FID/".$ID."' target='_self'>点击注册</a>".$Str4
		}

		$wop = '';
		$TreeArray['0']=$ZiJi;
		$this->Tree5_MtKass($UID,0,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
		$this->Tree5_showTree($uLev,$TreeArray,$wop);

		$Level = explode('|',C("Member_Level"));
		$this->assign('Level',$Level);
		$this->assign('ColorUA',$ji_c);
		$this->assign('TU_Color',$kd_c);
		$this->assign('TU_MiCheng',$mi_c);
		$this->assign('UID',$UID);
		$this->assign('uLev',$uLev);
		$this->assign('FatherID',$FatherID);
		$this->assign('wop',$wop);
		$this->display('Tree5');
	}  // end function

	//  五轨图---生成下层会员内容
	private function Tree5_MtKass($FatherID,$iL,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,&$TreeArray,$Nums){
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称
		if (!empty($FatherID)){
			$fck = M('fck');
			$where = array();
			$where = "father_id=". $FatherID ." and p_level-". $pLevel ."<=". $uLev ."  order by treeplace asc";
			$field = '*';
			$rss = $fck->where($where)->field($field)->select();
			//dump($rss);
			foreach($rss as $rs){
				$Rid		= $rs['id'];
				$UserID		= $rs['user_id'];
				$NickName	= $rs['nickname'];
				$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
				$FatherID	= $rs['father_id'];    //安置人ID
				$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
				$uLevel		= $rs['u_level'];      //级别
				$upLevel	= $rs['p_level'];	  //层数(数字)
				$L			= $rs['l'];
				$R			= $rs['r'];
				$LR			= $rs['lr'];
				if ($isPay>1) $isPay=1;
				if($rs['is_agent'] > 1){
					$isPay = 2;    //报单中心颜色
				}

				$i=($pLevel+$uLev)-$upLevel;
				if ($TreePlace == 0){
					$k = $tL+1;
				}elseif ($TreePlace == 1){
					$j = 5^$i;
					$k = ($j+1)/2+$tL-1;
				}elseif ($TreePlace == 2){
					$i = $i+1;
					$j = 5^$i;
					$k = ($j+1)/2+$tL+1;
				}elseif ($TreePlace == 3){
					$i = $i+1;
					$j = 5^$i;
					$k = ($j+1)/2+$tL+2;
				}elseif ($TreePlace == 4){
					$i = $i+1;
					$j = 5^$i;
					$k = $j+$tL+1;
				}

				$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
				$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
				$MyYJ  = "</td></tr>";
				$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>$R</td><td class='tu_r' $bj>$LR</td></tr>";
				$MyYJ .= "</table>";

				$Str=$StTab."<a href='".__URL__."/Tree5/ID/".$Rid."'>".$UserID."</a>".$MyYJ;

				$TreeArray[$k]=$Str;
				if ($upLevel < $pLevel + $uLev){
					$this->Tree3_MtKass($Rid,$k,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,$TreeArray,$Nums);
				}
			}  //end for
		}  //end if
	}  //end function

	//  五轨图----生成表格内容
	private function Tree5_showTree($uLev,$TreeArray,&$wop){
		for ($i=1;$i<=$uLev;$i++){
			$Nums=$Nums+pow(5,$i);
		}
		$arr=array();
		global $arrs;
		$arrs=array();
		for ($i=0;$i<=$Nums;$i++){
			$arr[$i]=$TreeArray[$i];
		}
		$arrs[0][0]=$arr;
		for ($i=1;$i<=$uLev;$i++){
			for ($u = 1 ; $u <= pow(5,($i-1)) ; $u++){
				$yyyo=$arrs[$i-1][$u-1];
				$ta=array();
				$tar=count($yyyo);
				for ($ti=0 ; $ti<$tar ; $ti++){
					$ta[$ti] = $yyyo[$ti+1];
				}
				$to=floor($tar/5)-1;
				$tarr1=array();
				$tarr2=array();
				$tarr3=array();
				$tarr4=array();
				$tarr5=array();
				for ($tj=0 ; $tj<=$to ; $tj++){
					$tarr1[$tj] = $ta[$tj];
					$tarr2[$tj] = $ta[$to+$tj+1];
					$tarr3[$tj] = $ta[2*$to+$tj+2];
					$tarr4[$tj] = $ta[3*$to+$tj+3];
					$tarr5[$tj] = $ta[4*$to+$tj+4];
				}
				$sq=($u-1)*5;
				$arrs[$i][$sq] = $tarr1;
				$arrs[$i][$sq+1] = $tarr2;
				$arrs[$i][$sq+2] = $tarr3;
				$arrs[$i][$sq+3] = $tarr4;
				$arrs[$i][$sq+4] = $tarr5;
			}
		}
		$wid = '20%';
		$lhe = 8;
		$tps = __ROOT__.'/public/Images/Tree4/';

		$strL = "<img src='".$tps."/t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_mid.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'><img src='".$tps."t_tree_line.gif' width='".$wid."' height='".$lhe."'><img src='".$tps."t_tree_bottom.gif'>";

        $wop="";
		for ($i = 0  ;  $i <= $uLev  ;  $i++){
			$wop.="<table width='100%' border='0' cellpadding='1' cellspacing='1'>";
			for ($t = 0  ;  $t <= 1  ;  $t++){
				if ($t != 1 or $i != $uLev){
					$wop.="<tr align='center'>";
					$oop= pow(5,$i)-1;
					for ($j = 0  ;  $j <= $oop ;  $j++){
						$eop=100/pow(5,$i);
						if ($t==1){
							$wop.="<td class='borderno' width='". $eop ."%' valign='top'>";
							$wop.=$strL;
						}else{
							$bcxx=$arrs[$i][$j][0];
							$rp=$i+1;
							$wop.="<td class='borderlrt' width='". $eop ."%' valign='top' title='第" . $rp . "层'>";
							$wop.=$strW;
							$wop.=$bcxx;
							$wop.="</td>";
						}
						$wop.="</td>";
					}
					$wop.="</tr>";
				}
			}
			$wop.="</table>";
		}
		$wop.="</td></tr></table>";
	}




	//  直角图
	public function Tree6(){
		$this->_checkUser();
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称

		$fck   =  M ("fck");
		$id  = $_SESSION[C('USER_AUTH_KEY')];
		$UID = (int) $_GET['ID'];
		if (empty($UID)) $UID = $id;
			$UserID=$_POST['UserID'];
			if (!empty($UserID)){
			if (strlen($UserID)>10 ){
				$this->error( "错误操作！");
				exit;
			}
			//$where = "p_path like '%,". $UID .",%' and (user_id='". $UserID ."' or nickname='". $UserID ."') ";  //帐号的昵称都可以查询
			$where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
			$field ='*';
			$rs = $fck ->where($where)->field($field)->find();
			if($rs==false){
				$this->error('没有该用户1!');
				exit();
			}else{
				$UID = $rs['id'];
			}
		}
		$_SESSION['showUID'] = $UID;
		$where =array();
		$where['id'] = $UID;
		$field ='*';
		$rs = $fck ->where($where)->field($field)->find();
		if (!$rs){
			$this->error('没有该用户2!');
			exit();
		}else{
			$ID			= $rs['id'];
			$UserID		= $rs['user_id'];
			$NickName	= $rs['nickname'];
			$TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
			$FatherID	= $rs['father_id'];    //安置人ID
			$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
			$uLevel		= $rs['u_level'];      //级别
			$pPath		= $rs['p_path'];       //自已的路径
			$pLevel		= $rs['p_level'];	  //层数(数字)
			$L			= $rs['l'];
			$R			= $rs['r'];
			$benqiL		= $rs['benqi_l'];//本期新增
			$benqiR		= $rs['benqi_r'];
			$SpareL		= $rs['shangqi_l'];//上期剩余
			$SpareR		= $rs['shangqi_r'];

		}
		if ($isPay>1) $isPay=1;
		if($rs['is_agent'] > 1){
			$isPay = 2;    //报单中心颜色
		}

		global $TreeArray;
		$TreeArray=array();

		for ($i=0;$i<=10;$i++){
			$TreeArray[$i]="<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位 </td></tr></table>";
		}
		$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
		$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
		$MyYJ = "</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$R</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
		$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
		$MyYJ .= "</table>";

		$ZiJi   = $StTab."<a href='#'>". $UserID ."</a>". $MyYJ;
		$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
		$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/TPL/";
		$Str4C4 = "</td></tr></table>";

		$wop = array();
		if ($isPay>0){
			$wop['1']=$Str4C0.$Str4C1."0/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			$wop['2']=$Str4C0.$Str4C1."1/RID/". $ID ."/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}else{
			//$wop['1']=$Str4C0.$Str4C1."0/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
			//$wop['2']=$Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
		}

		$TreeArray[0] = $ZiJi;
		$Zid = 0;
		$this->Tree6_MtKass($ID,0,$TreeArray,1,$Zid);
		if($Zid > 0){
			$Cip = $Zid;
			$Zid = 0;
			$this->Tree6_MtKass($Cip,0,$TreeArray,2,$Zid);
			if($Zid > 0){
				$Vip = $Zid;
				$Zid = 0;
				$this->Tree6_MtKass($Vip,0,$TreeArray,3,$Zid);
				$this->Tree6_MtKass($Vip,1,$TreeArray,4,$Zid);
			}
			$this->Tree6_MtKass($Cip,1,$TreeArray,5,$Zid);
			if($Zid > 0){
				$Vip = $Zid;
				$Zid = 0;
				$this->Tree6_MtKass($Vip,0,$TreeArray,6,$Zid);
				$this->Tree6_MtKass($Vip,1,$TreeArray,7,$Zid);
			}
		}

		$this->Tree6_MtKass($ID,1,$TreeArray,8,$Zid);
		if($Zid > 0){
			$Cip = $Zid;
			$this->Tree6_MtKass($Cip,0,$TreeArray,9,$Zid);
			$this->Tree6_MtKass($Cip,1,$TreeArray,10,$Zid);
		}

		$Level = explode('|',C("Member_Level"));
		$this->assign('Level',$Level);
		$this->assign('ColorUA',$ji_c);
		$this->assign('TU_Color',$kd_c);
		$this->assign('TU_MiCheng',$mi_c);
		$this->assign('UID',$UID);
		$this->assign('uLev',$uLev);
		$this->assign('FatherID',$FatherID);
		$this->assign('wop',$TreeArray);
		$this->display('Tree6');
	}  // end function

	//  直角图---生成下层会员内容
	private function Tree6_MtKass($Pid,$LR,&$TreeArray,$Trr,&$Zid){
		$ji_c = $this->ji_Color();  //级别颜色
		$kd_c = $this->kd_Color();  //是否开通
		$mi_c = $this->Mi_Cheng();  //级别名称

		$fck = M('fck');
		$where = array();
		$where = "father_id=". $Pid ." And treeplace=".$LR." and treeplace<2";
		$field = '*';
        $rsc = $fck ->where($where)->field($field)->find();
		if($rsc){
			$ID			= $rsc['id'];
			$Zid        = $rsc['id'];
			$UserID		= $rsc['user_id'];
			$NickName	= $rsc['nickname'];
			//$TreePlace	= $rsc['treeplace'];   //区分左右 0为左边,1为右边
			//$FatherID	= $rsc['father_id'];    //安置人ID
			$isPay		= $rsc['is_pay'];		  //是否为正式(开通时为正式)
			$uLevel		= $rsc['u_level'];      //级别
			//$pPath		= $rsc['p_path'];       //自已的路径
			//$pLevel		= $rsc['p_level'];	  //层数(数字)
			$L			= $rsc['l'];
			$R			= $rsc['r'];
			$benqiL		= $rsc['benqi_l'];//本期新增
			$benqiR		= $rsc['benqi_r'];
			$SpareL		= $rsc['shangqi_l'];//上期剩余
			$SpareR		= $rsc['shangqi_r'];

			$bj = "style='background:". $kd_c[$isPay] ."';";  //表格背景色
			$StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[$uLevel] .";font-weight:bold;'>";
			$MyYJ = "</td></tr>";
			$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$R</td></tr>";
			$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
			$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新{$this->Trr}</td><td class='tu_r' $bj>$benqiR</td></tr>";
			$MyYJ .= "</table>";
			$Tree = $StTab."<a href='".__URL__."/Tree6/ID/".$ID."'>". $UserID ."</a>". $MyYJ;

			$TreeArray[$Trr] = $Tree;

		}else{
			$Zid=0;
			$Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
			$Str4C1 = "<a href='". __URL__ ."/KaiBoLuo/TPL/";
			$Str4C4 = "</td></tr></table>";
			$Tree = $Str4C0.$Str4C1.$LR."/RID/". $Pid ."/FID/".$Pid."' target='_self'>点击注册{$this->Trr}</a>".$Str4C4;
			$TreeArray[$Trr] = $Tree;

		} //end if($rsc)
	}  //end function




}
?>