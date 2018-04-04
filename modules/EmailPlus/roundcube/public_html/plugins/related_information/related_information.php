<?php
/**
 * ContextMenu
 *
 * Plugin to add a context menu to various parts of the interface
 *
 * @author Fredrick Marks
 *
 * Copyright (C) 2018 Smackcoders, Inc
 *
 * This program is a Roundcube (http://www.roundcube.net) plugin.
 * For more information see README.md.
 * See MANUAL.md for information about extending this plugin.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Roundcube. If not, see http://www.gnu.org/licenses/.
 */

class related_information extends rcube_plugin {

	public $task = 'mail';

	function init() {
		$rcmail = rcube::get_instance();
		$this->include_script('relatedinfo.js');

		if ($rcmail->output->type == 'html') {
			$this->include_stylesheet($this->local_skin_path() . '/relatedinfo.css');
			$this->include_stylesheet($this->local_skin_path() . '/glyphicon.css');
			$this->api->output->set_env('relatedinfo', true);
		}
		if ($rcmail->task == 'mail') {
			$this->add_hook('message_widget', array($this, 'addition_addressbook_options'));
		}
	}

	public function addition_addressbook_options()
	{
		$RC = rcube::get_instance();
		$DB = rcube_db::factory($RC->config->get('db_dsnw'));

		$DB->set_debug((bool)$RC->config->get('sql_debug'));

		// Connect to database
		$DB->db_connect('w');

		global $MESSAGE, $RCMAIL;
		$sender_mail_id = $MESSAGE->sender['mailto'];
		$contact = $DB->query("select con.contactid, con.contact_no, con.accountid, con.firstname, con.lastname, con.email, con.phone, con.mobile, con.secondaryemail, acc.account_no, acc.accountname, acc.website, conadd.mailingpobox, conadd.mailingstreet, conadd.mailingcity, conadd.mailingstate, conadd.mailingcountry, mailingzip from jo_contactdetails con join jo_account acc on con.accountid = acc.accountid join jo_contactaddress conadd on conadd.contactaddressid = con.contactid where email = '$sender_mail_id'");
		
		while($response = $DB->fetch_assoc($contact)) {
			break;
		}

		if(empty($response)){
			$response = $this->notRelatingWithAccount($DB, $sender_mail_id);
		}
		
		$contact_id = $response['contactid'];
		$account_id = $response['accountid'];
		$detailed_info = "<p>" . $response['accountname'] . "</p>";
		$detailed_info .= "<p>" . $response['firstname'] . " " . $response['lastname'] . "</p>";
		$detailed_info .= "<p>" . $response['email'] . "</p>";
		$detailed_info .= "<p>" . $response['secondaryemail'] . "</p>";
		$detailed_info .= "<p>" . $response['phone'] . "</p>";
		$detailed_info .= "<p>" . $response['mobile'] . "</p>";
		$address_info = "<p>" . $response['mailingpobox'] . " " . $response['mailingstreet'] . "</p>";
		$address_info .= "<p>" . $response['mailingcity'] . " " . $response['mailingstate'] . " " . $response['mailingcountry'] . " " . $response['mailingzip'] . "</p>";

		$getOrganisation = $DB->query("select accountname, website from jo_account where accountid = $accountid");
		while($organisationInfo = $DB->fetch_assoc($getOrganisation)) {
			$organisation[] = $organisationInfo;
		}

		$getOpportunities = $DB->query("select potentialname, sales_stage, round(amount, 2) as amount from jo_potential where contact_id = $contact_id");
		while($opportunityInfo = $DB->fetch_assoc($getOpportunities)) {
                        $opportunities[] = $opportunityInfo;
                }

		$getQuotes = $DB->query("select subject, quotestage, round(total, 2) as total from jo_quotes where contactid = $contact_id");
		while($quotesInfo = $DB->fetch_assoc($getQuotes)) {
			$quotes[] = $quotesInfo;
		}

		$getSalesOrder = $DB->query("select subject, sostatus, round(total, 2) as total from jo_salesorder where contactid = $contact_id");
		while($salesOrderInfo = $DB->fetch_assoc($getSalesOrder)) {
			$salesOrders[] = $salesOrderInfo;
		}

		$getTroubleTickets = $DB->query("select title, status from jo_troubletickets where contact_id = $contact_id");
		while($troubleTicketInfo = $DB->fetch_assoc($getTroubleTickets)) {
			$troubleTickets[] = $troubleTicketInfo;
		}

		$html = '<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
			<style type="text/css">
			#mainscreencontent {
			        width: 99%;
			}
			#mainscreen {
			        width: 76%;
			}
			#messageDetailview > div#mailview-right {
			        width: 78% !important;
			}
			</style>
			<div id="relatedInformation" class="relatedInformation">
			<div id="left-pane-icons" class="left-pane-icons">
			 	<!-- Organisation -->
				<!-- Contact Summary -->';
				$html .= '<ul class="nav nav-tabs" id="related-info">
				<li data-id="detailedInfo" class="active"><a data-toggle="tab"><i class="glyphicon glyphicon-list"></i></a></li>
				<!--<li data-id="eventsInfo"><a data-toggle="tab"><i class="glyphicon glyphicon-calendar"></i></a></li>-->
				<li data-id="opportunityInfo"><a data-toggle="tab"><i class="glyphicon glyphicon-user"></i></a></li>
				<li data-id="quotesInfo"><a data-toggle="tab"><i class="glyphicon glyphicon-usd"></i></a></li>
				<li data-id="salesorderInfo"><a data-toggle="tab"><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
				<li data-id="tasksInfo"><a data-toggle="tab"><i class="glyphicon glyphicon-tasks"></i></a></li>
				</ul>
			</div>
			<div class="tab-content">
			<div id="detailedInfo" class="detailed-info tab-pane fade in active">
				<h5>Detailed Information</h5>
				<p><i class="fa fa-building-o"></i> ' . $response["accountname"] . '</p>
				<p><i class="glyphicon glyphicon-user"></i> ' . $response["firstname"] . " " . $response['lastname'] . '</p>
				<p><i class="fa fa-envelope"></i> ' . $response["email"] . '</p>
				<p><i class="fa fa-envelope-o"></i> ' . $response["secondaryemail"] . '</p>
				<p><i class="fa fa-phone-square"></i> ' . $response["phone"] . '</p>
				<p><i class="fa fa-phone"></i> ' . $response["mobile"] . '</p>
				<br>
				<h5>Address</h5>
				<div>
				<div style="font-size: 20px; float: left; padding: 10px 10px 0px 20px;"><i class="glyphicon glyphicon-picture"></i></div>
				<div style="float:left;">
				<p>' . $response["mailingpobox"] . " " . $response["mailingstreet"] . '</p>
				<p>' . $response["mailingcity"] . " " . $response["mailingstate"] . " " . $response["mailingcountry"] . " " . $response["mailingzip"] . '</p>
				</div>
				</div>';
	if($contact_id){
		$html.='<div style="position: absolute; margin: 60px 0px 0px 20px;">
				<a href="'.$RCMAIL->config->get('siteurl').'index.php?module=Contacts&view=Edit&record='.$contact_id.'" target="_blank"><input type="button" value="Edit" class="related-button" ></a>
				<a href="'.$RCMAIL->config->get('siteurl').'index.php?module=Contacts&view=Detail&record='.$contact_id.'" target="_blank"><input type="button" value="Show Details" class="related-button" style="background: #5BC0DE !important;"></a>
				</div>';
	}
	$html.='</div>
	
		<div id="eventsInfo" class="events-info tab-pane fade">
				<h5>Tasks & Events</h5>
			</div>
			<div id="opportunityInfo" class="opportunity-info tab-pane fade">
				<h5>Opportunities</h5>
				<table class="table rel-info">
					<thead>
						<th>Name</th>
						<th>Stage</th>
						<th>Amount</th>
					</thead>
					<tbody>';
					foreach($opportunities as $opKey => $opVal) {
						$html .= '<tr>
							<td>' . $opVal["potentialname"] . '</td><td style="text-align:center;">' . $opVal["sales_stage"] . '</td><td style="text-align:right;">' . $opVal["amount"] . '</td>
						</tr>';
					}
					$html .= '</tbody>
				</table>
			</div>
			<div id="quotesInfo" class="quotes-info tab-pane fade">
				<h5>Quotes</h5>
				<table class="table">
					<thead>
						<th>Name</th>
						<th>Stage</th>
						<th>Amount</th>
					</thead>
					<tbody>';
					foreach($quotes as $quoKey => $quoVal) {
						$html .= '<tr>
							<td>' . $quoVal["subject"] . '</td><td style="text-align:center;">' . $quoVal["quotestage"] . '</td><td style="text-align:right;">' . $quoVal["total"] . '</td>
						</tr>';
					}
					$html .= '</tbody>
				</table>
			</div>
			<div id="salesorderInfo" class="salesorder-info tab-pane fade">
				<h5>Orders</h5>
				<table class="table">
					<thead>
						<th>Name</th>
						<th>Stage</th>
						<th>Amount</th>
					</thead>
					<tbody>';
					foreach($salesOrders as $soKey => $soVal) {
						$html .= '<tr>
							<td>' . $soVal["subject"] . '</td><td style="text-align:center;">' . $soVal["sostatus"] . '</td><td style="text-align:right;">' . $soVal["total"] . '</td>
						</tr>';
					}
					$html .= '</tbody>
				</table>
			</div>
			<div id="tasksInfo" class="tasks-info tab-pane fade">
				<h5>Trouble Tickets</h5>
				<table class="table">
					<thead>
						<th>Subject</th>
						<th>Status</th>
					</thead>
					<tbody>';
					foreach($troubleTickets as $trtKey => $trtVal) {
						$html .= '<tr>
							<td>' . $trtVal["title"] . '</td><td style="text-align:center;">' . $trtVal["status"] . '</td>
						</tr>';
					}
					$html .= '</tbody>
				</table>
			</div>
			</div>
		</div>';
		print $html;
	}

	function notRelatingWithAccount($DB, $sender_mail_id)
	{
		$contact = $DB->query("select con.contactid, con.contact_no, con.accountid, con.firstname, con.lastname, con.email, con.phone, con.mobile, con.secondaryemail, conadd.mailingpobox, conadd.mailingstreet, conadd.mailingcity, conadd.mailingstate, conadd.mailingcountry, mailingzip from jo_contactdetails con join jo_contactaddress conadd on conadd.contactaddressid = con.contactid where email = '$sender_mail_id'");
		while($response = $DB->fetch_assoc($contact)) {
			break;
		}
		return $response;
	}

}
