<?php
//j// BOF

/*n// NOTE
----------------------------------------------------------------------------
secured WebGine
net-based application engine
----------------------------------------------------------------------------
(C) direct Netware Group - All rights reserved
http://www.direct-netware.de/redirect.php?swg

The following license agreement remains valid unless any additions or
changes are being made by direct Netware Group in a written form.

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation; either version 2 of the License, or (at your
option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc.,
59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
----------------------------------------------------------------------------
http://www.direct-netware.de/redirect.php?licenses;gpl
----------------------------------------------------------------------------
$Id: swg_index.php,v 1.6 2008/12/30 19:03:18 s4u Exp $
#echo(sWGsearchVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* search/swg_index.php
*
* @internal   We are using phpDocumentor to automate the documentation process
*             for creating the Developer's Manual. All sections including
*             these special comments will be removed from the release source
*             code.
*             Use the following line to ensure 76 character sizes:
* ----------------------------------------------------------------------------
* @author     direct Netware Group
* @copyright  (C) direct Netware Group - All rights reserved
* @package    sWG
* @subpackage search
* @uses       direct_product_iversion
* @since      v0.1.00
* @license    http://www.direct-netware.de/redirect.php?licenses;gpl
*             GNU General Public License 2
*/

/* -------------------------------------------------------------------------
All comments will be removed in the "production" packages (they will be in
all development packets)
------------------------------------------------------------------------- */

//j// Basic configuration

/* -------------------------------------------------------------------------
Direct calls will be honored with an "exit ()"
------------------------------------------------------------------------- */

if (!defined ("direct_product_iversion")) { exit (); }

//j// Script specific commands

if (!isset ($direct_settings['search_https'])) { $direct_settings['search_https'] = false; }
if (!isset ($direct_settings['search_services_preselected'])) { $direct_settings['search_services_preselected'] = array (); }
if (!isset ($direct_settings['search_subs_supported'])) { $direct_settings['search_subs_supported'] = array (); }
if (!isset ($direct_settings['search_term_max'])) { $direct_settings['search_term_max'] = 256; }
if (!isset ($direct_settings['search_term_min'])) { $direct_settings['search_term_min'] = 4; }
if (!isset ($direct_settings['search_titledata'])) { $direct_settings['search_titledata'] = true; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
$direct_settings['additional_copyright'][] = array ("Module search #echo(sWGsearchVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if ($direct_settings['a'] == "index") { $direct_settings['a'] = "form"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// ($direct_settings['a'] == "form")||($direct_settings['a'] == "form-save")
case "form":
case "form-save":
{
	if ($direct_settings['a'] == "form-save") { $g_mode_save = true; }
	else { $g_mode_save = false; }

	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }

	$g_source = (isset ($direct_settings['dsd']['source']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['source'])) : "");
	$g_target = (isset ($direct_settings['dsd']['target']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['target'])) : "");

	if ($g_source) { $g_source_url = base64_decode ($g_source); }
	else { $g_source_url = "m=search"; }

	if ($g_target) { $g_target_url = base64_decode ($g_target); }
	else
	{
		$g_target = $g_source;
		$g_target_url = $g_source_url;
	}

	if ($g_mode_save)
	{
		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "m=search&a=form&dsd=source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_homelink'] = str_replace ("[oid]","",$g_source_url);
	}
	else
	{
		$direct_cachedata['page_this'] = "m=search&a=form&dsd=source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_source_url);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'] ;
	}

	if ($direct_classes['kernel']->service_init_default ())
	{
	if ($direct_settings['search_subs_supported'])
	{
	//j// BOA
	if ($g_mode_save) { direct_output_related_manager ("search_index_form_save","pre_module_service_action"); }
	else { direct_output_related_manager ("search_index_form","pre_module_service_action"); }
	
	if (!$g_mode_save) { $direct_classes['kernel']->service_https ($direct_settings['search_https'],$direct_cachedata['page_this']); }
	$direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/classes/swg_formbuilder.php");
	if ($g_mode_save) { $direct_classes['basic_functions']->require_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }
	direct_local_integration ("search");

	direct_class_init ("formbuilder");
	direct_class_init ("output");
	$direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0");

	$g_continue_check = false;
	$g_usertype = $direct_classes['kernel']->v_usertype_get_int ($direct_settings['user']['type']);

	if ($g_usertype > 2) { $g_continue_check = true; }
	elseif ($g_usertype)
	{
		if (($direct_settings['search_intitle'])||($direct_settings['search_intext'])) { $g_continue_check = true; }
	}
	else
	{
		if (($direct_settings['search_intitle_guests'])||($direct_settings['search_intext_guests'])) { $g_continue_check = true; }
	}

	if ($g_continue_check)
	{
		if ($g_mode_save)
		{
/* -------------------------------------------------------------------------
We should have input in save mode
------------------------------------------------------------------------- */

			$direct_cachedata['i_sterm'] = (isset ($GLOBALS['i_sterm']) ? ($direct_classes['basic_functions']->inputfilter_basic ($GLOBALS['i_sterm'])) : "");

			$direct_cachedata['i_swords'] = (isset ($GLOBALS['i_swords']) ? (str_replace ("'","",$GLOBALS['i_swords'])) : "");
			$direct_cachedata['i_swords'] = str_replace ("<value value='$direct_cachedata[i_swords]' />","<value value='$direct_cachedata[i_swords]' /><selected value='1' />","<evars><any><value value='any' /><text><![CDATA[".(direct_local_get ("search_word_behavior_any"))."]]></text></any><all><value value='all' /><text><![CDATA[".(direct_local_get ("search_word_behavior_all"))."]]></text></all></evars>");

			if (isset ($GLOBALS['i_sservices'])) { $g_services_selected = $GLOBALS['i_sservices']; }
			else { $g_services_selected = $direct_settings['search_services_preselected']; }
		}
		else
		{
			$direct_cachedata['i_sterm'] = "";
			$direct_cachedata['i_swords'] = "<evars><any><value value='any' /><selected value='1' /><text><![CDATA[".(direct_local_get ("search_word_behavior_any"))."]]></text></any><all><value value='all' /><text><![CDATA[".(direct_local_get ("search_word_behavior_all"))."]]></text></all></evars>";
			$g_services_selected = $direct_settings['search_services_preselected'];
		}

		if ($g_usertype > 2) { $g_continue_check = true; }
		elseif (($g_usertype)&&($direct_settings['search_intitle'])&&($direct_settings['search_intext'])) { $g_continue_check = true; }
		elseif (($direct_settings['search_intitle_guests'])&&($direct_settings['search_intext_guests'])) { $g_continue_check = true; }
		else { $g_continue_check = false; }

		if ($g_continue_check)
		{
			$g_search_base = "<evars><title><value value='title' /><text><![CDATA[".(direct_local_get ("search_target_title_only"))."]]></text></title><data><value value='data' /><text><![CDATA[".(direct_local_get ("search_target_data"))."]]></text></data>";
			if ($direct_settings['search_titledata']) { $g_search_base .= "<both><value value='titledata' /><text><![CDATA[".(direct_local_get ("search_target_both"))."]]></text></both>"; }
			$g_search_base .= "</evars>";

			$direct_cachedata['i_sbase'] = (isset ($GLOBALS['i_sbase']) ? (str_replace ("'","",$GLOBALS['i_sbase'])) : "title");
			$direct_cachedata['i_sbase'] = str_replace ("<value value='$direct_cachedata[i_sbase]' />","<value value='$direct_cachedata[i_sbase]' /><selected value='1' />",$g_search_base);
		}

		if (is_string ($direct_settings['search_subs_supported'])) { $direct_settings['search_subs_supported'] = array ($direct_settings['search_subs_supported']); }
		if (is_string ($g_services_selected)) { $g_services_selected = array ($g_services_selected); }
		$direct_cachedata['i_sservices'] = "<evars>";

		foreach ($direct_settings['search_subs_supported'] as $g_service)
		{
			$g_sid = md5 ($g_service);
			$g_service_name = direct_string_id_translation ("datalinker",$g_sid);

			$direct_cachedata['i_sservices'] .= "<s$g_sid><value value='$g_sid' />";
			if (in_array ($g_sid,$g_services_selected)) { $direct_cachedata['i_sservices'] .= "<selected value='1' />"; }

			if ($g_service_name) { $direct_cachedata['i_sservices'] .= "<text><![CDATA[$g_service_name]]></text>"; }
			else { $direct_cachedata['i_sservices'] .= "<text><![CDATA[".(direct_local_get ("core_unknown"))." ($g_service)]]></text>"; }

			$direct_cachedata['i_sservices'] .= "</s$g_sid>";
		}

		$direct_cachedata['i_sservices'] .= "</evars>";

/* -------------------------------------------------------------------------
Build the form
------------------------------------------------------------------------- */

		$direct_classes['formbuilder']->entry_add_text ("sterm",(direct_local_get ("search_term")),true,"s",$direct_settings['search_term_min'],$direct_settings['search_term_max'],(direct_local_get ("search_helper_term")),"",true);
		if ($g_continue_check) { $direct_classes['formbuilder']->entry_add_select ("sbase",(direct_local_get ("search_target")),false,"s",(direct_local_get ("search_helper_target")),"",true); }
		$direct_classes['formbuilder']->entry_add_select ("swords",(direct_local_get ("search_word_behavior")),false,"s");
		$direct_classes['formbuilder']->entry_add_multiselect ("sservices",(direct_local_get ("search_services")),true,"l");

		$direct_cachedata['output_formelements'] = $direct_classes['formbuilder']->form_get ($g_mode_save);

		if (($g_mode_save)&&($direct_classes['formbuilder']->check_result))
		{
/* -------------------------------------------------------------------------
Save data edited
------------------------------------------------------------------------- */

			if (!$g_continue_check)
			{
				if ((($g_usertype)&&($direct_settings['search_intext']))||($direct_settings['search_intext_guests']))
				{
					if ($direct_settings['search_titledata']) { $direct_cachedata['i_sbase'] = "titledata"; }
					else { $direct_cachedata['i_sbase'] = "data"; }
				}
				else { $direct_cachedata['i_sbase'] = "title"; }
			}

			if ($direct_settings['user']['type'] == "gt")
			{
				$g_uuid_string = $direct_classes['kernel']->v_uuid_get ("s");

				if (!$g_uuid_string)
				{
					$g_uuid_string = "<evars><userid /></evars>";
					$direct_classes['kernel']->v_uuid_write ($g_uuid_string);
					$direct_classes['kernel']->v_uuid_cookie_save ();
				}
			}

			$g_tid = uniqid ("");

$g_task_array = array (
"core_back_return" => "m=search&a=form",
"core_sid" => "06a943c59f33a34bb5924aaf72cd2995",
// md5 ("search")
"search_term" => $direct_cachedata['i_sterm'],
"search_words" => $direct_cachedata['i_swords'],
"search_services" => $direct_cachedata['i_sservices'],
"search_base" => $direct_cachedata['i_sbase'],
"search_result_handler" => "m=dataport&s=swgap;search;selector&dsd=dtheme+1++[oid]",
"search_marker_return" => "m=search&a=form",
"search_selection_quantity" => 0,
"uuid" => $direct_settings['uuid']
);
 
			direct_tmp_storage_write ($g_task_array,$g_tid,"06a943c59f33a34bb5924aaf72cd2995","task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
			$direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;search;selector&a=run&dsd=dtheme+1++tid+".$g_tid,false));
		}
		else
		{
/* -------------------------------------------------------------------------
View form
------------------------------------------------------------------------- */

			$direct_cachedata['output_formbutton'] = direct_local_get ("search_new");
			$direct_cachedata['output_formtarget'] = "m=search&a=form-save&dsd=source+".(urlencode ($g_source))."++target+".(urlencode ($g_target));
			$direct_cachedata['output_formtitle'] = direct_local_get ("core_search");

			direct_output_related_manager ("search_index_form","post_module_service_action");
			$direct_classes['output']->oset ("default","form");
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_formtitle']);
		}
	}
	else { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	//j// EOA
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_service_inactive","sWG/#echo(__FILEPATH__)# _a={$direct_settings['a']}_ (#echo(__LINE__)#)"); }
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>