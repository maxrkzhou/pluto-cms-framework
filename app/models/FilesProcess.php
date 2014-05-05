
<?php

class FilesProcess extends Eloquent{

	protected $action;
	protected $dir;
	protected $plainContent;
	protected $webContent;
	protected $name;
	protected $sourceDir;
	protected $destDir;

/*
	public function __construct(){
		
		$action = Input::get("action");
		$dir = Input::get("dir");
		$plainContent = Input::get("plaincontent");
		$webContent = Input::get("webcontent");
		$name = Input::get("name");
		$sourceDir = Input::get("sourcedir");
		$destDir = Input::get("destdir");
	}
*/	
	public function __construct($action,$dir,$plainContent,$webContent,$name,$sourceDir,$destDir) {
		$this->action = $action;
		$this->dir = $dir;
		$this->plainContent = $plainContent;
		$this->webContent = $webContent;
		$this->name = $name;
		$this->sourceDir = $sourceDir;
		$this->destDir = $destDir;
	}

	public function temp(){
		
		switch($this->action){
			case "save":
				if(isset($this->dir,$this->webContent)) {
					file_put_contents($this->dir,$this->webContent);
					echo "save successfully";
				}
				break;
			case "rename":
				if(isset($this->dir,$this->name)) {
					if(!rename($this->dir, $this->name)) echo"rename failed!";
					else echo "Done!";
				}
				break;
			case "delete":
				if(isset($this->dir)){
					if(!unlink($this->dir)) echo"delete failed!";
					else echo "Done!";
				}
				break;
			case "copy":
				if(isset($this->sourceDir,$this->destDir)) {
					if(!copy($this->sourceDir,$this->destDir)) echo"copy failed!";
					else echo "Done!";
				}
				break;
			case "cut":
				if(isset($this->sourceDir,$this->destDir)){
					if(!rename($this->sourceDir,$this->destDir)) echo"cut failed!";
					else echo "Done!";
				}
				break;
			case "newfolder":
				if(isset($this->dir)){
					if(!file_exists($this->dir)){
						if(!mkdir($this->dir)) return "create failed!";
						else return "Done!";
					}
					else return "Create Failed";	
				}
				break;
			case "newfile":
				if(isset($this->dir)){
					if(!file_exists($this->dir)){
						$fp = fopen($$this->dir,"w");
						fclose($fp);
						echo "Done!";
					}
					else echo "Create Failed";	
				}				
				break;
			default:
				break;
		}
	}
	
	public function test1(){
		return "aaaa";
	}
	
	public function CreateFolder(){
		if(isset($this->dir)){
					if(!file_exists($this->dir)){
						if(!mkdir($this->dir)) return "create failed!";
						else return "Done!";
					}
			else return "Create Failed";	
		}		
	}
	
	public function CreateFile(){
		if(isset($this->dir)){
			if(!file_exists($this->dir)){
				$fp = fopen($$this->dir,"w");
				fclose($fp);
				return "Done!";
			}
			else return "Create Failed";	
			}				
	}
	
}
?>