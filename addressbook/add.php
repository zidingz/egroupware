<?php
/**************************************************************************\
* phpGroupWare - addressbook                                               *
* http://www.phpgroupware.org                                              *
* Written by Joseph Engo <jengo@phpgroupware.org>                          *
* --------------------------------------------                             *
*  This program is free software; you can redistribute it and/or modify it *
*  under the terms of the GNU General Public License as published by the   *
*  Free Software Foundation; either version 2 of the License, or (at your  *
*  option) any later version.                                              *
\**************************************************************************/

/* $Id$ */

	if ($submit || $AddVcard) {
		$phpgw_info["flags"] = array(
			"noheader" => True,
			"nonavbar" => True
		);
	}
	
	$phpgw_info["flags"]["currentapp"] = "addressbook";
	$phpgw_info["flags"]["enable_addressbook_class"] = True;
	include("../header.inc.php");
	
	$t = new Template($phpgw->common->get_tpl_dir("addressbook"));
	$t->set_file(array("add" => "add.tpl"));
	
	$this = CreateObject("phpgwapi.contacts");
	
	if ($AddVcard){
		Header("Location: " . $phpgw->link("/addressbook/vcardin.php"));
	} else if ($add_email) {
		list($fields["firstname"],$fields["lastname"]) = explode(" ", $name);
		$fields["email"] = $add_email;
		addressbook_form("","add.php","Add",$fields);
	} else if (! $submit && ! $add_email) {
		addressbook_form("","add.php","Add","");
	} else {
		if (! $bday_month && ! $bday_day && ! $bday_year) {
			$bday = "";
		} else {
			$bday = "$bday_month/$bday_day/$bday_year";
		}

		if ($url == "http://") {
			$url = "";
		}

		$fields["org_name"]			= $company;
		$fields["org_unit"]			= $department;
		$fields["n_given"]			= $firstname;
		$fields["n_family"]			= $lastname;
		$fields["n_middle"]			= $middle;
		$fields["n_prefix"]			= $prefix;
		$fields["n_suffix"]			= $suffix;
		if ($prefix) { $pspc = " "; }
		if ($middle) { $mspc = " "; }
		if ($suffix) { $sspc = " "; }
		$fields["fn"]				= $prefix.$pspc.$firstname.$mspc.$middle.$mspc.$lastname.$sspc.$suffix;
		$fields["d_email"]			= $email;
		$fields["d_emailtype"]		= $email_type;
		$fields["title"]			= $title;
		$fields["a_tel"]			= $wphone;
		$fields["a_tel_work"]		= "y";
		$fields["b_tel"]			= $hphone;
		$fields["b_tel_home"]		= "y";
		$fields["c_tel"]			= $fax;
		$fields["c_tel_fax"]		= "y";
		$fields["pager"]			= $pager;
		$fields["mphone"]			= $mphone;
		$fields["ophone"]			= $ophone;
		$fields["adr_street"]		= $street;
		$fields["address2"]			= $address2;
		$fields["adr_locality"]		= $city;
		$fields["adr_region"]		= $state;
		$fields["adr_postalcode"]	= $zip;
		$fields["adr_countryname"]	= $country;
		$fields["tz"]				= $timezone;
		$fields["bday"]				= $bday;
		$fields["url"]				= $url;
		$fields["note"]				= $notes;
	
		addressbook_add_entry($phpgw_info["user"]["account_id"],$fields);
		$ab_id = addressbook_get_lastid();

		Header("Location: " . $phpgw->link("/addressbook/view.php","ab_id=$ab_id&order=$order&sort=$sort&filter=$filter&start=$start"));
		$phpgw->common->phpgw_exit();
	}

	$t->set_var("lang_ok",lang("ok"));
	$t->set_var("lang_clear",lang("clear"));
	$t->set_var("lang_cancel",lang("cancel"));
	$t->set_var("cancel_url",$phpgw->link("/addressbook/index.php","sort=$sort&order=$order&filter=$filter&start=$start"));
	$t->parse("out","add");
	$t->pparse("out","add");
	
	$phpgw->common->phpgw_footer();
?>
