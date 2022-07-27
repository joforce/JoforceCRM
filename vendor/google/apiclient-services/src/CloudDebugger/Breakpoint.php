<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\CloudDebugger;

class Breakpoint extends \Google\Collection
{
  protected $collection_key = 'variableTable';
  public $action;
  public $canaryExpireTime;
  public $condition;
  public $createTime;
  protected $evaluatedExpressionsType = Variable::class;
  protected $evaluatedExpressionsDataType = 'array';
  public $expressions;
  public $finalTime;
  public $id;
  public $isFinalState;
  public $labels;
  protected $locationType = SourceLocation::class;
  protected $locationDataType = '';
  public $logLevel;
  public $logMessageFormat;
  protected $stackFramesType = StackFrame::class;
  protected $stackFramesDataType = 'array';
  public $state;
  protected $statusType = StatusMessage::class;
  protected $statusDataType = '';
  public $userEmail;
  protected $variableTableType = Variable::class;
  protected $variableTableDataType = 'array';

  public function setAction($action)
  {
    $this->action = $action;
  }
  public function getAction()
  {
    return $this->action;
  }
  public function setCanaryExpireTime($canaryExpireTime)
  {
    $this->canaryExpireTime = $canaryExpireTime;
  }
  public function getCanaryExpireTime()
  {
    return $this->canaryExpireTime;
  }
  public function setCondition($condition)
  {
    $this->condition = $condition;
  }
  public function getCondition()
  {
    return $this->condition;
  }
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param Variable[]
   */
  public function setEvaluatedExpressions($evaluatedExpressions)
  {
    $this->evaluatedExpressions = $evaluatedExpressions;
  }
  /**
   * @return Variable[]
   */
  public function getEvaluatedExpressions()
  {
    return $this->evaluatedExpressions;
  }
  public function setExpressions($expressions)
  {
    $this->expressions = $expressions;
  }
  public function getExpressions()
  {
    return $this->expressions;
  }
  public function setFinalTime($finalTime)
  {
    $this->finalTime = $finalTime;
  }
  public function getFinalTime()
  {
    return $this->finalTime;
  }
  public function setId($id)
  {
    $this->id = $id;
  }
  public function getId()
  {
    return $this->id;
  }
  public function setIsFinalState($isFinalState)
  {
    $this->isFinalState = $isFinalState;
  }
  public function getIsFinalState()
  {
    return $this->isFinalState;
  }
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param SourceLocation
   */
  public function setLocation(SourceLocation $location)
  {
    $this->location = $location;
  }
  /**
   * @return SourceLocation
   */
  public function getLocation()
  {
    return $this->location;
  }
  public function setLogLevel($logLevel)
  {
    $this->logLevel = $logLevel;
  }
  public function getLogLevel()
  {
    return $this->logLevel;
  }
  public function setLogMessageFormat($logMessageFormat)
  {
    $this->logMessageFormat = $logMessageFormat;
  }
  public function getLogMessageFormat()
  {
    return $this->logMessageFormat;
  }
  /**
   * @param StackFrame[]
   */
  public function setStackFrames($stackFrames)
  {
    $this->stackFrames = $stackFrames;
  }
  /**
   * @return StackFrame[]
   */
  public function getStackFrames()
  {
    return $this->stackFrames;
  }
  public function setState($state)
  {
    $this->state = $state;
  }
  public function getState()
  {
    return $this->state;
  }
  /**
   * @param StatusMessage
   */
  public function setStatus(StatusMessage $status)
  {
    $this->status = $status;
  }
  /**
   * @return StatusMessage
   */
  public function getStatus()
  {
    return $this->status;
  }
  public function setUserEmail($userEmail)
  {
    $this->userEmail = $userEmail;
  }
  public function getUserEmail()
  {
    return $this->userEmail;
  }
  /**
   * @param Variable[]
   */
  public function setVariableTable($variableTable)
  {
    $this->variableTable = $variableTable;
  }
  /**
   * @return Variable[]
   */
  public function getVariableTable()
  {
    return $this->variableTable;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Breakpoint::class, 'Google_Service_CloudDebugger_Breakpoint');
