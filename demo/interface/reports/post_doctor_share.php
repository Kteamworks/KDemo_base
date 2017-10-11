<?php 
/**
 * This is a report of Financial Summary by Service Code.
 *
 * This is a summary of service code charge/pay/adjust and balance,
 * with the ability to pick "important" codes to either highlight or
 * limit to list to. Important codes can be configured in
 * Administration->Service section by assigning code with
 * 'Service Reporting'.
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Visolve
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes=true;
$fake_register_globals=false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");


            $count = count($_POST['form_provider']);
            $fields = [];
			$provider = $_POST['form_provider'];
			$amount = $_POST['amount'];
			$bill_no = $_POST['bill_change'];
            for ($i = 0; $i <= $count; $i++) {
                if (!empty($_POST['form_provider'][$i])) {
                    //$name = str_slug(Input::get('name')[$i], '_');
                    $field = sqlQuery("INSERT into billing_master (Bill_Id,Bill_Payment_Id,Bill_Amount) values ('$bill_no','$provider[$i]','$amount[$i]')");

                }
            }

?>