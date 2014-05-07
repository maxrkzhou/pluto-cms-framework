
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
	
	public function SaveFile(){
		if(isset($this->dir,$this->webContent)) {
			file_put_contents($this->dir,$this->webContent);
			echo "save successfully";
		}	
	}
	
	
	public function CreateFolder(){
		if(isset($this->dir)){
					if(!file_exists($this->dir)){
						if(!mkdir($this->dir)) return false;
						else return true;
					}
			else return false;	
		}		
	}
	
	public function CreateFile(){
		if(isset($this->dir)){
			if(!file_exists($this->dir)){
				$fp = fopen($this->dir,"w");
				fclose($fp);
				return true;
			}
			else return false;	
			}				
	}
	
	public function RenameFile(){
		if(isset($this->dir,$this->name)) {
			if(!rename($this->dir, $this->name)) echo"rename failed!";
			else echo "Done!";
		}	
	}
	/* has bug when go back to parent folder can't cut!*/
	public function CutFile($src,$dst){
		if(isset($src,$dst)){
			if(is_dir($src)){
				$dir = opendir($src);
				@rename($src,$dst);
				closedir($dir);
			}else{
				rename($src,$dst);
			}
		}
	}	
	/* has bug when go back to parent folder can't copy!*/
	public function CopyFile($source,$dest) {
				
		mkdir($dest, 0755);
		foreach (
		  $iterator = new RecursiveIteratorIterator(
		  new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
		  RecursiveIteratorIterator::SELF_FIRST) as $item) {
		  if ($item->isDir()) {
			mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
		  } else {
			copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
		  }
		}
	}

	public function DeleteFile(){
		if(isset($this->dir)){
			if(!unlink($this->dir)) echo"delete failed!";
			else echo "Done!";
		}
	}
	
	public function DeleteFolder(){
		if(isset($this->dir)){
			$it = new RecursiveDirectoryIterator($this->dir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it,
					 RecursiveIteratorIterator::CHILD_FIRST);
			foreach($files as $file) {
				if ($file->getFilename() === '.' || $file->getFilename() === '..') {
					continue;
				}
				if ($file->isDir()){
					rmdir($file->getRealPath());
				} else {
					unlink($file->getRealPath());
				}
			}
			if(!rmdir($this->dir)) echo"delete failed!";
			else echo "Done!";
		}
	}
}
?>