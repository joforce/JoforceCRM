{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Calendar/views/Calendar.php *}
{strip}

        <script type="text/javascript" src="../../layouts/modules/Users/resources/Supportmail.js"></script>
        
<div class="container-fluid support-page pt-3 pb-3">

    <div class="support-page main-support m-4 p-2 ">
        <div class="row">
            <div class="col-md-6">
                <h1>Let's get you</br> some help!</h1>
                <h6>Have any issue? Send us an <span class="email-us">email.</span></h6>
                <div class="img-wrapper">
                    <img class="images-support" src="https://i.ibb.co/bWfN3Qy/undraw-onboarding-o8mv-1.png"
                        alt="undraw-onboarding-o8mv-1" border="0">
                </div>
            </div>

            <div class="col-md-6 mt-5">
                <form>
                <input type="hidden" name="module" value="Users"/>
                 <input type="hidden" name="view" value="Supportmail" />
                 
                    <div class="form-group">
                        <label for="list">Name</label>
                        <input type="text"  name="ename" class="form-control" id="name" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="tags">Email</label>
                        <input type="email" name="email" class="form-control" id="email">
                    </div>

                    <div class="form-group">
                        <label for="describe">Description</label>
                        <textarea type="text" name="edescription" class="form-control" id="describe" rows="5"></textarea>
                    </div>

                    

                    <button type="button" id="check"  class="btn btn-primary supportmailsubmit"><span>Submit</span> <i
                            class="fas fa-long-arrow-alt-right"></i></button>
                </form>            
            </div>

        </div>
    </div>
</div>
{/strip}