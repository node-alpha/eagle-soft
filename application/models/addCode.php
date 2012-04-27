<?php
function gen_sipid() {

	$pwd = "";
	$chrs = 7;
	mt_srand ((double) microtime() * 1000000);
	$i=0;

	while (strlen($pwd)<$chrs) {
		$i++;
		$chr = chr(mt_rand (0,255));
		if (eregi("^[0-9]$", $chr)) { $pwd = $pwd.$chr; }
		if($i==1 && $pwd==0) { $pwd=""; $i=0; }
	}
	return '999'.$pwd;
}

function gen_username() {

	$pwd = "";
	$chrs = 10;
	mt_srand ((double) microtime() * 1000000);
	$i=0;

	while (strlen($pwd)<$chrs) {
		$i++;
		$chr = chr(mt_rand (0,255));
		if (eregi("^[0-9]$", $chr)) { $pwd = $pwd.$chr; }
		if($i==1 && $pwd==0) { $pwd=""; $i=0; }
	}
	return $pwd;
}

for($i=0;$i<2000;$i++) {
	$username = gen_username();
	$query = mysql_query("SELECT username FROM cc_card WHERE username = '$username'", $connA);	
	if(mysql_num_rows($query) > 0) { continue;}
	else { break; }
}

for($i=0;$i<2000;$i++) {
	$sipid = gen_sipid();
	$query = mysql_query("SELECT name FROM cc_sip_buddies WHERE name LIKE '$sipid'", $connA);
	if(mysql_num_rows($query) > 0) { continue;}
	else { break; }
}


$uniquecode=md5($firstname.$phone.$currentdate.rand(1111,9999));
$cc_card_qry = "INSERT INTO cc_card (id, creationdate, firstusedate, expirationdate, enableexpire, expiredays, username, useralias, 
uipass, type, credit, tariff, id_didgroup, activated, status, lastname, firstname, address, city, state, country, zipcode, phone, email, fax, 
inuse, simultaccess, currency, lastuse, nbused, typepaid, creditlimit, voipcall, sip_buddy, iax_buddy, language, redial, runservice, 
nbservice, id_campaign, num_trials_done, vat, servicelastrun, initialbalance, invoiceday, autorefill, loginkey, mac_addr, id_timezone, 
tag, voicemail_permitted, voicemail_activated, last_notification, email_notification, notify_email, credit_notification, id_group, 
company_name, company_website, vat_rn, traffic, traffic_target, discount, restriction, id_seria, serial, block, lock_pin, lock_date, 
refills_overal, bonus_get, refills_cleared, reseller_id) VALUES 
(NULL, NOW(), '0000-00-00 00:00:00', DATE_ADD(NOW(), INTERVAL 10 YEAR), 0, 0, '$username', '$email', '$passwd', 'W', '$fund', 1, -1, 't', 
1, '$lastname', '$firstname', '$address', '$city', '$state', '$country', '$zipcode', '$phone', '$email', '', 0, 1, 'USD', NOW(), 3, 0, 0, 0, 1, 1, 'en', 
'', 0, 0, -1, 0, 0, '0000-00-00 00:00:00', 0.00000, 1, 0, '$uniquecode', '00-00-00-00-00-00', 49, '', 0, 0, NULL, '', 0, -1, 1, '', '', NULL, NULL, 
'', 0.00, 0, -1, NULL, 0, '0', NULL, 0.00000, 0.00000, 0.00000, '$agentid')";

mysql_query($cc_card_qry, $connA);
 

$id_cc_card = mysql_fetch_object(mysql_query("SELECT id FROM cc_card WHERE username LIKE '$username' AND email LIKE '$email'", $connA))->id;

 
    for($phno=1;$phno<=3;$phno++)
    {
      $phone = $_POST['phone'.$phno];
        if($phone!="" && $id_cc_card!="")
        {
                if(substr($phone,0,1) == "+")  { $phone = substr($phone, 1); }
                if(substr($phone,0,2) == "00")  { $phone = substr($phone, 2); }

                $cc_cid_qry = "INSERT INTO cc_callerid SET cid = '$phone', id_cc_card = '$id_cc_card',creationdate=NOW(), activated = 't'";
                mysql_query($cc_cid_qry, $connA);
            
        }
    }


//Speed dial

     for($sp=1;$sp<=3;$sp++)
    {
     $spd_dial_name = $_POST['spd_dial'.$sp];
     $dest     = $_POST['dest'.$sp];
     if($spd_dial_name == 'Name'){$spd_dial_name="";}
     if($dest     == 'Destination'){$dest="";}
        if($spd_dial_name!="" && $dest!="")
        {//echo $spd_dial_name.$dest;
            $chkqry_cc_spd_dial=mysql_query("SELECT speeddial FROM cc_speeddial WHERE speeddial='$sp' AND id_cc_card LIKE '$id'",$connA);
                if(mysql_num_rows($chkqry_cc_spd_dial)==0)
                {
                        $nsrtqry_spddial = mysql_query("INSERT INTO cc_speeddial  
                                 SET speeddial = '$sp',
                                       name    = '$spd_dial_name',
                                       phone   = '$dest',
                                creationdate   = Now(),
                                    id_cc_card ='$id_cc_card'",$connA);	
                        
                            
                }
                
        }
    }
//end
if($id_cc_card!="")
{
  $cc_sip_qry = "INSERT INTO cc_sip_buddies (id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, 
canreinvite, context, DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, 
nat, deny, permit, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, secret, type, username, disallow, allow, 
musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar, regserver, lastms, defaultuser, auth, subscribemwi, vmexten, 
cid_number, callingpres, usereqphone, incominglimit, subscribecontext, musicclass, mohsuggest, allowtransfer, autoframing, 
maxcallbitrate, rtpkeepalive, useragent) VALUES
(NULL, '$id_cc_card', '$sipid', '$username', '$sipid', 'billing', NULL, '', 'no', 'ctx-sip', NULL, 'RFC2833', '', '', 'dynamic', '', 
NULL, '$sipid', '', 'yes', '', NULL, '', NULL, '', 'no', NULL, NULL, NULL, '$passwd', 'friend', '$sipid', 'ALL', 'ulaw,g729', '', 0, '', 
'yes', '', '', NULL, '0', '$sipid', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '')";
mysql_query($cc_sip_qry, $connA);

}
if($fund!="")
        {
                $idcccrd = mysql_fetch_object(mysql_query("SELECT id FROM cc_card WHERE loginkey='$uniquecode'"))->id;
                $credit=mysql_fetch_object(mysql_query("SELECT credit FROM cc_agent WHERE id='$agentid'"))->credit;
                if($credit>=$fund && $credit!='0' )
                    {
                    if($fund>'0')
                       {
						
                                                 $fund1=($fund * 15)/100;
						 mysql_query("UPDATE cc_agent SET credit = (credit - '$fund' + '$fund1') WHERE id = '$agentid'",$connA);
				                $insQryUser     =   "INSERT INTO cc_logrefill SET
						date        =   '$currentdate',
						card_id     =   '$idcccrd',
						refill_type =   '0',
						description =   '$description',
						credit      =   '$fund',
                                                ref_id      =   '$agentid',
                                                payment_type='Add Fund',
						balancebefore='$credit',
						balanceafter=('$credit' - '$fund' +'$fund1')";
						//echo $insQryUser;
						mysql_query($insQryUser,$connA);
					/*
						$insQryagent     =   "INSERT INTO cc_logrefill_agent SET
                                                date        =   '$currentdate',
						agent_id    =   '$agentid',
                                                refill_type =   '0',
                                                description =   'New Customer Commission',
                                                credit      =   '$fund1'";
                                                //echo $insQryUser;
                                                mysql_query($insQryagent,$connA);						 
                                        */  
					      $succmsg="Recharged Successfully.";
                       }
                       else
                       {
                         $errmsg="Fund must be positive number.";
                       }

                    }
		    else
		    {
			  $errmsg="Agent account do not have sufficient fund.";
		    }
        }
	if($errmsg!="")
	{
          $qrydl = mysql_query("DELETE FROM cc_card WHERE loginkey = '$uniquecode'");
          $qrydl = mysql_query("DELETE FROM cc_sip_buddies WHERE id_cc_card = '$idcccrd'");
          $qrydl = mysql_query("DELETE FROM cc_callerid WHERE cc_callerid = '$idcccrd'"); 
	}
?>
