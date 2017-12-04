<?php

/*
  $Id: address_book_process.php,v 1.2 2003/09/24 13:57:00 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once('includes/application_top.php');

if(!tep_session_is_registered('customer_id'))
{
//    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

// needs to be included earlier to set the success message in the messageStack
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS);

if(isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete']))
{
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int) $_GET['delete'] . "' and customers_id = '" . (int) $customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
}

// error checking when updating or adding an entry
$process = false;

// +Country-State Selector

$refresh = false;
if(isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update') || ($_POST['action'] == 'refresh')))
{
    if($_POST['action'] != 'refresh')
    {
        $process = true;
    }
    else
    {
        $refresh = true;
    }
    // -Country-State Selector

    $process = true;
    $error = false;

    if(ACCOUNT_GENDER == 'true')
    {
        $gender = tep_db_prepare_input($_POST['gender']);
    }
    if(ACCOUNT_COMPANY == 'true')
    {
        $company = tep_db_prepare_input($_POST['company']);
    }
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if(ACCOUNT_STREET_ADDRESS == 'true')
    {
        $street_address = tep_db_prepare_input($_POST['street_address']);
    }
    if(ACCOUNT_SUBURB == 'true')
    {
        $suburb = tep_db_prepare_input($_POST['suburb']);
    }
    if(ACCOUNT_POSTCODE == 'true')
    {
        $postcode = tep_db_prepare_input($_POST['postcode']);
    }
    if(ACCOUNT_CITY == 'true')
    {
        $city = tep_db_prepare_input($_POST['city']);
    }
    if(ACCOUNT_COUNTRY == 'true')
    {
        $country = tep_db_prepare_input($_POST['country']);
    }
    if(ACCOUNT_STATE == 'true')
    {
        if(isset($_POST['zone_id']))
        {
            $zone_id = tep_db_prepare_input($_POST['zone_id']);
        }
        else
        {
            $zone_id = false;
        }
        $state = tep_db_prepare_input($_POST['state']);
    }

    // +Country-State Selector
    if($refresh)
    {
        $state = '';
    }
    if($process)
    {
        // -Country-State Selector
        if(ACCOUNT_GENDER == 'true')
        {
            if(($gender != 'm') && ($gender != 'f'))
            {
                $error = true;

                $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
            }
        }

        if(strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH)
        {
            $error = true;

            $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
        }

        if(strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH)
        {
            $error = true;

            $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
        }

        if(ACCOUNT_STREET_ADDRESS == 'true')
        {
            if(strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH)
            {
                $error = true;

                $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
            }
        }

        if(ACCOUNT_POSTCODE == 'true')
        {
            if(strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH)
            {
                $error = true;

                $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
            }
        }

        if(ACCOUNT_CITY == 'true')
        {
            if(strlen($city) < ENTRY_CITY_MIN_LENGTH)
            {
                $error = true;

                $messageStack->add('addressbook', ENTRY_CITY_ERROR);
            }
        }

        if(ACCOUNT_COUNTRY == 'true')
        {
            if(!is_numeric($country))
            {
                $error = true;

                $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
            }
        }

        if(ACCOUNT_STATE == 'true')
        {
            // +Country-State Selector
            if($zone_id == 0)
            {
                // -Country-State Selector
                if(strlen($state) < ENTRY_STATE_MIN_LENGTH)
                {
                    $error = true;

                    $messageStack->add('addressbook', ENTRY_STATE_ERROR);
                }
            }
        }
        // +Country-State Selector
    }
    if(!$refresh)
    {
        // -Country-State Selector
        if($error == false)
        {
            $sql_data_array = array('entry_firstname' => $firstname,
                'entry_lastname' => $lastname,
                'entry_street_address' => $street_address,
                'entry_postcode' => $postcode,
                'entry_city' => $city,
                'entry_country_id' => (int) $country);

            if(ACCOUNT_GENDER == 'true')
            {
                $sql_data_array['entry_gender'] = $gender;
            }
            if(ACCOUNT_COMPANY == 'true')
            {
                $sql_data_array['entry_company'] = $company;
            }
            if(ACCOUNT_SUBURB == 'true')
            {
                $sql_data_array['entry_suburb'] = $suburb;
            }
            if(ACCOUNT_STATE == 'true')
            {
                if($zone_id > 0)
                {
                    $sql_data_array['entry_zone_id'] = (int) $zone_id;
                    $sql_data_array['entry_state'] = '';
                }
                else
                {
                    $sql_data_array['entry_zone_id'] = '0';
                    $sql_data_array['entry_state'] = $state;
                }
            }

            if($_POST['action'] == 'update')
            {
                tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int) $_GET['edit'] . "' and customers_id ='" . (int) $customer_id . "'");

                // reregister session variables
                if((isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $customer_default_address_id))
                {
                    $customer_first_name = $firstname;
                    $customer_country_id = $country;
                    $customer_zone_id = (($zone_id > 0) ? (int) $zone_id : '0');
                    $customer_default_address_id = (int) $_GET['edit'];

                    $sql_data_array = array(
                        'customers_firstname' => $firstname,
                        'customers_lastname' => $lastname,
                        'customers_default_address_id' => (int) $_GET['edit']
                    );

                    if(ACCOUNT_GENDER == 'true')
                    {
                        $sql_data_array['customers_gender'] = $gender;
                    }
                    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int) $customer_id . "'");
                }
            }
            else
            {
                $sql_data_array['customers_id'] = (int) $customer_id;
                tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

                $new_address_book_id = tep_db_insert_id();

// reregister session variables
                if(isset($_POST['primary']) && ($_POST['primary'] == 'on'))
                {
                    $customer_first_name = $firstname;
                    $customer_country_id = $country;
                    $customer_zone_id = (($zone_id > 0) ? (int) $zone_id : '0');
                    if(isset($_POST['primary']) && ($_POST['primary'] == 'on'))
                    {
                        $customer_default_address_id = $new_address_book_id;
                    }

                    $sql_data_array = array(
                        'customers_firstname' => $firstname,
                        'customers_lastname' => $lastname
                    );

                    if(ACCOUNT_GENDER == 'true')
                    {
                        $sql_data_array['customers_gender'] = $gender;
                    }
                    if(isset($_POST['primary']) && ($_POST['primary'] == 'on'))
                    {
                        $sql_data_array['customers_default_address_id'] = $new_address_book_id;
                    }

                    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int) $customer_id . "'");
                }
            }

            $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');

            tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
        }
    }

// +Country-State Selector
}

if($refresh)
{
    // Recreate $entry from post values collected above
    $entry = array(
        'entry_firstname' => $firstname,
        'entry_lastname' => $lastname,
        'entry_street_address' => $street_address,
        'entry_postcode' => $postcode,
        'entry_city' => $city,
        'entry_state' => "",
        'entry_zone_id' => (int) 0,
        'entry_country_id' => (int) $country
    );
    if(ACCOUNT_GENDER == 'true')
    {
        $entry['entry_gender'] = $gender;
    }
    if(ACCOUNT_COMPANY == 'true')
    {
        $entry['entry_company'] = $company;
    }
    if(ACCOUNT_SUBURB == 'true')
    {
        $entry['entry_suburb'] = $suburb;
    }
}
else
// -Country-State Selector

if(isset($_GET['edit']) && is_numeric($_GET['edit']))
{

    $entry_query = tep_db_query("select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $customer_id . "' and address_book_id = '" . (int) $_GET['edit'] . "'");

    if(!tep_db_num_rows($entry_query))
    {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);
}
elseif(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
    if($_GET['delete'] == $customer_default_address_id)
    {
        $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
    else
    {
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int) $_GET['delete'] . "' and customers_id = '" . (int) $customer_id . "'");
        $check = tep_db_fetch_array($check_query);

        if($check['total'] < 1)
        {
            $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

            tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
        }
    }
}
else
{
    $entry = array();

    // +Country-State Selector
    if(!isset($country))
    {
        $country = DEFAULT_COUNTRY;
    }
    $entry['entry_country_id'] = $country;
    // -Country-State Selector
}

if(!isset($_GET['delete']) && !isset($_GET['edit']))
{
    if(tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES)
    {
        $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

if(isset($_GET['edit']) && is_numeric($_GET['edit']))
{
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
}
elseif(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
}
else
{
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
}

$content = CONTENT_ADDRESS_BOOK_PROCESS;
$body_class = 'address-book-process-page';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');