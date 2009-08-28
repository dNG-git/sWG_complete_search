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
#echo(sWGsearchVersion)#
sWG/#echo(__FILEPATH__)#
----------------------------------------------------------------------------
NOTE_END //n*/
/**
* dataport/swgap/search/swg_selector.php
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

if (!isset ($direct_settings['datalinker_iview_objects_per_page'])) { $direct_settings['datalinker_iview_objects_per_page'] = 20; }
if (!isset ($direct_settings['search_cache_size'])) { $direct_settings['search_cache_size'] = 0xffffffff; }
if (!isset ($direct_settings['search_https'])) { $direct_settings['search_https'] = false; }
if (!isset ($direct_settings['search_limit'])) { $direct_settings['search_limit'] = 250; }
if (!isset ($direct_settings['serviceicon_default_back'])) { $direct_settings['serviceicon_default_back'] = "mini_default_back.png"; }
if (!isset ($direct_settings['serviceicon_search_new'])) { $direct_settings['serviceicon_search_new'] = "mini_default_option.png"; }
$direct_settings['additional_copyright'][] = array ("Module search #echo(sWGsearchVersion)# - (C) ","http://www.direct-netware.de/redirect.php?swg","direct Netware Group"," - All rights reserved");

if ($direct_settings['a'] == "index") { $direct_settings['a'] = "list"; }
//j// BOS
switch ($direct_settings['a'])
{
//j// $direct_settings['a'] == "list"
case "list":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }

	$direct_cachedata['output_tid'] = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");
	$direct_cachedata['output_page'] = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$direct_cachedata['output_dtheme_mode'] = 2;
			$g_dtheme_embedded = true;
		}
		else
		{
			$direct_cachedata['output_dtheme_mode'] = 1;
			$g_dtheme_embedded = false;
		}

		$direct_cachedata['page_this'] = "m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+{$direct_cachedata['output_dtheme_mode']}++tid+{$direct_cachedata['output_tid']}++page+".$direct_cachedata['output_page'];
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$direct_cachedata['output_dtheme_mode'] = 0;
		$g_dtheme = false;
		$g_dtheme_embedded = false;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datalinker.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datalinker/swg_iviewer.php"); }
	
	if ($g_continue_check)
	{
	//j// BOA
	if ($direct_cachedata['output_tid'] == "") { $direct_cachedata['output_tid'] = $direct_settings['uuid']; }
	$g_task_array = direct_tmp_storage_get ("evars",$direct_cachedata['output_tid'],"","task_cache");

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['search_result_handler'],$g_task_array['uuid']))&&(!$g_task_array['search_selection_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("search_selector_list","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("search_selector_list","pre_module_service_action"); }
		else { direct_output_related_manager ("search_selector_list","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];

		if (!is_array ($g_task_array['search_objects_marked'])) { $g_task_array['search_objects_marked'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme) { $direct_classes['kernel']->service_https ($direct_settings['search_https'],$direct_cachedata['page_this']); }
	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("datalinker");
	direct_local_integration ("search");

	direct_class_init ("output");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		$direct_cachedata['output_source'] = base64_encode ("m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+{$direct_cachedata['output_dtheme_mode']}++tid+{$direct_cachedata['output_tid']}++page+".$direct_cachedata['output_page']);
		$g_results = 0;

		if ((isset ($g_task_array['search_results_confirmed']))&&(is_array ($g_task_array['search_results_confirmed'])))
		{
			$direct_cachedata['output_results_confirmed'] = count ($g_task_array['search_results_confirmed']);
			$g_results += $direct_cachedata['output_results_confirmed'];
		}
		else
		{
			$direct_cachedata['output_results_confirmed'] = 0;
			$g_task_array['search_results_confirmed'] = array ();
		}

		if ((isset ($g_task_array['search_results_possible']))&&(is_array ($g_task_array['search_results_possible'])))
		{
			$direct_cachedata['output_results_possible'] = count ($g_task_array['search_results_possible']);
			$g_results += $direct_cachedata['output_results_possible'];
		}
		else
		{
			$direct_cachedata['output_results_possible'] = 0;
			$g_task_array['search_results_possible'] = array ();
		}

		$direct_cachedata['output_pages'] = ceil ($g_results / $direct_settings['datalinker_iview_objects_per_page']);
		if ($direct_cachedata['output_pages'] < 1) { $direct_cachedata['output_pages'] = 1; }
		if ((!is_numeric ($direct_cachedata['output_page']))||($direct_cachedata['output_page'] < 1)) { $direct_cachedata['output_page'] = 1; }

		$g_results_confirmed = 0;
		$g_results_limit = ($direct_cachedata['output_page'] * $direct_settings['datalinker_iview_objects_per_page']);
		$g_results_offset = (($direct_cachedata['output_page'] - 1) * $direct_settings['datalinker_iview_objects_per_page']);
		$g_results_position = 0;
		$g_results_selected = array ();
		$g_results_selected_position = array ();

		for ($g_i = $g_results_offset;$g_i < $g_results_limit;$g_i++)
		{
			if (isset ($g_task_array['search_result_positions'][$g_i]))
			{
				if (isset ($g_task_array['search_results_confirmed'][$g_task_array['search_result_positions'][$g_i]]))
				{
					$g_results_selected[$g_results_position] = $g_task_array['search_results_confirmed'][$g_task_array['search_result_positions'][$g_i]];
					$g_results_selected_position[$g_results_selected[$g_results_position]] = $g_task_array['search_result_positions'][$g_i];
					$g_results_position++;
				}
				elseif (isset ($g_task_array['search_results_possible'][$g_task_array['search_result_positions'][$g_i]]))
				{
					$g_results_selected[$g_results_position] = $g_task_array['search_results_possible'][$g_task_array['search_result_positions'][$g_i]];
					$g_results_selected_position[$g_results_selected[$g_results_position]] = $g_task_array['search_result_positions'][$g_i];
					$g_results_position++;
				}
				elseif ($g_results_limit < $g_results) { $g_results_limit++; }
			}
		}

		$direct_cachedata['output_objects'] = array ();

		if (!empty ($g_results_selected))
		{
			$direct_classes['db']->init_select ($direct_settings['datalinker_table']);
			$direct_classes['db']->define_attributes (array ($direct_settings['datalinker_table'].".*",$direct_settings['datalinkerd_table'].".*"));
			$direct_classes['db']->define_join ("left-outer-join",$direct_settings['datalinkerd_table'],"<sqlconditions><element1 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinkerd_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' /></sqlconditions>");

			$g_select_criteria = "<sqlconditions>";
			foreach ($g_results_selected as $g_id) { $g_select_criteria .= $direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_id",$g_id,"string","==","or"); }
			$g_select_criteria .= "</sqlconditions>";

			$direct_classes['db']->define_row_conditions ($g_select_criteria);

			$g_datalinker_object = new direct_datalinker ();
			$g_results_position = 0;
			$g_results_selected = $direct_classes['db']->query_exec ("ma");
			$g_results_positions = $g_task_array['search_result_positions'][$g_results_offset];
			$g_results_positions_check = true;

			while (($g_results_confirmed < $direct_settings['datalinker_iview_objects_per_page'])&&(($g_results_offset + $g_results_position) < $g_results))
			{
				$g_result_accepted = false;

				if ($g_results_position < $direct_settings['datalinker_iview_objects_per_page'])
				{
					if ((isset ($g_results_selected))&&($g_datalinker_object->set ($g_results_selected[$g_results_position])))
					{
						$g_service_array = $g_datalinker_object->get_service ($g_results_selected[$g_results_position]['ddbdatalinker_sid']);

						if (isset ($g_service_array['services'][$g_results_selected[$g_results_position]['ddbdatalinker_type']]))
						{
							$g_service_array = $g_service_array['services'][$g_results_selected[$g_results_position]['ddbdatalinker_type']];
							$g_result_selected = direct_datalinker_iviewer ($g_service_array,$g_datalinker_object);

							if ($g_result_selected)
							{
								$g_result_selected['marked'] = in_array ($g_results_selected[$g_results_position]['ddbdatalinker_id'],$g_task_array['search_objects_marked']);

								if ((isset ($g_task_array['search_selection_quantity']))&&($g_task_array['search_selection_quantity']))
								{
									$g_result_selected['pageurl_marker'] = direct_linker ("url0","m=dataport&s=swgap;search;selector&a=mark_switch&dsd=dtheme+{$direct_cachedata['output_dtheme_mode']}++tid+{$direct_cachedata['output_tid']}++deid+{$g_results_selected[$g_results_position]['ddbdatalinker_id']}++page+".$direct_cachedata['output_page']);

									if ($g_result_selected['marked']) { $g_result_selected['marker_title'] = ((isset ($g_task_array['search_marker_title_1'])) ? $g_task_array['search_marker_title_1'] : direct_local_get ("datalinker_object_unmark")); }
									else { $g_result_selected['marker_title'] = ((isset ($g_task_array['search_marker_title_0'])) ? $g_task_array['search_marker_title_0'] : direct_local_get ("datalinker_object_mark")); }
								}

								$g_result_accepted = $g_result_selected['object_available'];
							}

							if ($g_result_accepted) { $direct_cachedata['output_objects'][] = $g_result_selected; }
						}
					}

					$g_results_positions = $g_results_selected_position[$g_results_selected[$g_results_position]['ddbdatalinker_id']];

					if ($g_result_accepted)
					{
						if (isset ($g_task_array['search_results_possible'][$g_results_positions]))
						{
							$g_task_array['search_result_positions'][$g_results_positions] = ($g_results + $g_results_offset + $g_results_position);
							$g_task_array['search_results_confirmed'][($g_results + $g_results_offset + $g_results_position)] = $g_task_array['search_results_possible'][$g_results_positions];

							unset ($g_task_array['search_results_possible'][$g_results_positions]);

							$direct_cachedata['output_results_confirmed']++;
							$direct_cachedata['output_results_possible']--;
						}

						$g_results_confirmed++;
					}
					elseif (isset ($g_task_array['search_results_possible'][$g_results_positions]))
					{
						$direct_cachedata['output_results_possible']--;
						unset ($g_task_array['search_results_possible'][$g_results_positions]);

						unset ($g_task_array['search_result_positions'][$g_results_positions]);
						$g_results_positions_check = false;
					}
					elseif (isset ($g_task_array['search_results_confirmed'][$g_results_positions]))
					{
						$direct_cachedata['output_results_confirmed']--;
						unset ($g_task_array['search_results_confirmed'][$g_results_positions]);

						unset ($g_task_array['search_result_positions'][$g_results_positions]);
						$g_results_positions_check = false;
					}
				}
				else
				{
					$g_results_positions = ((isset ($g_task_array['search_result_positions'][($g_results_offset + $g_results_position)])) ? $g_task_array['search_result_positions'][($g_results_offset + $g_results_position)] : 0);

					if ($g_results_positions)
					{
						$g_datalinker_object = new direct_datalinker ();

						if (isset ($g_task_array['search_results_confirmed'][$g_results_positions])) { $g_results_selected[$g_results_confirmed] = $g_datalinker_object->get ($g_task_array['search_results_confirmed'][$g_results_positions]); }
						elseif (isset ($g_task_array['search_results_possible'][$g_results_positions])) { $g_results_selected[$g_results_confirmed] = $g_datalinker_object->get ($g_task_array['search_results_possible'][$g_results_positions]); }
						else { $g_results_selected[$g_results_confirmed] = array (); }
					}
					else { $g_results_selected[$g_results_confirmed] = array (); }

					if (!empty ($g_results_selected[$g_results_confirmed]))
					{
						$g_service_array = $g_datalinker_object->get_service ($g_results_selected[$g_results_confirmed]['ddbdatalinker_sid']);

						if (isset ($g_service_array['services'][$g_results_selected[$g_results_confirmed]['ddbdatalinker_type']]))
						{
							$g_service_array = $g_service_array['services'][$g_results_selected[$g_results_confirmed]['ddbdatalinker_type']];
							$g_result_selected = direct_datalinker_iviewer ($g_service_array,$g_datalinker_object);

							if ($g_result_selected) { $g_result_accepted = $g_result_selected['object_available']; }
							if ($g_result_accepted) { $direct_cachedata['output_objects'][] = $g_result_selected; }
						}
					}

					if ($g_result_accepted)
					{
						if (isset ($g_task_array['search_results_possible'][$g_results_positions]))
						{
							$g_task_array['search_results_confirmed'][$g_results_positions] = $g_task_array['search_results_possible'][$g_results_positions];
							unset ($g_task_array['search_results_possible'][$g_results_positions]);

							$direct_cachedata['output_results_confirmed']++;
							$direct_cachedata['output_results_possible']--;
						}

						$g_results_confirmed++;
					}
					elseif (isset ($g_task_array['search_results_possible'][$g_results_positions]))
					{
						$direct_cachedata['output_results_possible']--;
						unset ($g_task_array['search_results_possible'][$g_results_positions]);

						unset ($g_task_array['search_result_positions'][($g_results_offset + $g_results_position)]);
						$g_results_positions_check = false;
					}
					elseif (isset ($g_task_array['search_results_confirmed'][$g_results_positions]))
					{
						$direct_cachedata['output_results_confirmed']--;
						unset ($g_task_array['search_results_confirmed'][$g_results_positions]);

						unset ($g_task_array['search_result_positions'][($g_results_offset + $g_results_position)]);
						$g_results_positions_check = false;
					}
				}

				$g_results_position++;
			}

			if (!$g_results_positions_check)
			{
				$g_task_array['search_result_positions'] = array_values ($g_task_array['search_result_positions']);
				direct_tmp_storage_write ($g_task_array,$direct_cachedata['output_tid'],$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

				$g_results = ($direct_cachedata['output_results_confirmed'] + $direct_cachedata['output_results_possible']);
				$direct_cachedata['output_pages'] = ceil ($g_results / $direct_settings['datalinker_iview_objects_per_page']);
				if ($direct_cachedata['output_pages'] < 1) { $direct_cachedata['output_pages'] = 1; }
			}
		}

		$direct_cachedata['output_title'] = (((isset ($g_task_array['search_selection_title']))&&($g_task_array['search_selection_title'])) ? $g_task_array['search_selection_title'] : direct_local_get ("search_results"));

		if ($g_dtheme)
		{
			if ($g_dtheme_embedded)
			{
				direct_output_related_manager ("search_selector_list","post_module_service_action_embedded");
				$direct_cachedata['output_page_url'] = "m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+2++tid+{$direct_cachedata['output_tid']}++";
				$direct_classes['output']->oset ("datalinker_embedded","list_iviews");
			}
			else
			{
				direct_output_related_manager ("search_selector_list","post_module_service_action");
				$direct_cachedata['output_page_url'] = "m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+1++tid+{$direct_cachedata['output_tid']}++";
				$direct_classes['output']->oset ("datalinker","list_iviews");
			}

			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_title']);
		}
		else
		{
			$direct_cachedata['output_page_url'] = "javascript:djs_dataport_{$direct_cachedata['output_tid']}_call_url0('m=dataport&amp;s=swgap;search;selector&amp;a=list&amp;dsd=dtheme+0++tid+{$direct_cachedata['output_tid']}++page+[page]')";

			$direct_classes['output']->header (false);
			header ("Content-type: text/xml; charset=".$direct_local['lang_charset']);

echo ("<?xml version='1.0' encoding='$direct_local[lang_charset]' ?>
".(direct_output_smiley_decode ($direct_classes['output']->oset_content ("datalinker_embedded","ajax_list_iviews"))));
		}
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=list_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// $direct_settings['a'] == "mark_switch"
case "mark_switch":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }

	$g_eid = (isset ($direct_settings['dsd']['deid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['deid'])) : "");
	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");
	$g_page = (isset ($direct_settings['dsd']['page']) ? ($direct_classes['basic_functions']->inputfilter_number ($direct_settings['dsd']['page'])) : 1);

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$g_dtheme_embedded = true;
			$g_dtheme_mode = 2;
		}
		else
		{
			$g_dtheme_embedded = false;
			$g_dtheme_mode = 1;
		}

		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$g_dtheme = false;
		$g_dtheme_embedded = false;
		$g_dtheme_mode = 0;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_datalinker.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/classes/dhandler/swg_datalinker_uhome.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/datalinker/swg_iviewer.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	$g_task_array = direct_tmp_storage_get ("evars",$g_tid,"","task_cache");

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['search_result_handler'],$g_task_array['uuid']))&&(!$g_task_array['search_selection_done'])&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("search_selector_mark_switch","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("search_selector_mark_switch","pre_module_service_action"); }
		else { direct_output_related_manager ("search_selector_mark_switch","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = "m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+{$g_dtheme_mode}++tid+{$g_tid}++page+".$g_page;
		$direct_cachedata['page_homelink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);

		if (!is_array ($g_task_array['search_objects_marked'])) { $g_task_array['search_objects_marked'] = array (); }
	}
	else { $g_continue_check = false; }

	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("search");

	direct_class_init ("output");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		$g_continue_check = false;
		$g_datalinker_object = ((strpos ($g_eid,"u-") === 0) ? new direct_datalinker_uhome () : new direct_datalinker ());

		$g_datalinker_array = ($g_datalinker_object ? $g_datalinker_object->get ($g_eid) : NULL);

		if ($g_datalinker_array)
		{
			$g_service_array = $g_datalinker_object->get_service ($g_datalinker_array['ddbdatalinker_sid']);

			if (isset ($g_service_array['services'][$g_datalinker_array['ddbdatalinker_type']]))
			{
				$g_service_array = $g_service_array['services'][$g_datalinker_array['ddbdatalinker_type']];
				$g_datalinker_array = direct_datalinker_iviewer ($g_service_array,$g_datalinker_object);
				if ($g_datalinker_array) { $g_continue_check = $g_datalinker_array['object_available']; }
			}
		}

		if ($g_continue_check)
		{
			if (in_array ($g_eid,$g_task_array['search_objects_marked'])) { unset ($g_task_array['search_objects_marked'][$g_eid]); }
			else
			{
				if ($g_task_array['search_selection_quantity'] > count ($g_task_array['search_objects_marked'])) { $g_task_array['search_objects_marked'][$g_eid] = $g_eid; }
				else
				{
					array_shift ($g_task_array['search_objects_marked']);
					$g_task_array['search_objects_marked'][$g_eid] = $g_eid;
				}
			}

			if ($g_task_array['search_marker_return']) { $g_task_array['search_selection_done'] = 1; }
			direct_tmp_storage_write ($g_task_array,$g_tid,$g_task_array['core_sid'],"task_cache","evars",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));

			if ((isset ($g_task_array['search_marker_return']))&&($g_task_array['search_marker_return']))
			{
				$g_back_link = str_replace ("[oid]","deid_d+$g_eid++",$g_task_array['search_marker_return']);
				$direct_classes['output']->redirect (direct_linker ("url1",$g_back_link,false));
			}
			else { $direct_classes['output']->redirect (direct_linker ("url1","m=dataport&s=swgap;search;selector&a=list&dsd=dtheme+{$g_dtheme_mode}++tid+{$g_tid}++page+{$g_page}#swgdhandlerdatalinker".$g_eid,false)); }
		}
		elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("login","core_access_denied","sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }
	}
	elseif ($g_dtheme) { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=mark_switch_ (#echo(__LINE__)#)"); }
	//j// BOA
	}

	$direct_cachedata['core_service_activated'] = 1;
	break 1;
}
//j// $direct_settings['a'] == "run"
case "run":
{
	if (USE_debug_reporting) { direct_debug (1,"sWG/#echo(__FILEPATH__)# _a=run_ (#echo(__LINE__)#)"); }

	$g_tid = (isset ($direct_settings['dsd']['tid']) ? ($direct_classes['basic_functions']->inputfilter_basic ($direct_settings['dsd']['tid'])) : "");

	if ((isset ($direct_settings['dsd']['dtheme']))&&($direct_settings['dsd']['dtheme']))
	{
		$g_dtheme = true;

		if ($direct_settings['dsd']['dtheme'] == 2)
		{
			$direct_cachedata['output_dtheme_mode'] = 2;
			$g_dtheme_embedded = true;
		}
		else
		{
			$direct_cachedata['output_dtheme_mode'] = 1;
			$g_dtheme_embedded = false;
		}

		$direct_cachedata['page_this'] = "";
		$direct_cachedata['page_backlink'] = "";
		$direct_cachedata['page_homelink'] = "";

		$g_continue_check = $direct_classes['kernel']->service_init_default ();
	}
	else
	{
		$direct_cachedata['output_dtheme_mode'] = 0;
		$g_dtheme = false;
		$g_dtheme_embedded = false;

		$g_continue_check = $direct_classes['kernel']->service_init_rboolean ();
	}

	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->settings_get ($direct_settings['path_data']."/settings/swg_search.php"); }
	if ($g_continue_check) { $g_continue_check = $direct_classes['basic_functions']->include_file ($direct_settings['path_system']."/functions/swg_tmp_storager.php"); }

	if ($g_continue_check)
	{
	//j// BOA
	if ($g_tid == "") { $g_tid = $direct_settings['uuid']; }

	$g_search_storage = direct_tmp_storage_get ("s",$g_tid,"","task_cache");
	// md5 ("search")

	$g_task_array = ($g_search_storage ? direct_evars_get ($g_search_storage) : NULL);

	if (($g_task_array)&&(isset ($g_task_array['core_sid'],$g_task_array['search_result_handler'],$g_task_array['uuid']))&&($g_task_array['uuid'] == $direct_settings['uuid']))
	{
		if ($g_dtheme_embedded) { direct_output_related_manager ("search_selector_run","pre_module_service_action_embedded"); }
		elseif ($g_dtheme) { direct_output_related_manager ("search_selector_run","pre_module_service_action"); }
		else { direct_output_related_manager ("search_selector_run","pre_module_service_action_ajax"); }

		$direct_cachedata['page_backlink'] = str_replace ("[oid]","",$g_task_array['core_back_return']);
		$direct_cachedata['page_homelink'] = $direct_cachedata['page_backlink'];
	}
	else { $g_continue_check = false; }

	if ($g_dtheme_embedded) { direct_output_theme_subtype ("embedded"); }
	direct_local_integration ("search");

	direct_class_init ("output");
	if (($g_dtheme)&&($direct_cachedata['page_backlink'])) { $direct_classes['output']->options_insert (2,"servicemenu",$direct_cachedata['page_backlink'],(direct_local_get ("core_back")),$direct_settings['serviceicon_default_back'],"url0"); }

	if ($g_continue_check)
	{
		$g_changed_check = false;

		if (!isset ($g_task_array['search_selection_done']))
		{
			$g_changed_check = true;
			$g_task_array['search_selection_done'] = 0;
		}

		if (!isset ($g_task_array['search_objects_marked']))
		{
			$g_changed_check = true;
			$g_task_array['search_objects_marked'] = "";
		}

		if (isset ($g_task_array['search_results_confirmed']))
		{
			$g_changed_check = true;
			unset ($g_task_array['search_results_confirmed']);
		}

		if (isset ($g_task_array['search_results_possible']))
		{
			$g_changed_check = true;
			unset ($g_task_array['search_results_possible']);
		}

		if (isset ($g_task_array['search_result_positions']))
		{
			$g_changed_check = true;
			unset ($g_task_array['search_result_positions']);
		}

		if ($g_changed_check) { $g_search_storage = direct_evars_write ($g_task_array); }

		if ($g_task_array['search_base'] == "title")
		{
			$direct_classes['db']->init_select ($direct_settings['datalinkerd_table']);
			$direct_classes['db']->define_join ("left-outer-join",$direct_settings['datalinker_table'],"<sqlconditions><element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' value='{$direct_settings['datalinkerd_table']}.ddbdatalinkerd_id' type='attribute' /></sqlconditions>");

			$g_select_attributes = array ($direct_settings['datalinker_table'].".ddbdatalinker_id");
		}
		else
		{
			$direct_classes['db']->init_select ($direct_settings['data_table']);

			if ($g_task_array['search_base'] != "data") { $direct_classes['db']->define_join ("left-outer-join",$direct_settings['datalinkerd_table'],"<sqlconditions><element1 attribute='{$direct_settings['datalinkerd_table']}.ddbdatalinkerd_id' value='{$direct_settings['data_table']}.ddbdata_id' type='attribute' /></sqlconditions>"); }
			$direct_classes['db']->define_join ("left-outer-join",$direct_settings['datalinker_table'],"<sqlconditions><element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' value='{$direct_settings['data_table']}.ddbdata_id' type='attribute' /></sqlconditions>");

			$g_select_attributes = array ($direct_settings['datalinker_table'].".ddbdatalinker_id",$direct_settings['data_table'].".ddbdata_mode_all");
		}

		$direct_classes['db']->define_attributes ($g_select_attributes);

		if ($g_task_array['search_words'] == "all")
		{
			$g_task_array['search_term'] = str_replace (" AND "," ",$g_task_array['search_term']);
			$g_task_array['search_term'] = str_replace (" OR "," ",$g_task_array['search_term']);
			$g_task_array['search_term'] = str_replace (" "," AND ",$g_task_array['search_term']);
		}

		$g_select_criteria = "<sqlconditions searchtype='advanced'>";

		if (($g_task_array['search_base'] == "title")||($g_task_array['search_base'] == "titledata")) { $g_select_criteria .= "<attribute value='{$direct_settings['datalinkerd_table']}.ddbdatalinker_title' />"; }
		if (($g_task_array['search_base'] == "data")||($g_task_array['search_base'] == "titledata")) { $g_select_criteria .= "<attribute value='{$direct_settings['data_table']}.ddbdata_data' />"; }

		$g_select_criteria .= ($direct_classes['db']->define_search_conditions_term ($g_task_array['search_term']))."</sqlconditions>";
		$direct_classes['db']->define_search_conditions ($g_select_criteria);

		$g_select_criteria = "<sqlconditions><sub1 type='sublevel'>";

		$g_search_sid = (($g_task_array['search_base'] == "title") ? $direct_settings['datalinker_table'].".ddbdatalinker_sid" : $direct_settings['data_table'].".ddbdata_sid");
		$g_service_types_defined = isset ($g_task_array['search_service_types']);

		foreach ($g_task_array['search_services'] as $g_sid)
		{
			if (($g_service_types_defined)&&(isset ($g_task_array['search_service_types'][$g_sid]))&&(is_array ($g_task_array['search_service_types'][$g_sid])))
			{
				$g_select_criteria .= "<sub{$g_sid}1 type='sublevel' condition='or'>".($direct_classes['db']->define_row_conditions_encode ($g_search_sid,$g_sid,"string"))."<sub{$g_sid}2 type='sublevel'>";
				foreach ($g_task_array['search_service_types'][$g_sid] as $g_sid_type) { $g_select_criteria .= $direct_classes['db']->define_row_conditions_encode ($direct_settings['datalinker_table'].".ddbdatalinker_type",$g_sid_type,"number","==","or"); }
				$g_select_criteria .= "</sub{$g_sid}2></sub{$g_sid}1>";
			}
			else { $g_select_criteria .= $direct_classes['db']->define_row_conditions_encode ($g_search_sid,$g_sid,"string","==","or"); }
		}

		$g_select_criteria .= "</sub1>";
		if ((isset ($g_task_array['search_filter_links']))&&($g_task_array['search_filter_links'])) { $g_select_criteria .= "<element1 attribute='{$direct_settings['datalinker_table']}.ddbdatalinker_id' value='{$direct_settings['datalinker_table']}.ddbdatalinker_id_object' type='attribute' />"; }
		$g_select_criteria .= "</sqlconditions>";

		$direct_classes['db']->define_row_conditions ($g_select_criteria);
		if ($direct_settings['search_limit']) { $direct_classes['db']->define_limit ($direct_settings['search_limit']); }

		$g_continue_check = (($g_task_array['search_base'] == "title") ? false : true);
		$g_search_result_positions = "";
		$g_search_results = $direct_classes['db']->query_exec ("ma");
		$g_search_results_confirmed = "";
		$g_search_results_possible = "";
		$g_search_results_size = (strlen ($g_search_storage) + 100);

		foreach ($g_search_results as $g_search_result_number => $g_search_result_array)
		{
			if ($g_search_results_size < $direct_settings['search_cache_size'])
			{
				$g_search_result = $direct_classes['xml_bridge']->array2xml_item_encoder ((array ("tag" => $g_search_result_number,"value" => $g_search_result_array['ddbdatalinker_id'])),true,false);
				$g_search_result_position = $direct_classes['xml_bridge']->array2xml_item_encoder ((array ("tag" => $g_search_result_number,"value" => $g_search_result_number)),true,false);
				$g_search_results_size += (strlen ($g_search_result) + (strlen ($g_search_result_position)));

				if ($g_continue_check)
				{
					if (($g_search_result['ddbdata_mode_all'])&&($g_search_result_array['ddbdata_mode_all'] != "-")) { $g_search_results_confirmed .= $g_search_result; }
					else { $g_search_results_possible .= $g_search_result; }
				}
				else { $g_search_results_possible .= $g_search_result; }

				$g_search_result_positions .= $g_search_result_position;
			}
		}

		unset ($g_search_results);

		$g_search_storage = (substr ($g_search_storage,0,-8)."<search_results_confirmed>$g_search_results_confirmed</search_results_confirmed><search_results_possible>$g_search_results_possible</search_results_possible><search_result_positions>$g_search_result_positions</search_result_positions></evars>");
		unset ($g_search_result_positions);
		unset ($g_search_results_confirmed);
		unset ($g_search_results_possible);

		direct_tmp_storage_write ($g_search_storage,$g_tid,$g_task_array['core_sid'],"task_cache","s",$direct_cachedata['core_time'],($direct_cachedata['core_time'] + 3600));
		unset ($g_search_results);

		$g_target_link = str_replace ("[oid]","tid+{$g_tid}++",$g_task_array['search_result_handler']);

		if ($g_dtheme)
		{
			$direct_cachedata['output_job'] = direct_local_get ("search_new");
			$direct_cachedata['output_job_desc'] = direct_local_get ("search_done_new");
			$direct_cachedata['output_jsjump'] = 2000;
			$direct_cachedata['output_pagetarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link)));
			$direct_cachedata['output_scripttarget'] = str_replace ('"',"",(direct_linker ("url0",$g_target_link,false)));

			if ($g_dtheme_embedded)
			{
				direct_output_related_manager ("search_index_form","post_module_service_action_embedded");
				$direct_classes['output']->oset ("default_embedded","done");
			}
			else
			{
				direct_output_related_manager ("search_index_form","post_module_service_action");
				$direct_classes['output']->oset ("default","done");
			}

			$direct_classes['output']->options_flush (true);
			$direct_classes['output']->header (false,true,$direct_settings['p3p_url'],$direct_settings['p3p_cp']);
			$direct_classes['output']->page_show ($direct_cachedata['output_job']);
		}
		else { $direct_classes['output']->redirect (direct_linker ("url1",$g_target_link,false)); }
	}
	else { $direct_classes['error_functions']->error_page ("standard","core_tid_invalid","sWG/#echo(__FILEPATH__)# _a=run_ (#echo(__LINE__)#)"); }
	//j// EOA
	}

	$direct_cachedata['core_service_activated'] = true;
	break 1;
}
//j// EOS
}

//j// EOF
?>