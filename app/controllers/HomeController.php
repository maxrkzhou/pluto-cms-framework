<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
		return View::make('index');
	}
			
	public function __construct($dir){
			$this->$dir = $dir;
	}
	
	/*Display a list of the files/directory in current directory*/
	public function displayFiles($fileNames,$file){
		global $dir;
			
		$displayName = "";//display the title of each menu
		$classType = "";//classify the type for right clicked menu (e.g.forlder,file,back)
		$icon_folder = "<img src='img/folder.png' height='20' width='20'/>";
		$icon_back = "<img src='img/back.png' height='20' width='20'/>";
		$icon_file = "<img src='img/file.png' height='20' width='20'/>";
		
		echo '<ul class="nav">';
		foreach($fileNames as $key=>$fileName){
			if($this->isHiddenFile($fileName)||$this->linkCheck($fileName))continue;
			else if($fileName=="..") {$displayName = $icon_back." back"; $classType="back";}
			else if($this->fileCheck($fileName)) {$displayName = $icon_file." ".$fileName; $classType="file";}
			else {$displayName = $icon_folder." ".$fileName; $classType="folder";}
		?>
			<li><a href="#" id="<?php echo $fileName?>" class="<?php echo $classType ?>" onclick="intepreteDir('<?php echo $dir ?>','<?php echo $fileName?>')">
			<?php echo $displayName; ?></a></li>
		<?php 
		}
		echo '</ul>';
	}
	
	
	/*Check the current directory wether it is a file or directory*/
	public function isFile(){
		global $dir;
		
		if(filetype($dir)=="file")
			return true;	
		else
			return false;
	}

	
	
	
	
	
	
	
	
	
	
	
	/************************* Interner Functions ***********************************/
	
	
	
	private function isHiddenFile($fileName){
		$ignore = array( 'cgi-bin');
		
		if ($fileName=="..") return false;/* Only check the special case ".." */
		else if (!in_array($fileName,$ignore)&&substr($fileName, 0, 1) != '.') return false;        
		else return true;
	}

	private function linkCheck($file){
		global $dir;
		
		if(filetype($dir.'/'.$file)=="link")
			return true;	
		else
			return false;	
		
	}
	

	private function fileCheck($file){
		global $dir;
		
		if(filetype($dir.'/'.$file)=="file")
			return true;	
		else
			return false;	
	}
	
	




	
}
