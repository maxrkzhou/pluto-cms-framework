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
		$this->initByAction(Input::get("action"));	
	}
	/* __construct($action,$dir,$plainContent,$webContent,$name,$sourceDir,$destDir) */
	private function initByAction($action){
		
		if($action=="save"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),Input::get("plainontent"),Input::get("webcontent"),"","","");	
			$fileAction ->SaveFile();	
		}
		if($action=="newfolder"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");	
			if($fileAction ->CreateFolder()) $this->feedback ="Done!";
			else $this->feedback = "Folder Create Fail!";
		}
		if($action=="newfile"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");	
			$fileAction ->CreateFile();
		}
		if($action=="rename"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","",Input::get("name"),"","");
			$fileAction ->RenameFile();
		}
		if($action=="copy"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","",Input::get("sourcedir"),Input::get("destdir"));
			$fileAction ->CopyFile(Input::get("sourcedir"),Input::get("destdir"));	
		}
		if($action=="cut"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","",Input::get("sourcedir"),Input::get("destdir"));
			$fileAction ->CutFile(Input::get("sourcedir"),Input::get("destdir"));	
		}
		if($action=="deletefolder"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");
			$fileAction ->DeleteFolder();
		}
		if($action=="deletefile"){
			$fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");
			$fileAction ->DeleteFile();
		}
	}
	
	
	public function filterFileNames($dir){
		
		$fileNames = scandir($dir);
		$filterNames = array();
		$this->menuType = array();
		$filterNames['back']='img/back.png';
		$this->menuType['back'] = 'back';
		foreach($fileNames as $key=>$fileName){
				if($this->isHiddenFile($fileName)) continue;
				else if($this->fileCheck($fileName)){
					$filterNames[$fileName] = 'img/file.png'; 
					$this->menuType[$fileName] = 'file';
				}
				else{ 
					$filterNames[$fileName] = 'img/folder.png';
					$this->menuType[$fileName] = 'folder';
				}
		}
		
		return $filterNames;		
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