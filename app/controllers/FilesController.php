<?php
header('Content-type: application/json; charset=UTF-8');
?>

<?php

class FilesController extends \BaseController {
	
	protected $dir;
	protected $curFile;
	protected $fileNames;
	protected $fileContent;
	protected $feedback;
	protected $menuType;

	public function __construct(){
				
		if(Input::get('dir')!=NULL) $this->dir = Input::get("dir");
		else $this->dir = "/";
		$this->curFile = Input::get('file');
	}
	

	private function ActionOnFiles($action){
		
		$fileAction = new FilesProcess();	
		
		if($action=="save"){
			return $fileAction ->SaveFile(Input::get("newdir"),Input::get("webcontent"));			
		}
		if($action=="newfolder"){
			return $fileAction ->CreateFolder(Input::get("newdir"));
		}
		if($action=="newfile"){
			return $fileAction ->CreateFile(Input::get("newdir"));
		}
		if($action=="rename"){
			return $fileAction ->RenameFile(Input::get("newdir"),Input::get("name"));
		}
		if($action=="copy"){
			return $fileAction ->CopyFile(Input::get("sourcedir"),Input::get("destdir"));	
		}
		if($action=="delete"){
			return $fileAction ->DeleteFile(Input::get("newdir"));
		}
		if($action=="overwriteconfirm"){
			return $fileAction ->OverwriteConfirm(Input::get("destdir"));
		}
	}
	
	
	public function filterFileNames($dir){
		
		$fileNames = scandir($dir);
		$filterNames = array();
		$this->menuType = array();
		$filterNames['back']='img/back.png';
		$this->menuType['back'] = 'back';
		foreach($fileNames as $key=>$fileName){
				if($this->isHiddenFile($fileName)||$this->linkCheck($fileName)) continue;
				else if($this->fileCheck($fileName)){
					$filterNames[$fileName] = 'img/pdf-24_32.png'; 
					$this->menuType[$fileName] = 'file';
				}
				else{ 
					$filterNames[$fileName] = 'img/folder.png';
					$this->menuType[$fileName] = 'folder';
				}
		}
		
		return $filterNames;		
	}
	
	private function linkCheck($file){
		
		if(filetype($this->dir.'/'.$file)=="link")
			return true;	
		else
			return false;			
	}
	
	private function fileCheck($file){
		
		if(filetype($this->dir.'/'.$file)=="file")
			return true;	
		else
			return false;	
	}
	
	private function isHiddenFile($fileName){
		$ignore = array( 'cgi-bin', '.', '..','._' );
		if (!in_array($fileName,$ignore)&&substr($fileName, 0, 1) != '.') return false;        
		else return true;
	}
	
	/*Check the current directory wether it is a file or directory*/

	public function isFile(){

		if(filetype($this->dir)=="file")
			return true;	
		else
			return false;

	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	
		$dir = $this->dir;
		$curFile = $this->curFile;
		if($this->isFile()){
			$fileContent = file_get_contents($dir);
			$fliterNames = array();
			$fliterNames['back']='img/back.png';
		}
		else{			
			$fliterNames = $this->filterFileNames($dir);
			$fileContent = "";
		}
		$data = array('dir'=>$dir, 'curFile'=>$curFile, 'fileNames'=>$fliterNames,'menuType'=>$this->menuType,'fileContent'=>$fileContent,'isFile'=>$this->isFile());
		if(Request::ajax()){
			return Response::json($this->ActionOnFiles(Input::get("action")));
		}
		else
			//return Response::json(array('name' => 'Steve1', 'state' => 'CA1'));
			return View::make('index',$data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}