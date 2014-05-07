@extends('master')
	
@section('header')
<a href="#"><img src="http://www.pluto.com.au/images/logowithtagline.png" alt="Insert Logo Here" name="Insert_logo" width="20%" height="90" id="Insert_logo" style="background-color: #8090AB; display:block;" /></a>
@stop

@section('sidebar')
    	<ul class="nav">

				@foreach($fileNames as $fileName => $fileImg)
            		<li>
                    	<a href="#" id="{{$fileName}}" class="{{$menuType[$fileName]}}" onclick="intepreteDir('<?php echo $dir?>','<?php echo $fileName ?>')">
							{{HTML::image($fileImg,"",array('width'=>'20','height'=>'20'))}}
							{{$fileName}}
                        </a>
                    </li>
                @endforeach
        <ul>
@stop


@section('content')
	<input type="hidden" id="dir" value="<?php echo $dir;?>"/>
	<p>Current Location: {{$dir}}</p>
	@if($isFile)
	<h1>Text Editor Panel</h1>
 	<form>
        <p>
            <textarea name="editor" id="editor">{{$fileContent}}</textarea>
            <script>
                CKEDITOR.replace( 'editor' );
            </script>
        </p>
        <p>
            <input type="button" onclick="saveFile()" value="Save"/>
        </p>
    </form>	
    @else
    <h1>File Control Panel</h1>
		<table id="filepanel">
        <tr>
        <td><img src='img/folder.png' height='20' width='20'/><a href="#" onclick="createFolders()">New Folder</a></td>
        <td><img src='img/file.png' height='20' width='20'/><a href="#" onclick="createFiles()">New File</a></td>
        </tr>
        </table>
    @endif
    <div id="feedback"></div>
    
    		
    <div id="renameBox">
    <table>
    <tr><td>Enter the name you want to change:</td></tr>
    <tr><td colspan="2"><input type="text" id="rename" name="rename" /></td></tr>
    </table>
	</div>

    <div id="deleteBox">
    <table>
    <tr><td>Do you want to delete this file?</td></tr>
    </table>
    </div>

    <div id="createBox">
    <table>
    <tr><td>Enter Your File's/Folder's Name:</td></tr>
    <tr><td colspan="2"><input type="text" id="create" name="create" /></td></tr>
    </table>
    </div>
    
    
    <script>
		$('li').mousedown(function(event) {
    		switch (event.which) {
        		case 1://mouse left button
            		break;
        		case 2:
				//leave for mouse mid button
            		break;
        		case 3:
					getFileName(event.target.id);
  					$("#rename").val(event.target.id);	
            		break;
        		default:
            		alert('You have a strange mouse');
					break;
    			}
		});
</script>

@stop

@section('footer')
	<p>Pluto Technology</p>
@stop
