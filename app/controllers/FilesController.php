<?php

class FilesController extends \BaseController {
	
	protected $dir;
	protected $curFile;
	protected $fileNames;
	protected $fileContent;
	protected $fileAction;

	public function __construct(){
				
		if(Input::get('dir')!=NULL) $this->dir = Input::get("dir");
		else $this->dir = "/";
		$this->curFile = Input::get('file');	
		$this->initByAction(Input::get("action"));	
	}

	private function initByAction($action){
		if($action=="newfolder"){
			$this->fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");	
			$this->fileAction->CreateFolder();
		}
		if($action=="newfile"){
			$this->fileAction = new FilesProcess(Input::get("action"),Input::get("newdir"),"","","","","");	
			$this->fileAction->CreateFile();
		}
	}
	
	
	public function filterFileNames($dir){
		
		$fileNames = scandir($dir);
		$filterNames = array();
		$filterNames['back']='img/back.png';
		foreach($fileNames as $key=>$fileName){
				if($this->isHiddenFile($fileName)) continue;
				else if($this->fileCheck($fileName)) $filterNames[$fileName] = 'img/file.png';
				else $filterNames[$fileName] = 'img/folder.png';
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
		$data = array('dir'=>$dir, 'curFile'=>$curFile, 'fileNames'=>$fliterNames,'fileContent'=>$fileContent,'isFile'=>$this->isFile());
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