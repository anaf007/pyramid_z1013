<?php
class CommonModel extends Model {

	// 获取当前用户的ID
    public function getMemberId() {
        return isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;
    }

   /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $options 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function forbid($options,$field='status'){

        if(FALSE === $this->where($options)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

}
?>