(function(global,$){"use strict";var Updater=global.Updater=global.Updater||{};var uwrap;var actionsQueueElement;var actionsQueue={};var currentAction;var backupDate=(new Date).getTime()/1e3;var backupTables=[];var backupDirs=[];var backupDbDone=false;var backupFilesDone=false;var eeUpdates=[];var eeServer;var eeCopyDirs=[];var eeInstallerUrl=window.location.protocol+"//"+window.location.host+window.location.pathname+"?/dupdater/";Updater.processInit=function(){if(!document.getElementById("actions_queue"))return;uwrap=Updater.Wrap;actionsQueueElement=$("#actions_queue");uwrap.delegate(".backup_db input, .backup_files input","click",toggleBackupActions);toggleBackupActions();uwrap.find(".start_action").click(startActions);uwrap.delegate(".queries_exec","click",showSqlQueries);uwrap.delegate(".js-retrybtn","click",retryAction)};function toggleBackupActions(force_db,force_files){var action,exists;var enabled={backup_files:uwrap.find(".backup_files input:checked").val(),backup_db:uwrap.find(".backup_db input:checked").val()};var actions=["backup_files","backup_db"];if(force_db===true){uwrap.find(".backup_db .js-yes").attr("checked","checked");enabled.backup_db="yes"}if(force_files===true){uwrap.find(".backup_files .js-yes").attr("checked","checked");enabled.backup_files="yes"}for(var i=0;i<actions.length;i++){exists=false;if(actionsQueueElement.find(".type-"+actions[i]).length>0)exists=true;if(exists===false&&enabled[actions[i]]=="yes"){action={};action.type=actions[i];action.updaterAction="Backup";if(actions[i]=="backup_db"&&force_db===true){action.status="forced"}if(actions[i]=="backup_files"&&force_files===true){action.status="forced"}addSingleAction(action)}else if(exists===true&&enabled[actions[i]]=="no"){actionsQueueElement.find(".type-"+actions[i]).remove()}}}Updater.addProcessAction=function(data){var found,action,actions;for(var i=0;i<data.found.length;i++){actions=[];found=data.found[i];if(found.type=="addon"||found.type=="cp_theme"||found.type=="forum_theme"){for(var ii in found.info){actions.push({type:found.type,info:found.info[ii]})}}else{actions.push({type:found.type,info:found.info})}for(var iii=0;iii<actions.length;iii++){action={};action.temp_dir=data.temp_dir;action.temp_key=data.temp_key;action.type=actions[iii].type;action.info=actions[iii].info;action.updaterAction=actions[iii].info.updater_action?actions[iii].info.updater_action:"update";addSingleAction(action)}}};function addSingleAction(obj){var html;var location="after";obj.id=Updater.generateRandomString();if(typeof obj.status=="undefined"){obj.status="queued"}if(obj.type=="ee"){toggleBackupActions(true,true)}if(obj.type=="backup_files"||obj.type=="backup_db"){location="before"}html=Updater.Templates.action_row(obj);actionsQueueElement.find(".js-no_actions").hide();if(location=="after"){if(actionsQueueElement.find(".type-ee").length>0){actionsQueueElement.find(".type-ee").before(html)}else{actionsQueueElement.append(html)}}else{actionsQueueElement.prepend(html)}if(actionsQueueElement.find("tr.action:not(.type-backup_db,.type-backup_files)").length>0){uwrap.find("button.start_action").removeAttr("disabled").removeClass("disabled")}actionsQueue[obj.id]=obj;initSortable()}function updateCurrentAction(){var html=Updater.Templates.action_row(currentAction);$("#"+currentAction.id).replaceWith(html);actionsQueueElement.sortable("update")}function initSortable(){actionsQueueElement.sortable({items:"> .action:not(.type-ee)",handle:".move"})}function startActions(){var current,current_id;var queued_trs=actionsQueueElement.find("tr.status-forced, tr.status-queued");if(queued_trs.length===0){return false}current=queued_trs.first();current_id=current.attr("id");if(typeof actionsQueue[current_id]=="undefined"){return false}if(actionsQueue[current_id].status!="queued"&&actionsQueue[current_id].status!="forced"){return false}switch(actionsQueue[current_id].type){case"backup_files":startBackupFiles(current_id);break;case"backup_db":startBackupDb(current_id);break;case"addon":case"ee_forum":case"ee_msm":case"cp_theme":case"forum_theme":startGeneralAction(current_id);break;case"ee":eeUpdateInit(current_id);break}}function startBackupFiles(id){currentAction=actionsQueue[id];currentAction.status="processing";currentAction.loadingMsg="Preparing Files Backup";updateCurrentAction();$.ajax({url:Updater.ACT_URL+"&task=backup_files_prepare"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY,time:backupDate},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rdata,textStatus,xhr){Updater.setXID(xhr);backupDirs=rdata.dirs;backupFilesDirs(0)}})}function backupFilesDirs(index){var percent;if(index===0){currentAction.progressMsg="&nbsp;";delete currentAction.loadingMsg;updateCurrentAction()}if(typeof backupDirs[index]=="undefined"){currentAction.status="done";delete currentAction.progressMsg;updateCurrentAction();startActions();return}percent=100/backupDirs.length*index;$("#single_action_progress").css("width",percent+"%");$("#single_action_progress").find(".inner").html(backupDirs[index]);$.ajax({url:Updater.ACT_URL+"&task=backup_files"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action:"backup",dir:backupDirs[index],time:backupDate,XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rdata,textStatus,xhr){Updater.setXID(xhr);if(rdata.success=="no"){triggerError(rdata.body);return}backupFilesDirs(index+1)}})}function startBackupDb(id){currentAction=actionsQueue[id];currentAction.status="processing";currentAction.loadingMsg="Preparing Files Backup";updateCurrentAction();$.ajax({url:Updater.ACT_URL+"&task=backup_database_prepare"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rdata,textStatus,xhr){Updater.setXID(xhr);backupTables=rdata.tables;backupDbTables(0)}})}function backupDbTables(index){var percent;if(index===0){currentAction.progressMsg="&nbsp;";delete currentAction.loadingMsg;updateCurrentAction()}if(typeof backupTables[index]=="undefined"){currentAction.status="done";delete currentAction.progressMsg;updateCurrentAction();startActions();return}percent=100/backupTables.length*index;$("#single_action_progress").css("width",percent+"%");$("#single_action_progress").find(".inner").html(backupTables[index]);$.ajax({url:Updater.ACT_URL+"&task=backup_database"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action:"backup",table:backupTables[index],time:backupDate,XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rdata,textStatus,xhr){Updater.setXID(xhr);if(rdata.success=="no"){triggerError(rdata.body);return}backupDbTables(index+1)}})}function startGeneralAction(id){currentAction=actionsQueue[id];currentAction.status="processing";currentAction.loadingMsg="Moving Files";updateCurrentAction();$.ajax({url:Updater.ACT_URL+"&task=addon_move_files"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{addon:JSON.stringify(currentAction),XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rData,textStatus,xhr){Updater.setXID(xhr);if(rData.success=="no"){triggerError(rData.body);return}if(rData.queries){for(var i=0;i<rData.queries.length;i++){Updater.queries.push(rData.queries[i]+";")}}if(currentAction.type=="addon"||currentAction.type=="ee_forum"){setTimeout(function(){endGeneralAction(id)},500);return}currentAction.status="done";delete currentAction.loadingMsg;updateCurrentAction();startActions();checkQueriesExecuted()}})}function endGeneralAction(id){if(currentAction.updaterAction=="install"){currentAction.loadingMsg="Installing..."}else{currentAction.loadingMsg="Updating..."}updateCurrentAction();$.ajax({url:Updater.MCP_AJAX_URL+"&task=addon_install"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{addon:JSON.stringify(currentAction),XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rData,textStatus,xhr){Updater.setXID(xhr);if(rData.success=="no"){triggerError(rData.body);return}if(rData.queries){for(var i=0;i<rData.queries.length;i++){Updater.queries.push(rData.queries[i]+";")}}currentAction.status="done";delete currentAction.loadingMsg;updateCurrentAction();startActions();checkQueriesExecuted()}})}function eeUpdateInit(id){currentAction=actionsQueue[id];currentAction.status="processing";currentAction.loadingMsg="Putting site offline...";updateCurrentAction();$.ajax({url:Updater.ACT_URL+"&task=ee_update_init"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action:"site_offline",action_obj:JSON.stringify(currentAction),XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rData,textStatus,xhr){Updater.setXID(xhr);if(rData.success=="no"){triggerError(rData.body);return}eeUpdateCopyInstaller()}})}function eeUpdateCopyInstaller(){currentAction.loadingMsg="Copying installer files...";updateCurrentAction();$.ajax({url:Updater.ACT_URL+"&task=ee_update_init"+"&cache="+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action:"copy_installer",action_obj:JSON.stringify(currentAction),XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr);triggerError(xhr)},success:function(rData,textStatus,xhr){Updater.setXID(xhr);if(rData.success=="no"){triggerError(rData.body);return}eeUpdates=rData.updates;eeServer=rData.server;if(eeUpdates.length===0){eeUpdateWaitForServer();return}eeUpdateWaitForServer()}})}function eeUpdateWaitForServer(times){if(!times)times=1;else times++;currentAction.loadingMsg="Waiting for server to respond. Attempts: "+times;updateCurrentAction();$.ajax({url:eeInstallerUrl+"index/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action_obj:JSON.stringify(currentAction),server:eeServer},timeout:3e3,error:function(xhr){setTimeout(function(){eeUpdateWaitForServer(times)},1e3)},success:function(rData,textStatus,xhr){if(!rData||!rData.success){setTimeout(function(){eeUpdateWaitForServer(times)},1e3)}else{eeCopyFilesPrepare()}}})}function eeCopyFilesPrepare(){currentAction.loadingMsg="Preparing Files Copy";updateCurrentAction();$.ajax({url:eeInstallerUrl+"copy_files_prepare"+"/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action_obj:JSON.stringify(currentAction),server:eeServer},error:function(xhr){triggerError(xhr)},success:function(rData,textStatus,xhr){if(rData.success=="no"){triggerError(rData.body);return}eeCopyDirs=rData.dirs;eeCopyFiles(0)}})}function eeCopyFiles(index){var percent;if(index===0){currentAction.progressMsg="Preparing Files Copy";delete currentAction.loadingMsg;updateCurrentAction()}if(typeof eeCopyDirs[index]=="undefined"){delete currentAction.progressMsg;eeUpdate(0);return}percent=100/eeCopyDirs.length*index;$("#single_action_progress").css("width",percent+"%");$("#single_action_progress").find(".inner").html(eeCopyDirs[index]);$.ajax({url:eeInstallerUrl+"copy_files/"+encodeURIComponent(eeCopyDirs[index].replace(/\//g,"-"))+"/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{dir:eeCopyDirs[index],action_obj:JSON.stringify(currentAction),server:eeServer},error:function(xhr){triggerError(xhr)},success:function(rData,textStatus,xhr){if(rData.success=="no"){triggerError(rData.body);return}eeCopyFiles(index+1)}})}function eeUpdate(index){if(typeof eeUpdates[index]=="undefined"){delete currentAction.loadingMsg;eeUpdateModules();return}currentAction.loadingMsg="Updating to: "+eeUpdates[index].label;updateCurrentAction();$.ajax({url:eeInstallerUrl+"update_ee/"+eeUpdates[index].version+"/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{version:eeUpdates[index].version,action_obj:JSON.stringify(currentAction),server:eeServer},error:function(xhr){triggerError(xhr)},success:function(rData,textStatus,xhr){if(rData===null){triggerError("UNKNOWN ERROR");return}if(rData.queries){for(var i=0;i<rData.queries.length;i++){Updater.queries.push(rData.queries[i]+";")}}if(rData.success=="no"){triggerError(rData.body);return}eeUpdate(index+1)}})}function eeUpdateModules(){currentAction.loadingMsg="Executing module update routines...";updateCurrentAction();$.ajax({url:eeInstallerUrl+"update_modules"+"/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action_obj:JSON.stringify(currentAction),server:eeServer},error:function(xhr){triggerError(xhr)},success:function(rData,textStatus,xhr){if(rData.success=="no"){triggerError(rData.body);return}if(rData.queries){for(var i=0;i<rData.queries.length;i++){Updater.queries.push(rData.queries[i]+";")}}eeUpdateCleanup()}})}function eeUpdateCleanup(){currentAction.loadingMsg="Removing installer files...";updateCurrentAction();checkQueriesExecuted();$.ajax({url:eeInstallerUrl+"cleanup"+"/"+Updater.generateRandomString(),dataType:"json",type:"POST",data:{action_obj:JSON.stringify(currentAction),server:eeServer},error:function(xhr){triggerError(xhr)},success:function(rData,textStatus,xhr){currentAction.status="done";delete currentAction.loadingMsg;updateCurrentAction()}})}function triggerError(err){if(typeof err.responseText!="undefined"){currentAction.errorMsg="Unexpected server response, probably a PHP error.";currentAction.errorDetail=global.btoa(err.responseText)}else{currentAction.errorMsg=err}currentAction.status="error";delete currentAction.loadingMsg;delete currentAction.progressMsg;updateCurrentAction()}function retryAction(e){$("#error_log").hide();$(e.target).closest(".action").removeAttr("status-error").addClass("status-queued");delete currentAction.errorMsg;delete currentAction.errorDetail;if(currentAction.type=="ee"){currentAction.status="processing";updateCurrentAction();eeUpdateWaitForServer()}else{currentAction.status="queued";updateCurrentAction();startActions()}}function checkQueriesExecuted(){if(Updater.queries.length>0){uwrap.find("a.queries_exec").show().find(".total").html(Updater.queries.length)}}function showSqlQueries(e){e.preventDefault();$("#queries_executed").slideDown();$("html, body").stop().animate({scrollTop:$("#queries_executed").offset().top},1e3);$("#queries_executed").find("textarea").html(Updater.queries.join("\n"))}})(window,jQuery);(function(global,$){"use strict";var Updater=global.Updater=global.Updater||{};if(!global.btoa)global.btoa=global.base64.encode;if(!global.atob)global.atob=global.base64.decode;var uwrap;var dropregion;var file_queue={};var file_queue_elem;var upload_queue={};var upload_url;var input=document.createElement("input");input.type="file";var html5_support="multiple"in input&&typeof global.FormData!="undefined";var swfobj;Updater.uploadInit=function(){if(!document.getElementById("updater_upload"))return;uwrap=Updater.Wrap;dropregion=uwrap.find(".dropregion");file_queue_elem=$("#upload_queue");upload_url=Updater.ACT_URL+"&task=upload_file";$(document.body).bind("dragover",function(e){e.preventDefault();return false});$(document.body).bind("drop",function(e){e.preventDefault();return false});if(html5_support){html5Init()}else{swfInit()}};function addFileQueue(file,idstr){var obj={id:idstr,filename:file.name,filesize:formatBytes(file.size,2),status:"queued"};upload_queue[idstr]=obj;var html=Updater.Templates.upload_filerow(obj);file_queue_elem.find(".js-no_files").hide();file_queue_elem.append(html)}function uploadProgress(file_id,loaded,total,speed){var percent_loaded=(loaded/(total/100)).toFixed(2)+"%";$("#"+file_id).find(".progress").css("width",percent_loaded)}function html5Init(){var input=document.createElement("input");input.setAttribute("multiple","multiple");input.setAttribute("type","file");input.setAttribute("accept",".zip");$("#update_upload_placeholder").replaceWith(input);dropregion.find("input").change(html5DialogClosed);dropregion.bind("dragover",function(e){e.preventDefault();e.stopPropagation()});dropregion.bind("dragleave",function(e){e.preventDefault();e.stopPropagation()});dropregion.bind("drop",function(e){e.stopPropagation();e.preventDefault();var dropped_files=e.originalEvent.dataTransfer.files;for(var i=0;i<dropped_files.length;i++){var id=Math.random().toString(36).substring(2);file_queue[id]=dropped_files[i];addFileQueue(dropped_files[i],id)}html5UploadStart()})}function html5DialogClosed(e){var extensions=["zip"];for(var i=0;i<e.target.files.length;i++){var Ext=e.target.files[i].name.toLowerCase().split(".").pop();if(extensions.indexOf(Ext)<0)continue;var id=Math.random().toString(36).substring(2);file_queue[id]=e.target.files[i];addFileQueue(e.target.files[i],id)}html5UploadStart()}function html5UploadStart(){var currentFile=file_queue_elem.find(".file").filter(".status-queued:first");var fileId=currentFile.attr("id");var fileObj=upload_queue[fileId];if(currentFile.length===0){delete file_queue[fileId];return false}fileObj.status="uploading";updateFileRow(fileId,fileObj);var xhr=new XMLHttpRequest;xhr.upload["onprogress"]=function(rpe){uploadProgress(fileId,rpe.loaded,rpe.total)};xhr.onload=function(event){html5UploadResponse(event,xhr,fileId)};xhr.open("post",upload_url,true);xhr.setRequestHeader("Cache-Control","no-cache");xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");xhr.setRequestHeader("X-File-Name",fileObj.filename);xhr.setRequestHeader("X-File-Size",fileObj.fileSize);var f=new FormData;f.append("XID",Updater.XID);f.append("auth_key",Updater.AUTH_KEY);f.append("updater_file",file_queue[fileId]);xhr.send(f)}function html5UploadResponse(event,xhr,fileId){uploadProgress(fileId,100,100);var serverData,xid,oldXid;var fileObj=upload_queue[fileId];fileObj.status="error";if(xhr.status==200){try{serverData=JSON.parse(xhr.responseText)}catch(errorThrown){fileObj.errorMsg="Unexpected server response, probably a PHP error.";fileObj.errorDetail=global.btoa(xhr.responseText);updateFileRow(fileId,fileObj);return}if(serverData.error_msg){fileObj.errorMsg=serverData.error_msg;updateFileRow(fileId,fileObj);return}if(serverData.success=="no"){fileObj.errorMsg=serverData.error_msg;updateFileRow(fileId,fileObj);return}fileObj.status="done";updateFileRow(fileId,fileObj);Updater.addProcessAction(serverData)}else{fileObj.errorMsg="Server responded with a "+xhr.status+" status";fileObj.errorDetail=global.btoa(xhr.responseText);updateFileRow(fileId,fileObj)}html5UploadStart()}function swfInit(){var ButtonWith=120;if($("#updater_upload").is(":visible")!==false){ButtonWith=$("#updater_upload").width()+10}swfobj=new SWFUpload({flash_url:Updater.THEME_URL+"img/swfupload.swf",upload_url:global.location.protocol+upload_url,post_params:{task:"upload_file",flash_upload:"yes",XID:Updater.XID,auth_key:Updater.AUTH_KEY},file_post_name:"updater_file",prevent_swf_caching:true,assume_success_timeout:0,file_size_limit:0,file_types:"*.zip",file_types_description:".zip Files",file_upload_limit:0,file_queue_limit:0,swfupload_preload_handler:function(){},swfupload_load_failed_handler:function(){},file_dialog_start_handler:function(){},file_queued_handler:swfQueuedHandler,file_queue_error_handler:function(){},file_dialog_complete_handler:swfDialogCompleteHandler,upload_resize_start_handler:function(){},upload_start_handler:swfStartHandler,upload_progress_handler:swfProgressHandler,upload_error_handler:swfErrorHandler,upload_success_handler:swfSuccessHandler,upload_complete_handler:function(){},button_image_url:"",button_placeholder_id:"update_upload_placeholder",button_width:ButtonWith,button_height:28,button_window_mode:SWFUpload.WINDOW_MODE.TRANSPARENT,button_cursor:SWFUpload.CURSOR.HAND,button_action:SWFUpload.BUTTON_ACTION.SELECT_FILES,debug:true})}function swfQueuedHandler(file){if(addFileQueue(file,file.id,"")===false){this.cancelUpload(file.id,false);return false}}function swfDialogCompleteHandler(totalFilesSelected,totalFilesQueued,grandTotalFilesQueued){this.startUpload()}function swfStartHandler(file){var fileObj=upload_queue[file.id];fileObj.status="uploading";updateFileRow(file.id,fileObj)}function swfProgressHandler(file,bytesLoaded,bytesTotal){uploadProgress(file.id,bytesLoaded,bytesTotal)}function swfErrorHandler(file,error,message){if(error=="-270")return;if(error=="-280")return;var fileObj=upload_queue[file.id];fileObj.status="error";fileObj.errorMsg="Upload Failed: "+error+" MSG:"+message;fileObj.errorDetail=global.btoa(serverData);updateFileRow(file.id,fileObj)}function swfSuccessHandler(file,serverResponse,response){uploadProgress(file.id,100,100);var fileObj=upload_queue[file.id];fileObj.status="error";try{serverData=JSON.parse(serverResponse)}catch(errorThrown){fileObj.errorMsg="Unexpected server response, probably a PHP error.";fileObj.errorDetail=global.btoa(serverResponse);updateFileRow(file.id,fileObj);return}if(serverData.success=="no"){fileObj.errorMsg=serverData.error_msg;updateFileRow(file.id,fileObj);return}fileObj.status="done";updateFileRow(file.id,fileObj);Updater.addProcessAction(serverData)}function updateFileRow(id,obj){var html=Updater.Templates.upload_filerow(obj);$("#"+id).replaceWith(html)}function formatBytes(bytes,precision){var units=["b","KB","MB","GB","TB"];bytes=Math.max(bytes,0);var pow=Math.floor((bytes?Math.log(bytes):0)/Math.log(1024));pow=Math.min(pow,units.length-1);bytes=bytes/Math.pow(1024,pow);precision=typeof precision=="number"?precision:0;return Math.round(bytes*Math.pow(10,precision))/Math.pow(10,precision)+" "+units[pow]}})(window,jQuery);(function(global,$){"use strict";var Updater=global.Updater=global.Updater||{};var _wrap={};var _internal={};Updater.settingsInit=function(){if(!document.getElementById("updater-settings"))return;_wrap=Updater.Wrap;_wrap.delegate(".js-togglefiletransfer","click",toggleFileTransferOptions);toggleFileTransferOptions();_wrap.delegate(".pathmap .browse","click",browsePathMap);var login_check_timeout=0;_wrap.delegate(".js-ftp, .js-sftp","keyup",function(){clearTimeout(login_check_timeout);login_check_timeout=setTimeout(function(){checkLogin()},500)});_wrap.delegate(".retest","click",checkLogin);getServerInfo()};function checkLogin(){var inputs=_wrap.find("form").serializeArray();var td=_wrap.find("td.login");td.removeClass("login-failed login-success").addClass("login-testing");inputs.push({name:"XID",value:Updater.getXID()});inputs.push({name:"auth_key",value:Updater.AUTH_KEY});$.ajax({url:Updater.MCP_AJAX_URL+"&task=test_login",type:"POST",dataType:"json",data:inputs,success:function(data,textStatus,xhr){Updater.setXID(xhr);if(data.success=="yes"){td.removeClass("login-testing").addClass("login-success")}else{td.removeClass("login-testing").addClass("login-failed")}},error:function(xhr){Updater.setXID(xhr)}})}function toggleFileTransferOptions(){var radio_buttons=_wrap.find(".js-togglefiletransfer");var current_value=radio_buttons.find("input:checked").val();var parent_div=radio_buttons.closest(".utable");parent_div.find("tbody").hide();parent_div.find("tbody.js-"+current_value).show();if(current_value=="sftp"||current_value=="ftp"){checkLogin()}}function browsePathMap(e){var modalelem=$("#updater_folder_browse");var save_btn=modalelem.find(".btn-primary");var mapper=$(e.target).data("map");modalelem.data("mapper",mapper);_internal.inputs=_wrap.find("form").serializeArray();_internal.loading_elem=modalelem.find(".loading");_internal.error_elem=modalelem.find(".error");_internal.modal=modalelem;modalelem.modal({backdrop:"static"});sendBrowseXhr("chdir","");if(!save_btn.data("attached")){save_btn.data("attached",true);save_btn.click(function(ee){ee.preventDefault();var value=modalelem.find(".path input").val();_wrap.find(".map-"+modalelem.data("mapper")+" input").attr("value",value);modalelem.modal("hide")});modalelem.delegate(".cdup","click",function(ee){sendBrowseXhr("cdup",modalelem.find(".path input").val())});modalelem.delegate(".chdir","click",function(ee){sendBrowseXhr("chdir",modalelem.find(".path input").val()+$(ee.target).html())})}}function sendBrowseXhr(action,path){_internal.error_elem.hide();_internal.loading_elem.show();$.ajax({url:Updater.MCP_AJAX_URL+"&task=browse_server",type:"POST",dataType:"json",data:{settings:_internal.inputs,path:path,action:action,XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},success:function(data,textStatus,xhr){Updater.setXID(xhr);_internal.loading_elem.hide();if(data.success=="no"){_internal.error_elem.show();return}if(data.path){_internal.modal.find(".path input").attr("value",data.path)}var html=Updater.Templates.browse_server(data.items);_internal.modal.find(".content").html(html)},error:function(xhr){Updater.setXID(xhr)}})}function getServerInfo(){$.ajax({url:Updater.ACT_URL+"&task=get_server_info",dataType:"json",type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr){Updater.setXID(xhr)},success:function(rData,textStatus,xhr){Updater.setXID(xhr);Updater.Server=rData.server;for(var Path in Updater.Server){if(_wrap.find(".map-"+Path+" input").val()===""){_wrap.find(".map-"+Path+" input").attr("value",Updater.Server[Path])}}}})}})(window,jQuery);(function(global,$){"use strict";var Updater=global.Updater=global.Updater||{};Updater.queries=[];Updater.init=function(){Updater.Wrap=$("#updater");Updater.uploadInit();Updater.processInit();Updater.settingsInit();Updater.Wrap.delegate(".js-test_settings","click",testSettings);Updater.Wrap.delegate(".js-show_error","click",showError);Updater.TestAJAX(Updater.ACT_URL);cleanTempDirs()};Updater.generateRandomString=function(){return Math.random().toString(36).substring(2)};Updater.getXID=function(){if(!Updater.XID){return EE.XID}else{return Updater.XID}};Updater.setXID=function(xhr){var xid=xhr.getResponseHeader("X-Updater-XID");if(!xid)xid=null;Updater.XID=xid};Updater.TestAJAX=function(action_url){var test_ajax=$("#test_ajax_error");$.ajax({url:action_url+"&task=test_ajax_call",dataType:"json",type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY},error:function(xhr,textStatus,errorThrown){Updater.setXID(xhr);test_ajax.show();test_ajax.find("a.url").attr("href",action_url).html(action_url);test_ajax.find(".error textarea").html(global.btoa(xhr.responseText));if(xhr.status===0){test_ajax.find(".error .inner").html("<strong>Response Code:</strong> "+xhr.status+"&nbsp;&nbsp;&nbsp;<strong>Response Text</strong>: (Probably Cross-Domain AJAX Error)")}else if(xhr.status>=200){test_ajax.find(".error .inner").html("<strong>Response Code:</strong> "+xhr.status+"&nbsp;&nbsp;&nbsp;<strong>Response Text</strong>: "+xhr.statusText)}},success:function(rData,textStatus,xhr){Updater.setXID(xhr);if(!rData)test_ajax.show()}})};function cleanTempDirs(){$.ajax({url:Updater.SITE_URL+"#clean_temp_dirs",type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY,ACT:Updater.ACT_ID,task:"clean_temp_dirs"},error:function(xhr){Updater.setXID(xhr)},success:function(rdata,textStatus,xhr){Updater.setXID(xhr)}})}function testSettings(e){e.preventDefault();var modal=$("#test_transfer_method");modal.css({width:"800px","margin-left":function(){return-($(this).width()/2)}}).modal().find(".loading").show();modal.find(".wrapper").empty();$.ajax({url:Updater.MCP_AJAX_URL,type:"POST",data:{XID:Updater.getXID(),auth_key:Updater.AUTH_KEY,ACT:Updater.ACT_ID,task:"test_transfer_method"},success:function(rdata,textStatus,xhr){Updater.setXID(xhr);modal.find(".loading").hide();modal.find(".wrapper").html(rdata)},error:function(xhr){Updater.setXID(xhr)}})}function showError(e){e.preventDefault();var error_log=$("#error_log");var html=global.atob($(e.target).closest(".error").find("textarea").val());error_log.slideDown();error_log.find("body").empty();$("html, body").stop().animate({scrollTop:error_log.offset().top},1e3);$('<iframe id="updater_error_iframe" style="width:100%;height:300px"/>').load(function(){$("#updater_error_iframe").contents().find("body").append(html)}).appendTo(error_log.find(".body"))}})(window,jQuery);$(document).ready(function(){Updater.init()});