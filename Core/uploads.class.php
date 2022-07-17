<?php
class uploads{
	public $uploadDir='uploads/';
	public $allowExt=array('png','jpg','jpeg','gif');
	public $maxSize=500;
	public $fileName='';
	protected $fileExt='';
	
	public  function doing(){
		$this->checkError();
		$this->checkExt();
		$this->checkSize();
		return $this->moveFile();
	}
	protected  function checkError(){
		if(!isset($_FILES[$this->fileName])){
			die(json_encode( array('status'=>0,'url'=>'','msg'=>'没有选择文件或者文件太大，或者索引不正确')));
		}
		
		
		switch($_FILES[$this->fileName]['error']){
			case 1:
			$errorMsg='1超过了php.ini上传的大小';
			break;
			case 2:
			$errorMsg='2上传文件大小';
			break;
			case 3:
			$errorMsg='3文件只有部分被上传';
			break;
			case 4:
			$errorMsg='4没有文件被上传';
			break;
			case 6:
			$errorMsg='6找不到临时文件夹';
			break;
			case 7:
			$errorMsg='7文件写入失败';
			break;
			case 0:
			$errorMsg='';	
			break;
		}
		if(!empty($errorMsg)){
			die(json_encode( array('status'=>0,'url'=>'','msg'=>$errorMsg)));
		}
	}
	protected  function checkExt(){
            $info=pathinfo($_FILES[$this->fileName]['name']);
            $ext=$info['extension'];
            $this->fileExt=$ext;
            if(!in_array($ext,$this->allowExt)){
                    die(json_encode( array('status'=>0,'url'=>'','msg'=>'扩展名不支持，支持的扩展名有：'.implode(',',$this->allowExt))));
            }
	}
	protected  function checkSize(){
		if($this->maxSize*1024<$_FILES[$this->fileName]['size']){
			die(json_encode( array('status'=>0,'url'=>'','msg'=>'你的文件太大，只能上传小于'.$this->maxSize.'kb的文件。您的文件大小为：'.round($_FILES[$this->fileName]['size']/1024,2).'kb')));
		}
	}
	protected  function moveFile(){
		$newFileName=$this->uploadDir.$this->randStr().'.'.$this->fileExt;
	
		$r=move_uploaded_file($_FILES[$this->fileName]['tmp_name'],$newFileName);
		if($r){
			return array('status'=>1,'url'=>$newFileName,'msg'=>'上传成功');
		}else{
			die(json_encode( array('status'=>0,'url'=>'','msg'=>'上传失败')));
		}
	}


	protected  function randStr($len=32){
		$str='';
		for($i=1;$i<=$len;$i++){
			$which=mt_rand(1,3);
			switch($which){
				case 1:
				$ord=mt_rand(65,90);
				break;
				case 2:
				$ord=mt_rand(97,122);
				break;
				
				case 3:
				$ord=mt_rand(48,57);
				//$ord=ord($ord);
				break;
				
			}
			$str.=chr($ord);
		}
		return $str;
	}
}

?>