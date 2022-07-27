<?php
require_once("modules/Emails/class.phpmailer.php");
require_once("modules/Emails/mail.php");
require("modules/Emails/class.smtp.php");
class Users_Supportmail_View extends Head_Save_Action
{
  
   function process(Head_Request $request) {     
      //  die("ramaa"); 
      // require_once('modules/Emails/mail.php');
      
        $to_email = 'sonofvm@gmail.com';
        $from_email  = $request->get('email');
        $name = $request->get('name');
        $description = $request->get('description');

        $subject = 'Joforce Support Mail';

        $mailer = Emails_Mailer_Model::getInstance();
        $mailer->IsHTML(true);
        
        $mailer->ConfigSenderInfo($from_mail, $name, $replyTo);
        
        $mailer->Body = '';
				
        $emailObj = new Emails_Record_Model();
				$mailer->Body = $emailObj->getTrackImageDetails($id, $emailObj->isEmailTrackEnabled());
        
        $mailer->Body .= "Name   :<b>    "   .$name."</b><br>";
        $mailer->Body .= "Email   :<b>   ".$from_email."</b><br>" ;
				$mailer->Body .= "Description  :<b>  " .$description."</b>";
       
				$mailer->Subject = $subject;
				$mailer->AddAddress($to_email);

        
      $status = $mailer->Send(true);
      
			if(!$status) {
            $from_email  = 'pavithram@smackcoders.com';
            $name = $request->get('name');
            $description = $request->get('description');
            $mail = new PHPMailer();
               $mail->IsSMTP(); 
               $mail->SMTPDebug = 1;                                     
               $mail->Host =  'smtp.gmail.com'; 
               $mail->Username = "pavithram@smackcoders.com";
               $mail->Password = "pavis@21";    
               $mail->SMTPAuth = true;
               $mail->SMTPSecure = 'tls';                               
               $mail->Port       = 587;  
               $mail->From = $from_email;
               $mail->FromName = $name;
               $mail->Description = $description;
               $mail->AddAddress('ramachandiran091@gmail.com');               
               $mail->AddReplyTo($from_email);                         
               $mail->IsHTML(true);                                  
               $mail->Subject = 'Joforce Support Mail';
               $mail->Body    = 'hello';
               $mail->Body .= "Name   :<b>    "   .$name."</b><br>";
               $mail->Body .= "Email   :<b>   "    .$from_email."</b><br>" ;
               $mail->Body .= "Description  :<b>  " .$description."</b>";
               
               if(!$mail->Send()) {
                  echo "Mailer Error: " . $mail->ErrorInfo;
               } else {
                  echo "Message has been sent";
               }
			} 
         else {

           header("location:http://localhost/community-edition-version2.0/Users/view/Support");
                $status = true;
			}
    
      }
    }
  
  
  
  
    
  
