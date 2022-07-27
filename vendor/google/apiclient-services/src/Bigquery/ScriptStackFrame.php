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

namespace Google\Service\Bigquery;

class ScriptStackFrame extends \Google\Model
{
  public $endColumn;
  public $endLine;
  public $procedureId;
  public $startColumn;
  public $startLine;
  public $text;

  public function setEndColumn($endColumn)
  {
    $this->endColumn = $endColumn;
  }
  public function getEndColumn()
  {
    return $this->endColumn;
  }
  public function setEndLine($endLine)
  {
    $this->endLine = $endLine;
  }
  public function getEndLine()
  {
    return $this->endLine;
  }
  public function setProcedureId($procedureId)
  {
    $this->procedureId = $procedureId;
  }
  public function getProcedureId()
  {
    return $this->procedureId;
  }
  public function setStartColumn($startColumn)
  {
    $this->startColumn = $startColumn;
  }
  public function getStartColumn()
  {
    return $this->startColumn;
  }
  public function setStartLine($startLine)
  {
    $this->startLine = $startLine;
  }
  public function getStartLine()
  {
    return $this->startLine;
  }
  public function setText($text)
  {
    $this->text = $text;
  }
  public function getText()
  {
    return $this->text;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ScriptStackFrame::class, 'Google_Service_Bigquery_ScriptStackFrame');
