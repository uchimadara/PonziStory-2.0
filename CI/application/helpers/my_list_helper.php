<?php

function render_group_list($container, $grouping, $table_defs, $table_data, $cur_sort = '', $sort_dir, $select_box = FALSE, $totals = '') {

    if (empty($table_data)) {

        $html = '<div style="margin:10px;padding:10px;font-weight:bold;color:#eeeeee;">No items to display.</div>';
    } else {
        $gd        = array();
        $cur_group = '';
        $html      = '';

        // echo var_dump($table_data);

        foreach ($table_data as $td) {

            if ($cur_group == '') {
                $cur_group = $td[$grouping['field']];

                if (array_key_exists('href', $grouping))
                    $ghtml = '<a href="'.sprintf($grouping['href'], $td[$grouping['href_key']]).'">'.$cur_group.'</a>';
                else
                    $ghtml = '<span class="reportgroup-value">'.$cur_group.'</span>';
            }

            if ($cur_group != $td[$grouping['field']]) {

                $html .= '<div class="reportgroup">'.((!empty($grouping['label'])) ? $grouping['label'].':  &nbsp; ' : '');
                $html .= $ghtml;
                $html .= '</div>';

                $t = (empty($totals)) ? '' : $totals[$cur_group];
                $html .= render_list($container, $table_defs, $gd, $cur_sort, $sort_dir, $select_box, $t);

                $cur_group = $td[$grouping['field']];
                if (array_key_exists('href', $grouping))
                    $ghtml = '<a href="'.sprintf($grouping['href'], $td[$grouping['href_key']]).'">'.$cur_group.'</a>';
                else
                    $ghtml = '<span class="reportgroup-value">'.$cur_group.'</span>';
                $gd = array();
            }
            $gd[] = $td;
        }
        if (!empty($gd)) {
            $html .= '<div class="reportgroup">'.((!empty($grouping['label'])) ? $grouping['label'].':  &nbsp; ' : '').$ghtml.'</div>';
            $t = (empty($totals)) ? '' : $totals[$cur_group];
            $html .= render_list($container, $table_defs, $gd, $cur_sort, $sort_dir, $select_box, $t);
        }
    }
    return $html;
}

function render_list($container, $table_defs, $table_data, $cur_sort = '', $sort_dir, $select_box = FALSE, $totals = '') {

//log_message ('debug', "<<bjb>> render_list: cur_sort=$cur_sort sort_dir=$sort_dir");

    //$select_box = true;


    if (empty($table_data)) {

        $html = '<div style="margin:10px;padding:10px;font-weight:bold;color:#333;">No items to display.</div>';
    } else {


        $CI = & get_instance();

        $html = '<div id="'.$container.'-list" class="list-container-box">
		<table width="100%" cellpadding="2" cellspacing="1" border="0">';

        $html .= header_row($container, $table_defs, $cur_sort, $sort_dir, $select_box, $CI->session->userdata('group_id'));

        $bg_color = $color1 = LIST_COLOR1;
        $color2   = LIST_COLOR2;
        $style    = array('style' => "color:#000");

        $cur_field = '';

        foreach ($table_data as $co) {

            if (is_object($co)) $co = (array)$co;

            $html .= '<tr style="height:20px;background-color:'.$bg_color.'" >';

            foreach ($table_defs as $t) {

                $do_it = TRUE;
                if (array_key_exists('roles', $t)) $do_it = in_array($CI->session->userdata('group_id'), $t['roles']);

                if ($do_it) {
                    $align = '';
                    $field = $co[$t['field_name']];

                    //log_message('debug', '<<bjb>> render_list: field_name='.$t['field_name'].' value='.$field);
                    $field_count = 0;
                    if (array_key_exists('format', $t)) {
                        switch ($t['format']) {

                            case 'field_counter':
                                if ($cur_field != $co[$t['field_name']]) {
                                    $field_count = 1;
                                    $cur_field   = $co[$t['field_name']];
                                } else {
                                    $field_count++;
                                }
                                $field = $field_count;
                                break;

                            case 'currency':
                                if (floatval($field) < 0) {
                                    $field = '<span style="font-color:red;">$'.number_format($field, 2, ".", ",").'</span>';
                                } else {
                                    $field = '$'.number_format($field, 2, ".", ",");
                                }
                                $align = 'align="right"';
                                break;

                            case 'date':
                                if (empty($field) || $field == '0000-00-00')
                                    $field = '';
                                else
                                    $field = date('M d, Y', strtotime($field));
                                break;

                            case 'time':
                                if (empty($field) || $field == '00:00:00')
                                    $field = '';
                                else
                                    $field = date('g:i a', strtotime(date('Y-m-d ').$field));
                                break;

                            case 'datetime':
                                if (empty($field) || $field == '0000-00-00 00:00:00') {
                                    $field = '';
                                } else {
                                    $date_format = 'M d, Y g:i a';
                                    if (isset($t['date_format'])) $date_format = $t['date_format'];
                                    $field = date($date_format, strtotime($field));
                                }
                                break;

                            case 'yesno':
                                $field = ($field == 0) ? 'no' : 'yes';
                                break;

                            case 'select':
                                $field = $CI->picklist->select_value($t['select_list'], $field);
                                break;

                            case 'image':
                                $field = '<img style="border:none;" src="'.$t['src'].'" />';
                                break;

                            case 'popup':
                                $aid = '';
                                if ($field != '') $aid = 'id="'.$field.'"';
                                if (array_key_exists('class', $t)) {
                                    $field     = '<a '.$aid.' class="'.$t['class'].'" style="display:block;" title="'.$t['title'].'" href="javascript:void(0);"';
                                    $the_field = '';
                                } else {
                                    $the_field = $field;
                                    $field     = '<a '.$aid.' style="display:inline;" title="'.$t['title'].'" href="javascript:void(0);"';
                                }
                                if (is_array($t['onclick_key'])) {
                                    $args = array();
                                    foreach ($t['onclick_key'] as $k) {
                                        $args[] = $co[$k];
                                    }
                                    $field .= ' onclick="'.vsprintf($t['onclick'], $args).'"';
                                } else {
                                    $field .= ' onclick="'.sprintf($t['onclick'], $co[$t['onclick_key']]).'"';
                                }
                                $field .= '>'.$the_field.'</a>';
                                break;

                            case 'last_note':
                                $field = getNoteLink($co);
                                break;

                            case 'checkbox':

                                $field = '<input type="checkbox"
																 name="'.$t['field_name'].'"
																 value="1"
																 onchange="javascript:saveRadioOrCheckbox(\''.$t['field_name'].'\',this.checked,\''.$t['table'].'\',\''.$co['id'].'\',\'updateField\');"
																 checked="true" />';
                                $align = 'center';
                                break;
                            default:
                                //$field .= $field;
                                break;
                        }
                    }

                    if (array_key_exists('align', $t)) {
                        $align = ' align="'.$t['align'].'" ';
                    }

                    $tip = '';
                    if (array_key_exists('hover_fields', $t)) {
                        foreach ($t['hover_fields'] as $f) {
                            if ($tip != '') $tip .= '<br />';
                            $tip .= '<b>'.ucwords(str_replace('_', ' ', $f)).':</b> ';
                            if (strpos($f, 'date')) {
                                if (!empty($co[$f]) && $co[$f] != '0000-00-00 00:00:00' && $co[$f] != '0000-00-00')
                                    $tip .= date('d-M-y g:ia', strtotime($co[$f]));
                                else
                                    $tip .= 'N/A';
                            } else {
                                $tip .= $co[$f];
                            }
                        }
                        $tip = ' onmouseover="Tip(\''.$tip.'\');" onmouseout="UnTip();"';
                    }

                    if (array_key_exists('href', $t)) {
                        if (is_array($t['href_key'])) {
                            $args = array();
                            foreach ($t['href_key'] as $k) {
                                $args[] = $co[$k];
                            }
                            $link = '<a href="'.vsprintf($t['href'], $args).'"';
                        } else {
                            $link = '<a href="'.sprintf($t['href'], $co[$t['href_key']]).'"';
                        }
                        if (array_key_exists('title', $t)) {
                            $link .= ' title="'.$t['title'].'"';
                        }
                        if (array_key_exists('class', $t)) {
                            $link .= ' class="'.$t['class'].'"';
                        }

                        $field = $link.' '.$tip.'>'.$field.'</a>';
                    } elseif ($tip != '') {
                        $field = '<a href="javascript:void(0);" '.$tip.'>'.$field.'</a>';
                    }

                    if (array_key_exists('viewlinks', $t)) {
                        foreach ($t['viewlinks'] as $v) {
                            $v = "viewlink_$v";
                            $field .= $v($co);
                        }
                    }

                    if (array_key_exists('attachment', $t)) {
                        if (!empty($co['filename'])) {
                            $plus      = (array_key_exists('attachment_plus', $t)) ? $t['attachment_plus'] : '';
                            $file_link = "<a
								href='".base_url()."download/attachment/".$co['id'].$plus."'
								class='file-link'
								onmouseover=\"Tip('".$co['filename']."')\" onmouseout=\"UnTip()\">&nbsp;</a>";
                            $field     = $file_link.$field;
                        }
                    }
                    if (array_key_exists('document', $t)) {
                        if (!empty($co['filename'])) {
                            $plus      = (array_key_exists('attachment_plus', $t)) ? $t['attachment_plus'] : '';
                            $file_link = "<a
								href='".base_url()."download/document/".$co['id'].$plus."'
								class='file-link'
								onmouseover=\"Tip('".$co['filename']."')\" onmouseout=\"UnTip()\">&nbsp;</a>";
                            $field     = $file_link.$field;
                        }
                    }
                    $color = 'black';
                    if (isset($t['conditional_color'])) {
                        if (is_array($t['conditional_color'])) {
                            if (array_key_exists($field, $t['conditional_color'])) {
                                $color = $t['conditional_color'][$field];
                            }
                        }
                    }
                    $html .= '<td class="listitem" width="'.$t['width'].'" '.$align.' style="padding:5px;color:'.$color.'">'.$field.'</td>';
                }
            }
            if ($select_box !== FALSE) {
                $html .= '<td align="right">';
                $html .= form_checkbox(array('name' => 'selected_items[]', 'value' => $co['id'], 'id' => 'item_'.$co['id']));
                $html .= '&nbsp;&nbsp;&nbsp;';
                $html .= '</td>';
            }


            $html .= '</tr>						';
            $bg_color = ($bg_color == $color1) ? $color2 : $color1;
        }

        if (!empty($totals)) $html .= totals_row($table_defs, $totals);

        $html .= '        </table></div><br />';
    }
    return $html;
}

function header_row($container, $headers, $cur_sort, $sort_dir, $select, $group) {


//log_message ('debug', "<<bjb>> header_row: cur_sort=$cur_sort sort_dir=$sort_dir");

    $hr = "<tr class='reportheader'>";
    foreach ($headers as $header) {

        $do_it = TRUE;
        if (array_key_exists('roles', $header)) $do_it = in_array($group, $header['roles']);

        if ($do_it) {
            if (!array_key_exists('nosort', $header)) {

                $hr .= " <th width='".$header['width']."' class='reportheader'>";

                if ($cur_sort == $header['field_name']) {
                    //log_message ('debug', "<<bjb>> header_row: Currently Sorted - cur_sort=$cur_sort sort_dir=$sort_dir");
                    $hr .= "<img src ='".base_url()."images/sort_".$sort_dir.".gif' alt='' />";
                    $col_sort_dir = ($sort_dir == 'asc') ? 'desc' : 'asc';
                } else {
                    $col_sort_dir = 'asc';
                }
                if (array_key_exists('format', $header) && $header['format'] == 'image') {
                    $hr .= $header['label']."</th>";
                } else {
                    $hr .= "  <a href='javascript:void(0);' onclick='set".$container."Sort(\"".$header['field_name']."\",\"".$col_sort_dir."\");' class='reportheader'>".$header['label']."</a></th>";
                }
            } else {
                $hr .= " <th width='".$header['width']."' class='reportheader'>{$header['label']}";
            }
        }
    }

    if ($select !== FALSE) {
        $hr .= '<th align="right">';
        $hr .= ($select == 1) ? 'delete' : $select;
        $hr .= '&nbsp;';
        $hr .= '</th>';
    }

    $hr .= '</tr>';
    return $hr;
}


function totals_row($defs, $totals) {


//log_message ('debug', "<<bjb>> header_row: cur_sort=$cur_sort sort_dir=$sort_dir");

    $tr = "<tr class='reporttotalrow'><td>TOTALS</td>";
    foreach ($defs as $col) {
        if (array_key_exists($col['field_name'], $totals)) {
            $tr .= " <td class='reporttotal'>{$totals[$col['field_name']]}</td>";
        }
    }

    $tr .= '</tr>';
    return $tr;
}

/*********
 *
 */

function getNoteLink($d) {

    $note_text = '';

    if (isset($d['last_note_name']) && $d['last_note_name'] != '') {

        $tip = "<b>By: </b>".$d['last_note_by']."<br>";
        $tip .= "<b>Date: </b>".date('D M-d g:ia', strtotime($d['last_note_date']))."<br>";
        $tip .= "<b>Note:</b> ".htmlentities(strtr($d['last_note_desc'], array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/')));
        $note_text = "<a href='javascript:void(0);' onclick='popupEditNote(\"{$d['last_note_id']}\");' onmouseover=\"Tip('".$tip."');\" onmouseout=\"UnTip();\">".$d['last_note_name']."</a>";
    }


    return $note_text;
}

/*********
 *
 */

function viewlink_appt($d) {

    $CI    = get_instance();
    $query = "SELECT id, appt_date, note FROM appointments WHERE  parent_id='{$d['id']}' AND deleted='0' AND now() < appt_date ORDER BY appt_date desc";
    $q     = $CI->db->query($query);
    if ($q->num_rows() > 0) {
        $appts      = $q->row();
        $appt_popup = "<b>Date:</b> ".date('D M-d @ g:ia', strtotime($appts->appt_date))."<br><b>Note:</b> ".strtr($appts->note, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));
        //$appt_popup = "<b>Date:</b> ".date('D M-d @ g:ia',strtotime($appts->appt_date))."<br><b>Note:</b> ".htmlentities($appts->note);
        //$appt_link = "<a title='Change Appointment' href='javascript:void(0);' onclick='popupEditAppt(\"".$appts->appt_id."\");' class='anote'>%s</a>&nbsp;";

        return "&nbsp;<a id='".$appts->id."' title='Change Appointment' href='javascript:void(0);' onclick='popupEditAppt(\"".$appts->id."\");' class='appt' onmouseover=\"Tip('".$appt_popup."')\" onmouseout=\"UnTip()\" > </a>";
    } else {
        return '';
    }
}
