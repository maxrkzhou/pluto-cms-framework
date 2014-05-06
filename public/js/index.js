// JavaScript Document
var clickedFileName="";
var sourceFileName = "";
var sourceDir="";
var actionOnFile = "";



function saveFile(){
	var dir = $("#dir").val();
	var plainContent = CKEDITOR.instances.editor.document.getBody().getText();
	var webContent = CKEDITOR.instances.editor.getData();

  $.ajax({
	type: "GET",
	url: "/",
	//dataType:"json",
	data: {
		newdir: dir,
		action: "save",
		plainontent: plainContent,
		webcontent: webContent
	},
  	success:function(data){
		//alert("LOL");
		document.getElementById("feedback").innerHTML ="";
		$("#feedback").append(data);

  }});

}


/****************************************** Direction *******************************************************/
/****************************************** Main Function ***************************************************/

/*analysis the dirtectory and indicate the next path */
function intepreteDir(dir,curFile){
	var path = "";
	if(curFile=="back"){
		if(dir=="/") path = dir;/*if root correct the path with only one slash*/
		else path = truncatePath(dir); /*if not root simply add /+file at the end */
	}
	else{
		if(dir=="/") path = dir+curFile;/*if root correct the path with only one slash*/
		else path = dir+"/"+curFile; /*if not root simply add /+file at the end */
	}

	location.href="?dir="+path+"&file="+curFile;	
}

/****************************************** Direction *******************************************************/
/****************************************** Helper Function ***************************************************/


/*Truncate the dirtectory to return back the previous level 
  For example: current path:/home/pudge/bin, if use wants to go
  back to folder "pudge", this function will return /home/pudge
*/
function truncatePath(dir){
	
	var charLocation = dir.lastIndexOf("/");
	var trunDir = dir.substring(0,charLocation);
	if(trunDir=="") return "/"; /* return "/" where path goes back to root */
	else return trunDir; /* path is not root */

}


/****************************************** Right Click Menu **********************************************/
/****************************************** Layout Functions **********************************************/

/*Create a right click menu to the corresponding target: folder,file or back */
$(function(){
    $.contextMenu({
        selector: '.file', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
        },
        items: {
            "cut": {name: "Cut", icon: "cut", callback: function(key, opt){
				getCopiedDir("cut");
			}},
            "copy": {name: "Copy", icon: "copy", callback: function(key, opt){
				getCopiedDir("copy");
			}},
            "delete": {name: "Delete", icon: "delete",callback: function(key, opt){
				$( "#deleteBox" ).dialog({
						 buttons: { "Ok": function() { deleteFiles(); $(this).dialog("close"); },
									"Cancel": function(){$(this).dialog("close");} 
						} 
					});
			}},
			"rename": {name: "Rename", icon: "rename", callback: function(key, opt){
				$(function() {
					$( "#renameBox" ).dialog({
						 buttons: { "Ok": function() { renameFiles(); $(this).dialog("close");},
									"Cancel": function(){$(this).dialog("close");} 
						} 
					});
				});
			}},
            "sep1": "---------",
            "quit": {name: "Quit", icon: "quit"}
        }
    });
    

});

/*Right Clicked Menu for Folders */
$(function(){
    $.contextMenu({
        selector: '.folder', 
        callback: function(key, options) {
            var m = "clicked: " + key;
            window.console && console.log(m) || alert(m); 
        },
        items: {
            "cut": {name: "Cut", icon: "cut", callback: function(key, opt){
				getCopiedDir("cut");
			}},
            "copy": {name: "Copy", icon: "copy", callback: function(key, opt){
				getCopiedDir("copy");
			}},
            "paste": {name: "Paste", icon: "paste", callback: function(key, opt){
				if(actionOnFile=='copy') copyFiles();
				if(actionOnFile=='cut') moveFiles();		
			}},
            "delete": {name: "Delete", icon: "delete",callback: function(key, opt){
				$( "#deleteBox" ).dialog({
						 buttons: { "Ok": function() { deleteFolders(); $(this).dialog("close"); },
									"Cancel": function(){$(this).dialog("close");} 
						} 
					});
			}},
			"rename": {name: "Rename", icon: "rename", callback: function(key, opt){
				$(function() {
					$( "#renameBox" ).dialog({
						 buttons: { "Ok": function() { renameFiles(); $(this).dialog("close");},
									"Cancel": function(){$(this).dialog("close");} 
						} 
					});
				});
			}},
            "sep1": "---------",
            "quit": {name: "Quit", icon: "quit"}
        }
    });
    

});

/****************************************** Right Click Menu **********************************************/
/****************************************** Functionality Functions ***************************************/

function moveFiles(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+sourceFileName;
	$.ajax({
	type: "GET",
	url: "/",
	//dataType:"json",
	data: {
		action: "cut",
		sourcedir: sourceDir,
		destdir: destDir
	},
  	success:function(data){
		window.location.reload();
  }});		
}



function copyFiles(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+sourceFileName;
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "copy",
		sourcedir: sourceDir,
		destdir: destDir
	},
  	success:function(data){

  }});	
}


function renameFiles(){
	var dir = $("#dir").val()+"/"+clickedFileName;
	var name = $("#dir").val()+"/"+$("#rename").val();
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "rename",
		newdir: dir,
		name: name
	},
  	success:function(data){
		window.location.reload();
  }});	
}




function deleteFiles(){
	var dir = $("#dir").val()+"/"+clickedFileName;
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "deletefile",
		newdir: dir
	},
  	success:function(data){
		window.location.reload();
  }});	
	
}

function deleteFolders(){
	var dir = $("#dir").val()+"/"+clickedFileName;
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "deletefolder",
		newdir: dir
	},
  	success:function(data){
		//alert("LOL");
		//document.getElementById("feedback").innerHTML ="";
		//$("#feedback").append(data);
		window.location.reload();
  }});	
	
}
/****************************************** Right Click Menu ***************************************/
/****************************************** Helper Functions ***************************************/

function getCopiedDir(action){
	sourceDir = $("#dir").val()+"/"+clickedFileName;
	sourceFileName = clickedFileName;
	actionOnFile =	action;
}

function getFileName(fileName){
	clickedFileName = fileName;
}


/***************************************** Create Files *******************************************/
function createFiles(){
	$("#create").val(".txt");
	$( "#createBox" ).dialog({
		buttons: { "Ok": function() { sendFileName($("#create").val()); $(this).dialog("close"); },
		"Cancel": function(){$(this).dialog("close");} 
		} 
	});
}

function createFolders(){
	$("#createBox" ).dialog({
			 buttons: { "Ok": function() { sendFolderName($("#create").val()); $(this).dialog("close"); },
			"Cancel": function(){$(this).dialog("close");} 
		} 
	});
	
	
}

function sendFolderName(newFileName){

	var dir = $("#dir").val()+"/"+newFileName;
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "newfolder",
		newdir: dir
	},
  	success:function(data){
		window.location.reload();
  }});	
}

function sendFileName(newFileName){
	var dir = $("#dir").val()+"/"+newFileName;
	$.ajax({
	type: "GET",
	url: "/",
	data: {
		action: "newfile",
		newdir: dir
	},
  	success:function(data){
		//alert(data);
		window.location.reload();
  }});	
}