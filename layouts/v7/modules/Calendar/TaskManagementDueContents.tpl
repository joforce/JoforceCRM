<div id="taskManagementDueContainer" class='fc-overlay-modal modal-content' style="height:100%;">
<input type="hidden" name="colors" value='{json_encode($COLORS)}'>
<div class="overlayHeader">
   {assign var=HEADER_TITLE value="My Pending Tasks"}
   {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
</div>
<hr style="margin:0px;">
<div class='modal-body overflowYAuto' id='task_due'>
   <div class='datacontent'>
      <div class="data-header clearfix">
         <div class="btn-group dateFilters pull-left" role="group" aria-label="...">
         </div>
         <div id="taskManagementOtherFilters" class="otherFilters pull-right" style="width:550px;">
            <div class='field pull-left' style="width:250px;padding-right: 5px;">
            </div>
<!--            <div><button class="btn btn-success search"><span class="fa fa-search"></span></button></div>-->
         </div>
      </div>
      <hr>
      <div class="data-body row">
         {foreach key=TASK_KEY item=PRIORITY from=$TASK_DUE}
         <div class="col-lg-3 contentsBlock {$TASK_KEY} ui-droppable" data-priority='{$TASK_KEY}' data-page="{$PAGE}">
            <div class="{$TASK_KEY}-header" style="border-bottom: 2px solid {$PRIORITY}">
               <div class="title" style="background:{$PRIORITY};width:80px;"><span>{$TASK_KEY}</span></div>
            </div>
            <br>
            <div class="{$TASK_KEY}-content content" data-priority='{$TASK_KEY}' style="border-bottom: 1px solid {$PRIORITY};padding-bottom: 10px">
               <div class="input-group">
                  <input type="text" class="form-control taskSubject {$PRIORITY}" placeholder="{vtranslate('LBL_ADD_TASK_AND_PRESS_ENTER', $MODULE)}" aria-describedby="basic-addon1" style="width: 99%">
                  <span class="quickTask input-group-addon js-task-popover-container more cursorPointer" id="basic-addon1" style="border: 1px solid #ddd; padding: 0 13px;"> 
                  <a href="#" id="taskPopover" priority='{$PRIORITY}'><i class="fa fa-plus icon"></i></a>
                  </span>
               </div>
               <br>
               <div class='{$TASK_KEY}-entries container-fluid scrollable dataEntries padding20' style="height:400px;overflow:auto;width:400px;padding-left: 0px;padding-right: 0px;">
                  {foreach item=TASK_VALUES from=$TASKS}
                  {assign var=TYPE value=$TASK_HELPER->getDueType($TASK_VALUES['due_date'])}
                  {assign var=BASICINFO value=$TASK_HELPER->getBasicInfo($TASK_VALUES)}
                  {if $TYPE eq $TASK_KEY}	
                  <div class="entries ui-draggable ui-draggable-handle" style='border: 1px solid #ccc;margin-bottom: 10px;width:72%;'>
                     <div class="task clearfix" data-recordid="{$TASK_VALUES['activityid']}" data-priority="{$TASK_VALUES['priority']}" data-basicinfo='{$BASICINFO}' style="">
                        <div class="task-status pull-left">
                           <input class="statusCheckbox" name="taskstatus" type="checkbox">
                        </div>
                        <div class="task-body clearfix">
                           <div class="taskSubject pull-left textOverflowEllipsis" style="width:70%;">
                              <a class="quickPreview" data-id="{$TASK_VALUES['activityid']}" title="{$TASK_VALUES['subject']}">{$TASK_VALUES['subject']}</a>
                           </div>
                           <div class="more pull-right taskStatus picklist-240-1">{$TASK_VALUES['status']}</div>
                        </div>
                        <div class="other-details clearfix">
                           <div class="pull-left drag-task">
                              <img class="cursorPointerMove" src="layouts/v7/skins/images/drag.png">
                           </div>
                           <div class="task-details">
                              <span class="taskDueDate">
                              <i class="fa fa-calendar"></i>
                              <span style="vertical-align: middle">{$TASK_VALUES['due_date']}</span>
                              </span>
                           </div>
                           <div class="more pull-right cursorPointer task-actions">
                              <a id="taskPopover" class="quickTask" href="#">
                              <i class="fa fa-pencil-square-o icon"></i>
                              </a>
                              <a class="taskDelete" href="#">
                              <i class="fa fa-trash icon"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  {/if}
                  {/foreach}
               </div>
            </div>
         </div>
         {/foreach}
      </div>
      <div class="editTaskContent hide"> 
         {include file="TaskManagementEdit.tpl"|vtemplate_path:$MODULE} 
      </div>
   </div>
</div>


