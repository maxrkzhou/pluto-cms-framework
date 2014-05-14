
<?php
header('Content-type: application/json; charset=UTF-8');
class FilesProcess extends Eloquent{
	
	/**
	 * Empty constructor for access methods
	 *
	 */
	public function __construct(){

	}


/***************************************************** Main Functions *********************************************/


	/**
	 * Save the data to the text file
	 * 
	 * @param $dir is the path of the file to be saved
	 * @param $webContent is the text data to be saved in a file 
	 *
	 * @return feedback message as an array to user
	 */		
	public function SaveFile($dir,$webContent){
		if(isset($dir,$webContent)) {
			if(file_put_contents($dir,$webContent)){
				$data = array(
					'status'=> true,
					'feedback'=> "Save Successfully."
				);
				return $data;
			}
			else{
				$data = array(
					'status'=> false,
					'feedback'=> "Error: Save Failed."
				);
				return $data;
			}
		}	
	}
	
	/**
	 * Create a directory under user specific path
	 * 
	 * @param $dir the path where to create the directory/folder
	 *  
	 * @return feedback message as an array to user
	 */		
	public function CreateFolder($dir){
		if(isset($dir)){
			if(!file_exists($dir)){
				if(!mkdir($dir,0755)){
					$data = array(
						'status'=> false,
						'feedback'=> "Error: Create Failed."
					);
					return $data;
				}
				else{
					$data = array(
						'status'=> true,
						'feedback'=> "Folder Successfully Created."
					);
					return $data;	
				}
			}
			else{
				$data = array(
					'status'=> false,
					'feedback'=> "Error: Folder exists."
				);
				return $data;			
			}
		}		
	}
	
	/**
	 * Create a text file under user specific path
	 * 
	 * @param $dir the path where to create the file
	 *  
	 * @return feedback message as an array to user
	 */	
	public function CreateFile($dir){
		if(isset($dir)){
			if(!file_exists($dir)){
				$fp = fopen($dir,"w");
				fclose($fp);
				$data = array(
					'status'=> true,
					'feedback'=> "File Successfully Created."
				);
				return $data;
			}
			else{
				$data = array(
					'status'=> false,
					'feedback'=> "Error: File exists."
				);
				return $data;		
			}
		}				
	}

	/**
	 * Change the name of a directory or a file
	 * 
	 * @param $dir the path where the srouce dirtectory/file is
	 * @param $name the target path, for this function the $dir and $name are both
	 *        paths for a specific folder/file except the name of the folder/file 
	 *        itself. For example, $dir = "/home/filename1" and $name is "/home/filename2" 
	 *
	 * @return feedback message as an array to user
	 */		
	public function RenameFile($dir,$name){
		if(isset($dir,$name)) {
			if(!file_exists($name)){
				return $this->HelperRename($dir,$name);
			}else{
				$data = array(
					'status'=> false,
					'feedback'=> "Warming: The name is already used in this location. 
					              Please change another name."
				);
				return $data;	
			}
		}	
	}

	/**
	 * Move the dirtectory/file from source location to target location
	 * 
	 * @param $src the path where the srouce dirtectory/file is
	 * @param $des the target location/path to move the folder/file  
	 *
	 * @return feedback message as an array to user
	 */		
	public function CutFile($src,$dst){
		if(isset($src,$dst)){
			if(!file_exists($dst)){
				if(is_dir($src)){
					$dir = opendir($src);
					@rename($src,$dst);
					closedir($dir);
					$data = array(
						'status'=> true,
						'feedback'=> "The action proceed successfully."
					);
					return $data;
				}else{
					return $this->HelperRename($src,$dst);
				}
			}
			else{
				$data = array(
					'status'=> false,
					'feedback'=> "Warming: The name is already used in this location. 
					              Do you want to overwrite the file?"
				);
				return $data;	
			}
		}
	}	

	/**
	 * Move the dirtectory/file from source location to target location
	 * 
	 * @param $src the path where the srouce dirtectory/file is
	 * @param $des the target location/path to move the folder/file  
	 *
	 * @return feedback message as an array to user
	 */		
	public function CopyFile($source,$dest) {
		if(!file_exists($dest)){
			if(is_file($source)){
				return $this->HelperCopy($source,$dest);
			}else{			
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
					$data = array(
						'status'=> true,
						'feedback'=> "The action proceed successfully."
					);
					return $data;
			}
		}
		else{
			$data = array(
				'status'=> false,
				'feedback'=> "Warming: The name is already used in this location. 
				              Do you want to overwrite the file?"
			);
			return $data;	
		}
	}

	/**
	 * Delete the dirtectory and all its sub-directory/files or files
	 * 
	 * @param $dir the path of the dirtectory/file to be deleted  
	 *
	 * @return feedback message as an array to user
	 */	
	public function DeleteFile($dir){
		if(isset($dir)){
			if(is_file($dir)){
				return $this->HelperDelete($dir);
			}else{
				$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
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
				if(!rmdir($dir)) {
					$data = array(
						'status'=> false,
						'feedback'=> "Error: Action Denied."
					);
					return $data;	
				}
				else{
					$data = array(
						'status'=> true,
						'feedback'=> "The action proceed successfully."
					);
					return $data;
				}
			}
		}
	}
	
/************************************************ Helper Functions ********************************************/
	
	private function HelperRename($src,$dst){
		
		if(rename($src, $dst)){
			$data = array(
				'status'=> true,
				'feedback'=> "The action proceed successfully."
			);
			return $data;
		}
		else{
			$data = array(
				'status'=> false,
				'feedback'=> "Error: Action Denied."
			);
			return $data;		
		}
	}
	
	private function HelperCopy($src,$dst){
		
		if(copy($src, $dst)){
			$data = array(
				'status'=> true,
				'feedback'=> "The action proceed successfully."
			);
			return $data;
		}
		else{
			$data = array(
				'status'=> false,
				'feedback'=> "Error: Action Denied."
			);
			return $data;		
		}
	}
	
	private function HelperDelete($dir){
		if(unlink($dir)){
			$data = array(
				'status'=> true,
				'feedback'=> "The action proceed successfully."
			);
			return $data;
		}
		else{
			$data = array(
				'status'=> false,
				'feedback'=> "Error: Action Denied."
			);
			return $data;		
		}
	}
	
}
?>