<?php
/**
 * Customer controller
 *
 */
class Customer_IndexController extends BaseController
{

	/**
	 * Default action
	 * @return void
	 */
	public function indexAction ()
	{
		$o_CountryTable = new Model_Table_Country();
		$this->view->headScript()->appendFile($this->sz_fGetPublicHost() . 'js/modules/customer.js', 'text/javascript');
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/jquery.multiFieldExtender-2.0.js', 'text/javascript');
		$this->view->headTitle('Customer Account');
		$this->view->assign('a_Countries', $o_CountryTable->a_fGetAllCountry);
	}

	public function createAction ()
	{
		$o_MessageHandle = new Model_MessageHandle();
		$o_CustomerModel = new Model_Customer();
		$a_Params = $this->v_fClean($this->_request->getParams());
		$b_Valid = $o_CustomerModel->b_fValidate($a_Params, $o_MessageHandle);
		echo '<pre>'; echo($o_MessageHandle);die;
		$phone = mysql_real_escape_string(trim($_POST['phone']));
		$location = mysql_real_escape_string(trim($_POST['location']));
		$firstname = mysql_real_escape_string(trim($_POST['firstname']));
		$email = mysql_real_escape_string(trim($_POST['email']));
		$passwd = mysql_real_escape_string(trim($_POST['password']));
		$country = mysql_real_escape_string(trim($_POST['country']));
		if ($country == "Please Select") {
			$country = "";
		}
		if ($country != "" && $location == "") {
			$o_Response->a_ErrorMessages[] = "Please enter state";
		}
		$state = mysql_real_escape_string(trim($_POST['state']));
		$fund = mysql_real_escape_string(trim($_POST['fund']));
		$dest1 = mysql_real_escape_string(trim($_POST['dest1']));
		$direct_dial = mysql_real_escape_string(trim($_POST['direct_dial']));
		$spd_dial1 = mysql_real_escape_string(trim($_POST['spd_dial1']));
		$phone1 = mysql_real_escape_string(trim($_POST['phone1']));
		for ($val = 1; $val <= 3; $val ++) {
			$spd_dial = mysql_real_escape_string(trim($_POST['spd_dial' . $val]));
			$dest = mysql_real_escape_string(trim($_POST['dest' . $val]));
			if ($spd_dial == 'Name') {
				$spd_dial = "";
			}
			if ($dest == 'Destination') {
				$dest = "";
			}
			if ($spd_dial != "" && $dest != "") {
				//echo $spd_dial.$dest;
				if (! is_numeric($dest)) {
					$o_Response->a_ErrorMessages[] = "Destination number must be numeric";
				}
			}
		}
		for ($cl = 1; $cl <= 3; $cl ++) {
			$phone = $_POST['phone' . $cl];
			if (! empty($phone)) {
				$chkqry = mysql_query("SELECT cid FROM cc_callerid WHERE cid='$phone'", $connA);
				if (mysql_num_rows($chkqry) > 0) {
					$o_Response->a_ErrorMessages[] = "Phone Number already exists.";
				} else {
					if (! is_numeric($phone)) {
						$o_Response->a_ErrorMessages[] = "Caller ID must be numeric";
					}
				}
			}
		}
		if ($firstname != "" && $passwd != "") {
			if ($fund != "") {
				if (! is_numeric($fund)) {
					$o_Response->a_ErrorMessages[] = "Add fund must be numeric.";
				}
			}
			if (empty($o_Response->a_ErrorMessages)) {
				include_once ("addCode.php");
				//code for direct dial
				if ($location != "") {
					$didnumberAndID = mysql_real_escape_string(trim($_POST['didnumber']));
					$rst = str_replace("_", ",", $didnumberAndID);
					$exp_rst = explode(",", $rst);
					$didID = $exp_rst[0];
					$didnumber = $exp_rst[1];
					$number = mysql_real_escape_string(trim($_POST['number']));
					$name = mysql_real_escape_string(trim($_POST['name']));
					if (! empty($didnumberAndID) && ! empty($number) && ! empty($name)) {
						$UserDidID = mysql_fetch_object(mysql_query("SELECT dd_did_id FROM dd_contacts WHERE id_cc_card = '$id_cc_card' AND local_phone = '$didnumber'", $connA))->dd_did_id;
						if (empty($UserDidID)) {
							$did = mysql_query("UPDATE dd_did_numbers SET chan_used = (chan_used+1) WHERE dd_did_id='$didID'", $connA);
							$dd_contacts_qry = "INSERT INTO dd_contacts SET
                                                                creationdate   =  NOW(),
                                                                id_cc_card    =  '$id_cc_card',
                                                                contact_name  = '$name',
                                                                contact_country = '',
                                                                contact_phone  =  '$number',
                                                                dd_did_id     =    '$didID',
                                                                local_phone   =   '$didnumber'";
							mysql_query($dd_contacts_qry, $connA);
							//$succmsg = "This Number is assigned successfuly.";
						} else {
							$o_Response->a_ErrorMessages[] = "This number is already assigned.";
						}
					} else {
						$o_Response->a_ErrorMessages[] = "All direct dial  fields are required.";
					}
				}
			}
		} else {
			$o_Response->a_ErrorMessages[] = "Please enter required fields.";
		}
		if (! empty($o_Response->a_ErrorMessages)) {
			foreach ($o_Response->a_ErrorMessages as $message) {
				$o_Response->sz_ErrorMessage .= $message . PHP_EOL;
			}
		}
		print_r(json_encode($o_Response));
		die();
	}

	public function searchAction ()
	{
		// ajax Request
		$this->_diableView = true;
	}
}
?>