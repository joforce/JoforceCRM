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

namespace Google\Service\Storagetransfer;

class TransferCounters extends \Google\Model
{
  public $bytesCopiedToSink;
  public $bytesDeletedFromSink;
  public $bytesDeletedFromSource;
  public $bytesFailedToDeleteFromSink;
  public $bytesFoundFromSource;
  public $bytesFoundOnlyFromSink;
  public $bytesFromSourceFailed;
  public $bytesFromSourceSkippedBySync;
  public $directoriesFailedToListFromSource;
  public $directoriesFoundFromSource;
  public $directoriesSuccessfullyListedFromSource;
  public $intermediateObjectsCleanedUp;
  public $intermediateObjectsFailedCleanedUp;
  public $objectsCopiedToSink;
  public $objectsDeletedFromSink;
  public $objectsDeletedFromSource;
  public $objectsFailedToDeleteFromSink;
  public $objectsFoundFromSource;
  public $objectsFoundOnlyFromSink;
  public $objectsFromSourceFailed;
  public $objectsFromSourceSkippedBySync;

  public function setBytesCopiedToSink($bytesCopiedToSink)
  {
    $this->bytesCopiedToSink = $bytesCopiedToSink;
  }
  public function getBytesCopiedToSink()
  {
    return $this->bytesCopiedToSink;
  }
  public function setBytesDeletedFromSink($bytesDeletedFromSink)
  {
    $this->bytesDeletedFromSink = $bytesDeletedFromSink;
  }
  public function getBytesDeletedFromSink()
  {
    return $this->bytesDeletedFromSink;
  }
  public function setBytesDeletedFromSource($bytesDeletedFromSource)
  {
    $this->bytesDeletedFromSource = $bytesDeletedFromSource;
  }
  public function getBytesDeletedFromSource()
  {
    return $this->bytesDeletedFromSource;
  }
  public function setBytesFailedToDeleteFromSink($bytesFailedToDeleteFromSink)
  {
    $this->bytesFailedToDeleteFromSink = $bytesFailedToDeleteFromSink;
  }
  public function getBytesFailedToDeleteFromSink()
  {
    return $this->bytesFailedToDeleteFromSink;
  }
  public function setBytesFoundFromSource($bytesFoundFromSource)
  {
    $this->bytesFoundFromSource = $bytesFoundFromSource;
  }
  public function getBytesFoundFromSource()
  {
    return $this->bytesFoundFromSource;
  }
  public function setBytesFoundOnlyFromSink($bytesFoundOnlyFromSink)
  {
    $this->bytesFoundOnlyFromSink = $bytesFoundOnlyFromSink;
  }
  public function getBytesFoundOnlyFromSink()
  {
    return $this->bytesFoundOnlyFromSink;
  }
  public function setBytesFromSourceFailed($bytesFromSourceFailed)
  {
    $this->bytesFromSourceFailed = $bytesFromSourceFailed;
  }
  public function getBytesFromSourceFailed()
  {
    return $this->bytesFromSourceFailed;
  }
  public function setBytesFromSourceSkippedBySync($bytesFromSourceSkippedBySync)
  {
    $this->bytesFromSourceSkippedBySync = $bytesFromSourceSkippedBySync;
  }
  public function getBytesFromSourceSkippedBySync()
  {
    return $this->bytesFromSourceSkippedBySync;
  }
  public function setDirectoriesFailedToListFromSource($directoriesFailedToListFromSource)
  {
    $this->directoriesFailedToListFromSource = $directoriesFailedToListFromSource;
  }
  public function getDirectoriesFailedToListFromSource()
  {
    return $this->directoriesFailedToListFromSource;
  }
  public function setDirectoriesFoundFromSource($directoriesFoundFromSource)
  {
    $this->directoriesFoundFromSource = $directoriesFoundFromSource;
  }
  public function getDirectoriesFoundFromSource()
  {
    return $this->directoriesFoundFromSource;
  }
  public function setDirectoriesSuccessfullyListedFromSource($directoriesSuccessfullyListedFromSource)
  {
    $this->directoriesSuccessfullyListedFromSource = $directoriesSuccessfullyListedFromSource;
  }
  public function getDirectoriesSuccessfullyListedFromSource()
  {
    return $this->directoriesSuccessfullyListedFromSource;
  }
  public function setIntermediateObjectsCleanedUp($intermediateObjectsCleanedUp)
  {
    $this->intermediateObjectsCleanedUp = $intermediateObjectsCleanedUp;
  }
  public function getIntermediateObjectsCleanedUp()
  {
    return $this->intermediateObjectsCleanedUp;
  }
  public function setIntermediateObjectsFailedCleanedUp($intermediateObjectsFailedCleanedUp)
  {
    $this->intermediateObjectsFailedCleanedUp = $intermediateObjectsFailedCleanedUp;
  }
  public function getIntermediateObjectsFailedCleanedUp()
  {
    return $this->intermediateObjectsFailedCleanedUp;
  }
  public function setObjectsCopiedToSink($objectsCopiedToSink)
  {
    $this->objectsCopiedToSink = $objectsCopiedToSink;
  }
  public function getObjectsCopiedToSink()
  {
    return $this->objectsCopiedToSink;
  }
  public function setObjectsDeletedFromSink($objectsDeletedFromSink)
  {
    $this->objectsDeletedFromSink = $objectsDeletedFromSink;
  }
  public function getObjectsDeletedFromSink()
  {
    return $this->objectsDeletedFromSink;
  }
  public function setObjectsDeletedFromSource($objectsDeletedFromSource)
  {
    $this->objectsDeletedFromSource = $objectsDeletedFromSource;
  }
  public function getObjectsDeletedFromSource()
  {
    return $this->objectsDeletedFromSource;
  }
  public function setObjectsFailedToDeleteFromSink($objectsFailedToDeleteFromSink)
  {
    $this->objectsFailedToDeleteFromSink = $objectsFailedToDeleteFromSink;
  }
  public function getObjectsFailedToDeleteFromSink()
  {
    return $this->objectsFailedToDeleteFromSink;
  }
  public function setObjectsFoundFromSource($objectsFoundFromSource)
  {
    $this->objectsFoundFromSource = $objectsFoundFromSource;
  }
  public function getObjectsFoundFromSource()
  {
    return $this->objectsFoundFromSource;
  }
  public function setObjectsFoundOnlyFromSink($objectsFoundOnlyFromSink)
  {
    $this->objectsFoundOnlyFromSink = $objectsFoundOnlyFromSink;
  }
  public function getObjectsFoundOnlyFromSink()
  {
    return $this->objectsFoundOnlyFromSink;
  }
  public function setObjectsFromSourceFailed($objectsFromSourceFailed)
  {
    $this->objectsFromSourceFailed = $objectsFromSourceFailed;
  }
  public function getObjectsFromSourceFailed()
  {
    return $this->objectsFromSourceFailed;
  }
  public function setObjectsFromSourceSkippedBySync($objectsFromSourceSkippedBySync)
  {
    $this->objectsFromSourceSkippedBySync = $objectsFromSourceSkippedBySync;
  }
  public function getObjectsFromSourceSkippedBySync()
  {
    return $this->objectsFromSourceSkippedBySync;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TransferCounters::class, 'Google_Service_Storagetransfer_TransferCounters');
