<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
 * Form Library
 *
 * @package        rms
 * @subpackage    forms
 * @category    Libraries
 * @author        Brian Basser
 */
class RMSForm {

    private $CI = NULL;
    private $_prefix = '';
    private $_form_def = NULL;
    private $_form_name = NULL;
    private $_user = NULL;

    public function __construct($path, $formName, $prefix = '') {
        $this->CI         = & get_instance();
        $this->_user      = $this->CI->ion_auth->user()->row();
        $this->_form_name = $formName;

        $this->CI->load->config($path.$formName);
        $this->_form_def         = $this->CI->config->item('form_'.$formName);
        $this->_form_def['rows'] = FALSE; // true; // this encloses each label/field pair in a div on a single row

        $this->_prefix = $prefix;
    }

    public function get_table() {
        return $this->_form_def['table'];
    }

    public function get_title() {
        return (array_key_exists('title', $this->_form_def)) ? $this->_form_def['title'] : ucwords(str_replace('_', ' ', $this->_form_name));
    }

    public function render($data) {

        $html = '';

        foreach ($this->_form_def['fields'] as $field) {
            $html .= $this->form_field($field, $data);
        }

        return $html;
    }

    public function buttons() {
        return array_key_exists('buttons', $this->_form_def) ? $this->_form_def['buttons'] : array();
    }

    // ------------------------------------------------------------------------
    function search_array($v, $k, $a) {
        //search array, a for value v in key, k ~ used for determining if checked="checked"
        foreach ($a as $item) {
            if (array_key_exists($k, $item))
                if ($item[$k] == $v)
                    return TRUE;
        }
        return FALSE;
    }

    /**
     * Form Field
     *
     * @abstract Creates the entire HTML form element(s) based on custom types
     *
     * @access    public
     * @param    field    array of attributes for the form element
     * @param    data (optional) array of key,value pairs for default values
     * @param prefix (optional) string to place in front of field names/ids (used to create unique names for fields with the same name)
     *    this is useful if you have a form with, say, borrower data and then there is identical
     *    co_borrower data on the same form. You can use the same config array to produce all the form fields for both.
     * @return    string containing HTML for the form element
     */
    function form_field(&$field, &$data) {
        $script    = $html = '';
        $label     = '';
        $extra_tag = '';
        if (!empty($field['label']))
            $label = $field['label'];
        if (!empty($field['extra_tag']))
            $extra_tag = $field['extra_tag'];

        //echo $field['label'];

        log_message('debug', '<<bjb>> form_field: type='.$field['type']);

        if ($label == 'no_label')
            $label = '';

        if ($label == 'Secret Question')
            $label = $this->_user->secret_question;

        switch ($field['type']) {
            // handle non-data-related types at first (no field_value),
            // then the data related types in the default case (field_value required)

            case 'ci_view':
                $html .= $this->CI->load->view($field['view_file'], $data, TRUE);
                break;

            case 'blank':
                ;
                break;

            case 'fill':
                ;
                break;

            case 'head':
                $html .= '<div class="formHead">';
                $html .= $label;
                $html .= '</div>';
                break;

            case 'checklist':
                $list_html = '<table class="table"><tr>';

                $cl = $this->CI->picklist->select_values($field['select_list']);
                $i  = 0;
                //echo var_dump($data[$field['data_list']]);

                foreach ($cl as $id => $list_item) {
                    $sel = '';
                    if (!empty($data[$field['data_list']]) && $this->search_array($id, 'id', $data[$field['data_list']]))
                        $sel = ' checked="checked" ';

                    $list_html .= '<td><input class="form-control input-sm" type="checkbox" name="'.$this->_prefix.$field['data_list'].'[]" id="'.$this->_prefix.$field['data_list'].$id.'" value="'.$id.'"'.$sel.' />'.$list_item.'</td>';

                    if (++$i%$field['checks_per_row'] == 0)
                        $list_html .= "</tr><tr>";
                }
                $html .= '<div class="form-group"><label for="'.$this->_prefix.$field['data_list'].'">'.$label.'</label>';
                $html .= '<div class="">';
                $html .= $list_html.'</tr></table>';
                $html .= '</div>';
                $html .= '</div>';
                break;

            case 'item_list': // produces a list of items; input elements and a pop-up form for adding new items
                // also includes icons for edit and delete

                $html .= '<div class="form-group"><div class="formLabel">'.$label;
                $html .= '<div class="formPopup">'.anchor($field['form_url'].$data['id'], "ADD", "class='btn btn-alt btn-xs popup'").'</div></div>';
                $html .= '<div class="formField itemList" id="'.$field['list_name'].'">'; // main form element wrapper
                $html .= '<span class="loading"></span></div>';
                $html .= '<div class="clear"></div></div>'; // END OF MAIN FORM ELEMENT WRAPPER

                $url = sprintf($field['list_url'], $data[$field['list_url_key']]);

                $html .= "<script >$.get('$url', function (data) { $('#".$field['list_name']."').html(data);});</script >";
                break;

            default: // all the rest are data fields (field_name is a required index)
                // assign the field value from available resources

                $field_value = NULL;
                if (!empty($data)) {
                    $field_name = $field['field_name'];
                    if (is_array($data)) {
                        if (isset($data[$field_name]))
                            $field_value = $data[$field_name];
                    } else {
                        if (isset($data->$field_name))
                            $field_value = $data->$field_name;
                    }
                }
                if (is_null($field_value) && !empty($field['value'])) { // set to default if null only - allows for changing field to blank
                    $field_value = $field['value'];
                } else if (array_key_exists('encrypted', $field)) {
                    $field_value = $this->CI->encrypt->decode($field_value);
                }

                if ($field['type'] != 'hidden') {
                    // set up HTML that indicates a "required" field

                    $req = $this->mark_required($field);

                    if (array_key_exists('bold_label', $field))
                        $label = '<b>'.$label.'</b>';

                    // OUTPUT the Label

                    $html .= '<div class="form-group"><label for="'.$this->_prefix.$field['field_name'].'">'.$label.'</label>'.$req;
                    if (array_key_exists('tip', $field)) {
                        $html .= ' <a class="tip" title="'.$field['tip'].'"><i class="fa fa-question-circle"></i></a>';
                    }

                    // output the HTML based on the field type
                    $class = (isset($field['class'])) ? $field['class'] : "form-control input-sm";
                    $extra_tag .= ' class="'.$class.'" ';
                }

                switch ($field['type']) {

                    case 'hidden':
                        $html .= form_hidden($field['field_name'], $field_value);
                        break;

                    case 'select':

                        if (array_key_exists('display_only', $field)) {

                            $field_value = $this->get_select_value($field['select_list'], $field_value);
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= '<select class="form-control" id="'.$this->_prefix.$field['field_name'].'" '.$extra_tag;

                            if (!empty($field['onclick'])) {
                                $html .= ' onchange="'.$field['onclick'].';"';
                            }
                            if (!empty($field['size'])) {
                                $html .= ' name="'.$this->_prefix.$field['field_name'].'[]" multiple="multiple" size="'.$field['size'].';"';
                            } else {
                                $html .= ' name="'.$this->_prefix.$field['field_name'].'"';
                            }
                            $html .= '>';
                            $html .= $this->get_select_options($field['select_list'], $field_value, !array_key_exists('blank_first', $field));
                            $html .= '</select>';
                        }

                        break;

                    case 'content_load_select':

                        if (array_key_exists('display_only', $field)) {

                            $field_value = $this->get_select_value($field['select_list'], $field_value);
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= '<select class="form-control" id="'.$this->_prefix.$field['field_name'].'" '.$extra_tag;

                            if (!empty($field['onclick'])) {
                                $html .= ' onchange="'.$field['onclick'].';"';
                            }
                            if (!empty($field['size'])) {
                                $html .= ' name="'.$this->_prefix.$field['field_name'].'[]" multiple="multiple" size="'.$field['size'].';"';
                            } else {
                                $html .= ' name="'.$this->_prefix.$field['field_name'].'"';
                            }
                            $html .= '>';
                            $html .= $this->get_select_options($field['select_list'], $field_value, !array_key_exists('blank_first', $field));
                            $html .= '</select>';

                            $html .= '<script>$( "#'.$this->_prefix.$field['field_name'].'" ).change(function() {'
                                    .'var url = "'.$field['view_file'].'" + $(this).val().split(\'-\')[2].split(\'x\')[0];';
                            $html .= "$.get(url, function (data) { $('#content_load_select').html(data);});";
                            $html .= '});</script>';
                            $html .= '<div id="content_load_select"></div>';
                        }

                        break;

                    case 'popup_select':
                        if (array_key_exists('display_only', $field)) {

                            $field_value = $this->get_select_value($field['select_list'], $field_value);
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= '<input class="form-control input-sm" type="hidden" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" value="'.htmlentities($field_value).'" />';
                            $html .= '<div id="'.$this->_prefix.$field['field_name'].'_dialog" style="display:none" title="'.$field['button_label'].'">';
                            $html .= $this->get_popup_options($field['select_list'], $this->_prefix.$field['field_name'], $field_value);
                            $html .= '</div>';

                            $html .= '<span class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding:5px;" onclick="popUp(\''.$this->_prefix.$field['field_name'].'_dialog\');">';
                            $html .= $field['button_label'];
                            $html .= '</span><br />&nbsp;&nbsp;<span id="'.$this->_prefix.$field['field_name'].'_display">';
                            if (!empty($field_value)) {
                                $html .= $field_value;
                            }
                            $html .= '</span>';
                        }
                        break;

                    case 'radio':
                        if (array_key_exists('display_only', $field)) {

                            $field_value = $this->get_select_value($field['select_list'], $field_value);
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= $this->get_radio_options($field['field_name'], $field['select_list'], $field_value);
                        }
                        break;

                    case 'checkbox':
                        $disabled = '';
                        if (array_key_exists('display_only', $field)) {
                            $disabled = ' disabled="true"';
                        }
                        $checked = ($field_value == 1) ? ' checked="checked" ' : ' ';
                        $html .= '<input class="" type="checkbox" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" value="1" '.$checked.$extra_tag.$disabled.'/>';
                        //$html .= '<div style="clear:both;"></div>';
                        break;

                    case 'combo':
                        if (array_key_exists('display_only', $field)) {
                            $field_value = $this->get_select_value($field['select_list'], $field_value);
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= '<select class="combo" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" ';
                            if (!empty($field['onclick'])) {
                                $html .= ' onchange="'.$field['onclick'].';"';
                            }
                            $html .= '>';
                            $html .= $this->get_select_options($field['select_list'], $field_value);
                            $html .= '</select>';
                        }
                        break;

                    case 'text':
                        if (array_key_exists('display_only', $field)) {
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '<input class="form-control input-sm" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'"  maxlength="'.$field['maxlength'].'" value="'.htmlentities($field_value).'" '.$extra_tag.'/>';
                        }
                        break;

                    case 'secret':
                        $html .= '<input class="form-control input-sm" type="text" name="secret_answer" id="secret_answer"  maxlength="'.$field['maxlength'].'" placeholder="Enter your secret answer" '.$extra_tag.'/>';
                        break;

                    case 'email':
                        if (array_key_exists('display_only', $field)) {
                            $field_value = '<span class="displayOnly">'.$field_value.'</span>';
                            $html .= $field_value;
                        } else {
                            $html .= '<input class="form-control input-sm" class="email" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'"  maxlength="'.$field['maxlength'].'" value="'.htmlentities($field_value).'" '.$extra_tag.'/>';
                        }
                        break;

                    case 'password':
                        $html .= '<input class="form-control input-sm" type="password" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'"  maxlength="'.$field['maxlength'].'" value="'.htmlentities($field_value).'" '.$extra_tag.'/>';
                        break;

                    case 'html':
                        if (array_key_exists('display_only', $field)) {

                            $field_value = nl2br($field_value);
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $htmlEdit = '';
                            if (USE_FCKEDITOR) {
                                $htmlEdit = 'htmlEdit ';
                            }
                            $html .= '<textarea class="'.$htmlEdit.'form-control" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" rows="'.$field['rows'].'" cols="'.$field['cols'].'">'.$field_value.'</textarea>';
                        }
                        break;

                    case 'textarea':
                        if (array_key_exists('display_only', $field)) {

                            $field_value = nl2br($field_value);
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $class = (array_key_exists('class', $field)) ? 'class="'.$field['class'].'"' : '';
                            $html .= '<textarea '.$class.' name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" rows="'.$field['rows'].'" cols="'.$field['cols'].'">'.$field_value.'</textarea>';
                        }

                        break;

                    case 'date':
                        if (!empty($field_value))
                            $field_value = date(MYSQL_DATE_FORMAT, $field_value);

                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? $field_value : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '<div class="input-append date">
                                <input type="text" class="form-control input-sm dp" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" value="'.$field_value.'"/>
                                <span class="add-on"><i class="fa fa-calendar dpIcon"></i></span>
                            </div>';
                            //$html .= '<input class="form-control input-sm" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" size="10" maxlength="10" value="'.$field_value.'" />';
                        }
                        //$script .= 'datePicker($("input[name='.$this->_prefix.$field['field_name'].']"));';
                        break;

                    case 'date_range':
                        $html .= '<input class="form-control input-sm" class="dp" type="text" name="date_start" id="date_start" size="10" maxlength="10" value="'.set_value('date_start').'" />';
                        $html .= '<span class="dateRangeSep"> to </span>';
                        $html .= '<input class="form-control input-sm" class="dp" type="text" name="date_end" id="date_end" size="10" maxlength="10" value="'.set_value('date_end').'" />';

                        break;

                    case 'time':
                        if (empty($field_value) || $field_value == 0)
                            $field_value = date(DEFAULT_TIME_FORMAT, now());
                        else
                            $field_value = date(DEFAULT_TIME_FORMAT, $field_value);

                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? $field_value : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= form_dropdown("send_hour", $this->get_option_array('hours'), str_pad(date('h', strtotime("+1 hour")), 2, '0', STR_PAD_LEFT), ' class="timeSelect"');
                            $html .= form_dropdown("send_meridian", $this->get_option_array('meridian'), (date('G', strtotime("+1 hour")) < 12) ? 0 : 12, ' class="meridianSelect"');
                            $html .= '<div class="clear"></div>';
                        }
                        break;

                    case 'datetime':
                        if (array_key_exists('display_only', $field)) {
                            if (empty($field_value) || $field_value == 0)
                                $field_value = ''; //date(MYSQL_DATETIME_FORMAT, now());
                            else
                                $field_value = date(DEFAULT_DATETIME_FORMAT, $field_value);

                            //$field_value = ($field_value) ? $field_value : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            if (empty($field_value) || $field_value == 0)
                                $field_value = ''; //date(MYSQL_DATETIME_FORMAT, now());
                            else
                                $field_value = date(MYSQL_DATETIME_FORMAT, $field_value);

                            $html .= '<div class="input-append date">
                                <input data-format="dd-MM-yyyy" type="text" class="form-control input-sm dp" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" value="'.$field_value.'"/>
                                <span class="add-on"><i class="fa fa-calendar dpIcon"></i></span>
                            </div>';
                            //$html .= '<input class="form-control input-sm" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" size="10" maxlength="10" value="'.$field_value.'" />';
                        }
                        //$script .= 'datePicker($("input[name='.$this->_prefix.$field['field_name'].']"));';
//                        if ($field_value == 0) $field_value = '';
//                        if (array_key_exists('display_only', $field)) {
//                            $html .= ($field_value) ? date(DEFAULT_DATETIME_FORMAT, $field_value) : 'unknown';
//                        } else {
//                            if ($field_value != '') $field_value = date('M j, Y H:i:s', strtotime($field_value)); // prep for javascript
//                            $html .= '<input class="form-control input-sm" class="dateTimePick" type="text" id="'.$this->_prefix.$field['field_name'].'" name="'.$this->_prefix.$field['field_name'].'" value="'.$field_value.'"  />';
//                        }
                        break;

                    case 'phone':
                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? $field_value : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '<input class="form-control input-sm" class="phone" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" size="20" maxlength="20" value="'.$field_value.'" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);">';
                        }
                        break;

                    case 'currency':
                        if (!empty($field_value))
                            $field_value = number_format($field_value, 2, ".", ",");
                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? money($field_value) : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '₦<input class="form-control input-sm" class="currency" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" size="15" maxlength="15" value="'.$field_value.'" />';
                        }
                        break;

                    case 'float':
                        //if (!empty($field_value)) $field_value = number_format($field_value,,".","");
                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? number_format($field_value, 2) : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '<input class="form-control input-sm" class="floatingPoint" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'"  maxlength="'.$field['maxlength'].'" value="'.$field_value.'" onkeyup="javascript:maskAmount(this);" '.$extra_tag.'/>';
                        }
                        break;

                    case 'int':
                        if (array_key_exists('display_only', $field)) {
                            $field_value = ($field_value) ? $field_value : 'unknown';
                            $html .= '<span class="displayOnly">'.$field_value.'</span>';
                        } else {
                            $html .= '<input class="form-control input-sm" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'"  maxlength="'.$field['maxlength'].'" value="'.$field_value.'" onkeyup="javascript:maskInteger(this);"/>';
                        }
                        break;

                    case 'file':
                        $html .= '<input class="form-control input-sm" type="file" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" />';
                        $html .= ($field_value) ? $field_value : 'n/a';
                        break;

                    case 'img_url':
                        $html .= '<input class="form-control input-sm" type="text" name="'.$this->_prefix.$field['field_name'].'" id="'.$this->_prefix.$field['field_name'].'" onblur="validateImageUrl(this);" size="50" maxlength="255" />';
                        $html .= '<span class="validatedImage" id="'.$this->_prefix.$field['field_name'].'_image'.'"></span>';
                        break;

                    case 'image':
                        clearstatcache();
                        $html .= '<div class="formImage">';
                        if ($field_value == '') {
                            $html .= 'No image assigned';
                        } else {
                            $photo_file = (!empty($data) && $field['img_key']) ? sprintf($field['realpath'], $data[$field['img_key']]) : $field['realpath'].trim($field_value);

                            $image_properties = array(
                                // 'width'  => $width,
                                // 'height' => $height,
                                'src' => $photo_file,
                            );
                            $html .= img($image_properties);
                        }
                        $html .= '</div>';
                        break;
                }
                if ($field['type'] != 'hidden')
                    $html .= '</div>';

                break;
        }
        if ($this->_form_def['rows'])
            $html = "<div class='formRow'>$html</div>";
        if ($script != '')
            $script = "<script>$script</script>";
        return $html.$script;
    }

    /*
     * Function to output select options from select list
     */

    function get_select_options($list, $select = '', $blank_first = TRUE) {

        $option_list = '';

        if (is_array($list)) {

            $option_array = $list;
        } else {

            $option_array = $this->CI->picklist->select_values($list);
        }
        if ($blank_first)
            $option_list .= '<option value=""></option>';

        foreach ($option_array as $key => $value) {
            $selected = '';
            if ((is_array($select) && in_array($key, $select)) || $key == $select)
                $selected = ' selected="selected"';
            $option_list .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
        }
        return $option_list;
    }

    /*
     * Function to get a single value from an option list
     */

    function get_select_value($list, $select) {

        return $this->CI->picklist->select_value($list, $select);
    }

    /*
     * Function to output a red asterisk
     */

    function mark_required($f) {

        $html = '';
        if (array_key_exists('required', $f) && $f['required'] == 'yes') {
            $html = '<span class="red">*</span>';
        }
        return $html;
    }

    function get_option_array($list) {

        return $this->CI->picklist->select_values($list);
    }

    /*
     * Function to output radio buttons from select list
     */

    function get_radio_options($name, $list, $select = '') {

        $option_array = $this->CI->picklist->select_values($list);
        $option_list  = '';

        if ($list == 'yesno_list') {
            foreach ($option_array as $key => $value) {
                $selected = '';
                if ((is_array($select) && in_array($key, $select)) || $key == $select) {
                    $selected = 'checked="checked"';
                }

                $option_list .= '<input class="form-control input-sm" type="radio" name="'.$name.'['.$key.']" id="'.$name.$key.'" value="'.$key.'" '.$selected.' />';
                $option_list .= '<span class="radioLabel">'.$value.'</span>';
            }
        }
        return $option_list.'<div style="clear:both;"></div>';
    }

    // the following function will create an array starting from 0 that is ready for insert into database
    // independent of the indicies of the post data (which can be missing due to delete actions)
    // all the post arrays in each function should all be exactly the same size.


    function get_list_from_post($list) {
        $r = array();

        //echo print_r($this->_children[$this->data['table']]);

        foreach ($list['subform'] as $f) {
            if (array_key_exists('field_name', $f) and !empty($post[$list['list_prefix'].$f['field_name']])) {
                //echo 'processing... '.$this->_children[$this->data['table']][$list]['list_prefix'].$f['field_name'].'<br />';
                $i = 0;
                foreach ($post[$list['list_prefix'].$f['field_name']] as $v)
                    $r[$i++][$f['field_name']] = $v;
            }
        }
        //if (!empty($r)) { // add in the id field, because it is not part of the form def.
        //$i=0;    foreach ($this->input->post($this->_children[$this->data['table']][$list]['list_prefix'].'id') as $v) $r[$i++]['id'] = $v;  
        return $r;
        //}
    }

    function to_html($string, $encode = TRUE) {

        $toHTML = array(
            '"'  => '&quot;',
            '<'  => '&lt;',
            '>'  => '&gt;',
            '& ' => '&amp; ',
            "'"  => '&#039;',
        );

        if ($encode && is_string($string)) { //$string = htmlentities($string, ENT_QUOTES);
            $string = str_replace(array_keys($toHTML), array_values($toHTML), $string);
        }
        return $string;
    }

    function from_html($string, $encode = TRUE) {
        $toHTML = array(
            '"'  => '&quot;',
            '<'  => '&lt;',
            '>'  => '&gt;',
            '& ' => '&amp; ',
            "'"  => '&#039;',
        );
        if ($encode && is_string($string)) {
            $string = str_replace(array_values($toHTML), array_keys($toHTML), $string);
        }
        return $string;
    }

    function validate($post) {

        $this->CI->load->library('form_validation');

        //echo var_dump($this->_form_def);

        foreach ($this->_form_def['fields'] as $field) {

            if (array_key_exists('field_name', $field) && $field['type'] != 'file') {

                if ($field['type'] == 'radio') {

                    if (array_key_exists($field['field_name'], $post)) {
                        $post[$field['field_name']] = $post[$field['field_name']][0]; // pull the value out of the array
                    } else {
                        if (array_key_exists('required', $field)) {
                            $this->data['error'] = TRUE;
                            $this->data['err_msg'] .= 'It is required that you answer, "'.$field['label'].'"<br />';
                        }
                    }
                } elseif ($field['type'] == 'checkbox') {
                    if (!array_key_exists($field['field_name'], $post))
                        $post[$field['field_name']] = 0;
                } else {
                    if (array_key_exists('rule', $field)) {
                        $rule = $field['rule'];
                    } else {
                        $rule = 'xss_clean';
                        if ($field['type'] != 'select' && $field['type'] != 'radio')
                            $rule = 'trim|'.$rule;
                    }
                    //echo 'applying rule: '.$rule. ' to field '.$field['field_name'].'<br/>';

                    $this->CI->form_validation->set_rules($field['field_name'], $field['label'], $rule);
                }
            }
        }

        // echo 'RUNNING FORM VALIDATION...<BR>';

        $data = array();

        if ($this->CI->form_validation->run() === FALSE) {

            $data['errorElements'] = $this->CI->form_validation->error_array();
        } else {

            $items = Array('/\ /', '/\+/', '/\-/', '/\,/', '/\(/', '/\)/', '/[a-zA-Z]/');
            foreach ($this->_form_def['fields'] as $f) {
                if (isset($f['field_name']) && array_key_exists($f['field_name'], $post)) {
                    if ($f['type'] == 'currency') {
                        if (array_key_exists($f['field_name'], $post))
                            $post[$f['field_name']] = preg_replace($items, '', $post[$f['field_name']]);
                    }
                    if (strpos($f['type'], 'date') !== FALSE) {
                        $post[$f['field_name']] = strtotime($post[$f['field_name']]);
                    }
                    if (array_key_exists('encrypted', $f)) {
                        $post[$f['field_name']] = $this->encrypt->encode($post[$f['field_name']]);
                    }
                }
            }

            $data['success'] = $post;
        }

        return $data;
    }
}

/* End of file my_form_helper.php */
/* Location: ./application/helpers/my_form_helper.php */