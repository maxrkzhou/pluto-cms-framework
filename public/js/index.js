// JavaScript Document
var clickedFileName="";
var sourceFileName = "";
var sourceDir="";
var actionOnFile = "";


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
	location.href=location.protocol + '//' + location.host + location.pathname+"?dir="+path+"&file="+curFile;	
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
	var src = $("#dir").val()+"/"+clickedFileName;
	var dst = $("#dir").val()+"/"+$("#rename").val();
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
						 buttons: { "Ok": function() {
							  				renameFiles(src,dst); 
											$(this).dialog("close");
							  			  },
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
				if($.cookie("actionOnFile")=='copy') //copyFiles();
					OverwriteConfirm();
				if($.cookie("actionOnFile")=='cut') OverwriteConfirmCut();		
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

/****************************************** Right Click Menu **********************************************/
/****************************************** Functionality Functions ***************************************/

function saveFile(){
	var dir = $("#dir").val();
	var plainContent = CKEDITOR.instances.editor.document.getBody().getText();
	var webContent = CKEDITOR.instances.editor.getData();

  $.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		newdir: dir,
		action: "save",
		webcontent: webContent
	},
  	success:function(data){
		feedbackBox(data);
  }});

}



function moveFiles(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+$.cookie("srcFile");
	$.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		action: "cut",
		sourcedir: $.cookie("srcDir"),
		destdir: destDir
	},
  	success:function(data){
		feedbackBox(data);
  }
   ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
  
  });		
}

function OverwriteConfirmCut(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+ $.cookie("srcFile");
	$.ajax({
	type: "GET",
	dataType:"json",
	url: "/",
	data: {
		action: "overwriteconfirm",
		destdir: destDir
	},
  	success:function(data){
		OverwirteBoxCut(data);
  }
  ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
  
  });	
}

function OverwirteBoxCut(data){
	if(data.status==false){
		$("#overwriteBox" ).dialog({
				open: function(){
					$("#contentholder1").append(data.feedback);
				},
				 buttons: { 
					"Ok": function(){
							copyFiles();
							deleteSourceFile();
						$(this).dialog("close"); 
					},
					"Cancel": function(){
						$(this).dialog("close");
					} 
			} 
		});
	}
	if(data.status==true){
		copyFiles();
		deleteSourceFile();
	}
}


function OverwriteConfirm(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+ $.cookie("srcFile");
	$.ajax({
	type: "GET",
	dataType:"json",
	url: "/",
	data: {
		action: "overwriteconfirm",
		destdir: destDir
	},
  	success:function(data){
		overwriteBox(data);
  }
  ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
  
  });	
}

function copyFiles(){
	var destDir = $("#dir").val()+"/"+clickedFileName+"/"+ $.cookie("srcFile");
	$.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		action: "copy",
		sourcedir: $.cookie("srcDir"),
		destdir: destDir
	},
  	success:function(data){
		feedbackBox(data);
  }
  ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
  });	
}


function renameFiles(oldname,newname){
	var dir = $("#dir").val()+"/"+clickedFileName;
	var name = $("#dir").val()+"/"+$("#rename").val();
	$.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		action: "rename",
		newdir: dir,
		name: name
	},
  	success:function(data){
		feedbackBox(data);
  }});	
}


function deleteSourceFile(){
	
	$.ajax({
		type: "GET",
		url: "/",
		dataType:"json",
		data: {
			action: "delete",
			newdir: $.cookie("srcDir")
	},
  	success:function(data){
		feedbackBox(data);

  }
  /*
  ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
*/
  });	
}

function deleteFiles(){
	var dir = $("#dir").val()+"/"+clickedFileName;
	$.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		action: "delete",
		newdir: dir
	},
  	success:function(data){
		feedbackBox(data);

  }
  /*
  ,
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
*/
  });	
	
}

/****************************************** Right Click Menu ***************************************/
/****************************************** Helper Functions ***************************************/

function getCopiedDir(action){
	sourceDir = $("#dir").val()+"/"+clickedFileName;
	sourceFileName = clickedFileName;
	actionOnFile =	action;
	$.cookie("srcDir", sourceDir);
	$.cookie("srcFile", sourceFileName);
	$.cookie("actionOnFile",action);
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
	dataType:"json",
	data: {
		action: "newfolder",
		newdir: dir
	},
  	success:function(data){
		feedbackBox(data);

  }
  /*
   error:function (jqXHR, textStatus, errorThrown){
        console.log("Error:" + textStatus+ "," + errorThrown);
    }
	*/ 
  });	
}

function sendFileName(newFileName){
	var dir = $("#dir").val()+"/"+newFileName;
	$.ajax({
	type: "GET",
	url: "/",
	dataType:"json",
	data: {
		action: "newfile",
		newdir: dir
	},
  	success:function(data){
		feedbackBox(data);
  }});	
}

function feedbackBox(data){
	$("#feedbackBox" ).dialog({
			open: function(){
				document.getElementById("contentholder").innerHTML ="";
				$("#contentholder").append(data.feedback);
			},
			 buttons: { 
			 	"Ok": function(){
					if(data.status==true){
						window.location.reload(); 
					}
					$(this).dialog("close"); 
				},
				"Cancel": function(){
					$(this).dialog("close");
				} 
		} 
	});
}




function overwriteBox(data){
	if(data.status==false){
		$("#overwriteBox" ).dialog({
				open: function(){
					document.getElementById("contentholder1").innerHTML ="";
					$("#contentholder1").append(data.feedback);
				},
				 buttons: { 
					"Ok": function(){
							copyFiles();
						$(this).dialog("close"); 
					},
					"Cancel": function(){
						$(this).dialog("close");
					} 
			} 
		});
	}
	if(data.status==true) copyFiles();
}