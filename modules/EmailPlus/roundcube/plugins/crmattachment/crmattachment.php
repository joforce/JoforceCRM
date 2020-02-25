<?php
class crmattachment extends rcube_plugin {
	function init() {
		$rcmail = rcmail::get_instance();
		$this->register_action('plugin.crmattachment.attachFiles', array($this, 'attachFiles'));

		if ($rcmail->task == 'mail' && $rcmail->action == 'compose') {
			$this->include_script('crmattachment.js');
		}
	}	
	
	//Attach documents
	public function attachFiles() {
		$COMPOSE_ID = rcube_utils::get_input_value('_id', rcube_utils::INPUT_GPC);
		$uploadid = rcube_utils::get_input_value('_uploadid', rcube_utils::INPUT_GPC);
		$COMPOSE = null;

		if ($COMPOSE_ID && $_SESSION['compose_data_' . $COMPOSE_ID]) {
			$SESSION_KEY = 'compose_data_' . $COMPOSE_ID;
			$COMPOSE = & $_SESSION[$SESSION_KEY];
		}
		if (!$COMPOSE) {
			die("Invalid session var!");
		}
		$rcmail = rcmail::get_instance();
		$index = 0;
		
                $attachments = [];
                $attachments = array_merge($attachments, self::getDetails());

		foreach ($attachments as $attachment) {
			$index++;
			$attachment['group'] = $COMPOSE_ID;
			$userid = rcmail::get_instance()->user->ID;
			list($usec, $sec) = explode(' ', microtime());
			$id = preg_replace('/[^0-9]/', '', $userid . $sec . $usec).$index;
			$attachment['id'] = $id;

			$_SESSION['plugins']['filesystem_attachments'][$COMPOSE_ID][$id] = $attachment['path'];
			$rcmail->session->append($SESSION_KEY . '.attachments', $id, $attachment);
			if (($icon = $COMPOSE['deleteicon']) && is_file($icon)) {
				$button = html::img(array(
					'src' => $icon,
					'alt' => $rcmail->gettext('delete')
				));
			} else if ($COMPOSE['textbuttons']) {
				$button = rcube::Q($rcmail->gettext('delete'));
			} else {
				$button = '';
			}

			$content = html::a(array(
				'href' => "#delete",
				'onclick' => sprintf("return %s.command('remove-attachment','rcmfile%s', this)", rcmail_output::JS_OBJECT_NAME, $id),
				'title' => $rcmail->gettext('delete'),
				'class' => 'delete',
				'aria-label' => $rcmail->gettext('delete') . ' ' . $attachment['name'],
				), $button
			);

			$content .= rcube::Q($attachment['name']);
			$html .= 'window.rcmail.add2attachment_list("rcmfile'.$id.'",{html:"<a href=\"#delete\" onclick=\"return rcmail.command(\'remove-attachment\',\'rcmfile'.$id.'\', this)\" title=\"'.$rcmail->gettext('delete').'\" class=\"delete\" aria-label=\"'.$rcmail->gettext('delete').' '.$attachment['name'].'\"><\/a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$attachment['name'].'",name:"'.$attachment['name'].'",mimetype:"'.$attachment['mimetype'].'",classname:"'.rcube_utils::file2class($attachment['mimetype'], $attachment['name']).'",complete:true},"'.$uploadid.'");'.PHP_EOL;
		}
		$response = '<!DOCTYPE html>
			     <html lang="en">
			     <head><meta http-equiv="content-type" content="text/html; charset=UTF-8" />
			     <script type="text/javascript">
				if (window && window.rcmail) {
					window.rcmail.iframe_loaded("");
					'.$html.'
					window.rcmail.auto_save_start(false);
				}
			    </script>
			    </head>
			    </html>';
		echo $response; 
		exit;
	}

	//Get document details
	public function getDetails() {
		$attachments = [];
		$ids = rcube_utils::get_input_value('ids', rcube_utils::INPUT_GPC);
		if (!isset($ids)) {
			return $attachments;
		}
		$rcmail = rcmail::get_instance();
		$db = $rcmail->get_dbh();		
		$ids = implode(',', $ids);
		$userid = $rcmail->user->ID;
		$index = 0;
		$sql_result = $db->query("SELECT jo_attachments.* FROM jo_attachments JOIN jo_seattachmentsrel ON jo_seattachmentsrel.attachmentsid=jo_attachments.attachmentsid WHERE jo_seattachmentsrel.crmid IN ($ids);");
		while ($row = $db->fetch_assoc($sql_result)) {
			$crmDoc = $rcmail->config->get('root_directory') . $row['path'] . $row['attachmentsid'] . '_' . $row['name'];
			list($usec, $sec) = explode(' ', microtime());
			$filepath = $rcmail->config->get('root_directory') . 'modules/EmailPlus/roundcube/temp/'.$sec.$userid.$row['attachmentsid'].$index.'.tmp';
			if (file_exists($crmDoc)) {
				copy($crmDoc, $filepath);
				$attachment = [
					'path' => $filepath,
					'size' => filesize($filepath),
					'name' => $row['name'],
					'mimetype' => rcube_mime::file_content_type($filepath, $row['name'], $row['type']),
				];
				$attachments[] = $attachment;
			}
			$index++;
		}
		return $attachments;
	}
	
}
