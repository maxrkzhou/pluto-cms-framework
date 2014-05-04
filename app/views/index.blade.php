@extends('master')
	
@section('header')
<a href="#"><img src="http://www.pluto.com.au/images/logowithtagline.png" alt="Insert Logo Here" name="Insert_logo" width="20%" height="90" id="Insert_logo" style="background-color: #8090AB; display:block;" /></a>
@stop

@section('sidebar')
    	<ul class="nav">

				@foreach($fileNames as $fileName => $fileImg)
            		<li>
                    	<a href="#" id="menu" onclick="intepreteDir('<?php echo $dir?>','<?php echo $fileName ?>')">
							{{HTML::image($fileImg,"",array('width'=>'20','height'=>'20'))}}
							{{$fileName}}
                        </a>
                    </li>
                @endforeach
        <ul>
@stop


@section('content')
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
@stop

@section('footer')
	<p>Pluto Technology</p>
@stop
