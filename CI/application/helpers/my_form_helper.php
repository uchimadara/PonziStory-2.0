<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Enhancement
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Brian Basser
 * @copyright	Copyright (c) 2010
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Helpers
 *
 * @package		i3track
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Brian Basser
 * @link		http://codeigniter.com/user_guide/helpers/form_helper.html
 */

// ------------------------------------------------------------------------
function search_array($v, $k, $a) {
	//search array, a for value v in key, k
	if ($v == '0') return TRUE; //fudging for location 0 = Main Location as that's all that using this right now.
	
	foreach ($a as $item) {
			if (array_key_exists($k, $item))
				if ($item[$k] == $v) return TRUE;
	}
	return FALSE;
}
/**
 * Form Field
 *
 * @abstract Creates the entire HTML form element(s) based on custom types
 *
 * @access	public
 * @param	field	array of attributes for the form element
 * @param	data (optional) array of key,value pairs for default values
 * @param prefix (optional) string to place in front of field names/ids (used to create unique names for fields with the same name)
 *    this is useful if you have a form with, say, borrower data and then there is identical 
 *    co_borrower data on the same form. You can use the same config array to produce all the form fields for both.
 * @return	string containing HTML for the form element
 */	

if ( ! function_exists('form_field'))
{
  function form_field($field, $data = array(), $prefix='')
  {
   	$html = '';
		$label = '';
		$extra_tag = '';
		if (!empty($field['label'])) $label = $field['label'];
		if (!empty($field['extra_tag'])) $extra_tag = $field['extra_tag'];
		
		//echo $field['label'];
		
		log_message ('debug', '<<bjb>> form_field: type='.$field['type']);
		
		if ($label == 'no_label') $label = '';
		
	switch ($field['type']) {
		// handle non-data-related types at first, then the data related types in the default case
		case 'blank':
		;
		break;
		
		case 'fill':
			;
		break;
		
		case 'head':
		  $html .= '<div class="innerfield" style="margin-top:10px;">';
		  $html .= '<div style="font-weight: bold;font-size:12pt;border-bottom:1px solid #999;">'.$label.'</div>';
		  $html .= '</div>';
    	break;
    	
		case 'checklist':
			$list_html = '';
			//$list_style = 'display:none;';
			//$list_style = 'display:inline;';
				//echo 'data_listname='.$field['data_listname'].'<br />';
				//
				//echo var_dump($data[$field['data_listname']]);
				
			//if (!empty($data[$field['data_listname']])) {
				
				$list_style = 'display:inline;';
				
				$cl = get_option_array($field['select_list']);
				//$list_data = $data[$field['listname']];
			
			//echo var_dump($cl);
				foreach ($cl as $id => $list_item) {
					if ($id == '0') {
						$sel = ' checked="checked" ';
					} else {
						$sel = '';
						if (!empty($data[$field['data_listname']]) && search_array($id, 'id', $data[$field['data_listname']])) $sel = ' checked="checked" ';
					}
					
					$list_html .= '
						<div style="padding:3px 40px;float:left;width:100px;">		
						<input type="checkbox" style="margin-right:5px;" name="'.$prefix.$field['listname'].'[]" id="'.$prefix.$field['listname'].'[]" value="'.$id.'"'.$sel.' />&nbsp;'.$list_item.'
						</div>';
				}
				$list_html .= clear_both();
			//}
		 // $html .= '<div class="innerfield" style="">';
		  $html .= '<div class="innerfield" style="width:860px;font-weight: bold;">'.$label;
			//$html .= '&nbsp; <a id="check_'.$prefix.$field['listname'].'" href="javascript:void();" onclick="check_uncheck(\''.$prefix.$field['listname'].'\');">check all</a>';
			$html .= '</div>';
		  $html .= '<div id="'.$field['listname'].'-container" style="width:600px;" >';
		  $html .= $list_html;
		  $html .= '</div>';
		break;
          
		case 'item_list': // produces a list of items; input elements and a pop-up form for adding new items
											// also includes icons for edit and delete
				
			$html .= '<div class="innerfield"  style="">'; // main form element wrapper
			$html .= '<div style="color:#000000; float:left;font-weight: bold;width:175px;">'.$label.'</div>';
			$html .= '<div style="float:left;font-weight: bold;"><a href="javascript:void(0);" id="'.$field['formname'].'_button" class="ui-state-default ui-corner-all" onclick="$(\'#'.$field['formname'].'-container\').show(\'slide\',{direction:\'up\'},1000);$(\'#'.$field['formname'].'_button\').hide();">&nbsp;ADD&nbsp;</a>
				</div><div style="clear:both;"></div>';
			// produce the subform inside an effect wrapper                
			$html .= '<div id="'.$field['formname'].'-container" class="ui-widget-content ui-corner-all" style="width:80%;clear:both;display:none;float:right;margin:5px;" >'; // effect wrapper
			
			$html .= '<div id="'.$field['formname'].'-div">'; // start FORM container
			$list_item = array();
			$inputs = array();
			$list_data = array();
			if (!empty($data[$field['list_id']])) $list_data = $data[$field['list_id']];
						
			foreach($field['subform'] as $sublabel => $subfield) {
				$html .= '<div class="innerfield" style="height:2px;"></div>';
				if (empty ($subfield['label'])) $subfield['label'] = $sublabel;  
				$html .= form_field($subfield, '', $field['prefix']);
				
				 // if this is a field and there's data in the list, then add to list display
				if (array_key_exists('field_name',	 $subfield) && (!empty($list_data))) { 

					$k = $subfield['field_name'];
						
					for ($i=0; $i<count($list_data); $i++) {
						if (empty($inputs[$i])) $inputs[$i] = '';
						if (empty($list_item[$i])) $list_item[$i] = '';
						
						$field_value = $list_data[$i][$k];
						if ($subfield['type'] == 'datetime') { //make it javascript-happy
							$field_value = date('M j, Y H:i:s', strtotime($field_value)); 
						}
						
						$inputs[$i] .= '<input type="hidden" name="'.$field['list_prefix'].$k.'['.$i.']" id="'.$field['list_prefix'].$k.'_'.$i.'" value="'.$field_value.'" />';
						
									switch ($subfield['type']) {
										case 'currency':
											$field_value = '$'.number_format($field_value, 2, ".", ",");
										break;
										
										case 'date':
											$field_value = date('Y-M-d', strtotime($field_value));
										break;
										
										case 'select':
											$options = get_option_array($subfield['select_list']);
											if (!empty($options)) $field_value = $options[$field_value];
										
										default:
										break;
									}
						
						if (array_key_exists($k, $field['headings'])) {
						
							$list_item[$i] .= '<div class="col" style="width:'.$field['headings'][$k]['width'].';">'.$field_value.'</div>';
						}
					}
				}
			}
			$html .= '<div class="formbutton" style="width:100%;height:auto;">';
			$html .= '<span style="float:left;margin-left:20px;margin-bottom:10px;"><a href="javascript:void(0);" onclick="$(\'#'.$field['formname'].'-container\').hide(\'slide\',{direction:\'up\'},1000);$(\'#'.$field['formname'].'_button\').show();'.$field['formname'].'Clear();">DONE</a></span>';
			$html .= '<span style="float:right;margin-right:20px;margin-bottom:10px;"><a href="javascript:void(0);" id="'.$field['prefix'].'submit" onclick="'.$field['onsubmit'].'(\'\');">SUBMIT</a></span>';
			//$html .= '	<span class="formbutton" style="float:left;"><a href="javascript:void(0);" id="close" onclick="$(\'#'.$field['formname'].'-container\').hide(\'blind\',{},1000);">DONE</a></span>';
			//$html .= '	<span class="formbutton" style="float:right;"><a href="javascript:void(0);" style="height:30px;" id="'.$field['prefix'].'submit" onclick="'.$field['onsubmit'].'(\'\');">SUBMIT</a></span>';
			$html .= '</div>'; 
			$html .= '</div>'; // END OF FORM
			$html .= '</div><div style="clear:both;"></div>'; // END OF EFFECT WRAPPER

			$input_html = '';
			$list_html = '';
        		
			if (!empty($list_item)) { // start producing the list and the input elements
				$heading_display = "display:inline;";
				for ($i=0; $i<count($list_item); $i++) {
        
					$input_html .= '<div id="'.$field['list_prefix'].'input_'.$i.'">';
					if (array_key_exists('id', $list_data[$i])) // id is not included in the form, so check explicitly for the id field and put it in, too (should be there, array_key_exists is your friend)
						$input_html .= '<input type="hidden" name="'.$field['list_prefix'].'id['.$i.']" id="'.$field['list_prefix'].'id_'.$i.'" value="'.$list_data[$i]['id'].'" />';
			
					$input_html .= $inputs[$i];
					$input_html .= '</div>';

					//$edit_link = '<a href="javascript:void(0);" onclick="'.$field['edit_action'].'('.$i.');" title="edit" style="float:left;">'.img(array('src'=>'images/view-edit.gif', 'style'=>'vertical-align:middle;')).'</a>';
					//$del_link = '<a href="javascript:void(0);" onclick="'.$field['delete_action'].'('.$i.');" title="delete" style="float:left;">'.img(array('src'=>'images/cancel.png', 'style'=>'vertical-align:middle;')).'</a>';

					$edit_link = '<a href="javascript:void(0);" onclick="'.$field['edit_action'].'('.$i.');" title="edit" class="edit">&nbsp;</a>';
					$del_link = '<a href="javascript:void(0);" onclick="'.$field['delete_action'].'('.$i.');" title="delete" class="del">&nbsp;</a>';
								
					
								
					$list_html .= '<div id="'.$field['list_prefix'].'item'.$i.'" class="listitem">'.$edit_link.' '.$del_link.' '.$list_item[$i].'<div style="clear:both;"></div></div>';
				}
			} else {
				$heading_display = "display:none;";
			}

			//  make the heading html for the list
			
			$list_head = '<div id="'.$field['list_prefix'].'head" class="listhead" style="'.$heading_display.'">
										<div class="col" style="width:40px;">&nbsp</div>';
			
			foreach ($field['headings'] as $h) {
				$list_head .= '<div class="col" style="width:'.$h['width'].'">'.$h['label'].'</div>';
			}
			$list_head .= '<div style="clear:both;"></div></div>';
			
			$html .= $list_head;
			$html .= '<div id ="'.$field['list_id'].'" style="width:100%;line-height:20px;">'.$list_html.'</div>';
			$html .= '<div id="'.$field['list_id'].'_inputs" style="display:none;">'.$input_html.'<input type="hidden" name="'.$field['list_id'].'_count" id="'.$field['list_id'].'_count" value="'.count($list_data).'" /></div>';
			$html .= '</div><div style="clear:both;"></div>'; // END OF MAIN FORM ELEMENT WRAPPER
          
	  break;
						
	  default: // all the rest are data fields (field_name is a required index)
  	
	  // assign the field value from available resources
	
	  // should there be an "OVERRIDE SWITCH" that says what data should take precedent?
      // currently - the value in the $field array will take precedent by overriding the above code here

			$field_value = '';
			if (empty($field['value'])) {
				if (!empty($data)) {
					$field_name = $field['field_name'];
					if (is_array($data)) {
						if (!empty($data[$field_name]))
							$field_value = $data[$field_name];
					} else { 
						if (!empty($data->$field_name))
							$field_value = $data->$field_name;
					}
				} 
			} else { 
				$field_value = $field['value'];
			}
        
			//echo 'field='.$field['field_name'].' value='.$field_value.'<br />';
			
			// set up HTML that indicates a "required" field
			$req = '';
			if (array_key_exists('required', $field)) {
				if ($field['required'] == 'yes') {
					$req .= "<font style='color: red;'>*</font>";
				}
			}
        
			// set up opening <div> and include label
			// 'image' and 'textarea' types are special cases
			if (array_key_exists('bold_label', $field)) $label = '<b>'.$label.'</b>';
			
			if ($field['type'] == 'html' && USE_FCKEDITOR) {
				$html .= '<div class="innerfield" > 
										<div style="color:#000000;font-weight:bold;">'.$label.'</div>';
			} else {
				if ($field['type'] != 'hidden' && $field['type'] != 'image' && $field['type'] != 'affiliate_image') {
					$width = (isset($field['label_width'])) ? $field['label_width'] : '150';
					$html .= '<div class="innerfield"> 
											<div style="color:#000000; float:left;width:'.$width.'px;">'.$label.' '.$req.'</div>';
				}
			}
			//$html .= $req;
			
			// output the HTML based on the field type
    	switch ($field['type']) {
          
				case 'hidden':
					$html .=  '<div><input type="hidden" name="'.$field['label'].'" id="'.$field['label'].'" value="'.$field_value.'" />';
				break;
				
				case 'select':
				
					if (array_key_exists('display_only', $field)) {
					
						$html .=  get_select_value($field['select_list'], $field_value);
						
					} else {
						//$html .=  '<select class="selectbox fixed_width" style="width:650px;white-space:normal;" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" ';
						$html .=  '<select style="white-space:normal;" id="'.$prefix.$field['field_name'].'" ';
						
						if (!empty($field['onclick'])) {
							$html .= ' onchange="'.$field['onclick'].';"';
						}
						if (!empty($field['size'])) {
							$html .= ' name="'.$prefix.$field['field_name'].'[]" multiple="multiple" size="'.$field['size'].';"';
						} else {
						  $html .= ' name="'.$prefix.$field['field_name'].'"';
						}
						$html .= '>';
						$html .=  get_select_options($field['select_list'], $field_value, !array_key_exists('blank_first', $field));
						$html .=  '</select>';
					}
					
				break;
		

				case 'popup_select':
					$html .=  '<input type="hidden" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" value="'.htmlentities($field_value).'" />';
					$html .= '<div id="'.$prefix.$field['field_name'].'_dialog" style="display:none" title="'.$field['button_label'].'">';
					$html .= get_popup_options($field['select_list'], $prefix.$field['field_name'], $field_value);
					$html .= '</div>';

					$html .=  '<span class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="padding:5px;" onclick="popUp(\''.$prefix.$field['field_name'].'_dialog\');">';
					$html .=  $field['button_label'];
					$html .=  '</span><br />&nbsp;&nbsp;<span id="'.$prefix.$field['field_name'].'_display">';
					if (!empty($field_value)) {
						$html .= $field_value;
					}
					$html .= '</span>';
				break;

				case 'selectable':
					if (array_key_exists('display_only', $field)) {
					
						$options = get_option_array($field['select_list']);
						$html .=  $options[$field_value];
						
						
					} else {
						$html .= '<div><style>
												#'.$prefix.$field['field_name'].' .ui-selecting { background: #FECA40; }
												#'.$prefix.$field['field_name'].' .ui-selected { background: #F39814; color: white; }
												#'.$prefix.$field['field_name'].' { list-style-type: none; float:left;}
												#'.$prefix.$field['field_name'].' li { margin: 3px; padding: 0.4em; font-size: 1.1em; height: 14px; }
											</style>';
						$html .=  '<ol name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" ';
						if (!empty($field['onclick'])) {
							$html .= ' onchange="'.$field['onclick'].';"';
						}
						$html .= '>';
						$options = get_option_array($field['select_list']);
						foreach ($options as $o)
							$html .=  '<li class="ui-widget-content">'.$o.'</li>';
						$html .=  '</ol>';
						
						$html .= '<script>
												$(function() {
												$( "#'.$prefix.$field['field_name'].'" ).selectable();
											});
											</script></div><div style="clear:both"></div>';

					}
				break;
				case 'radio':
					$html .=  get_radio_options($field['field_name'], $field['select_list'], $field_value);
				break;
            
				case 'checkbox':
					$checked = ($field_value == 1) ? ' checked="checked" ' : ' ';
					//$html .= '<div class="innerfield" style="">';
					//$html .= '<div style="color:#000000; float:left;width:90%">'.$label.' '.$req.'</div>';
					//$html .= '<div style="color:#000000; text-align:center;float:left;width:10%;"><input type="checkbox" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" value="1" '.$checked.$extra_tag.'/></div>';
					$html .= '<input style="float:left;width:15px;" type="checkbox" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" value="1" '.$checked.$extra_tag.'/>';
					//$html .= '<div style="clear:both;"></div>';
					break;
				
				case 'combo':
					if (array_key_exists('display_only', $field)) {
						$html .=  $field_value;
					} else {
						$html .=  '<select class="combo" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" ';
						if (!empty($field['onclick'])) {
							$html .= ' onchange="'.$field['onclick'].';"';
						}
						$html .= '>';
						$html .=  get_select_options($field['select_list'], $field_value);
						$html .=  '</select>';
					}
				break;
				
				case 'text':
					if (array_key_exists('display_only', $field)) {
						$html .=  $field_value;
					} else {
						$html .=  '<input type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="'.$field['size'].'" maxlength="'.$field['maxlength'].'" value="'.htmlentities($field_value).'" '.$extra_tag.'/>';
					}
				break;
            
				case 'password':
						$html .=  '<input type="password" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="'.$field['size'].'" maxlength="'.$field['maxlength'].'" value="'.htmlentities($field_value).'" '.$extra_tag.'/>';
				break;
            
				case 'html':
                    $html .= '<textarea name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" rows="'.$field['rows'].'" cols="'.$field['cols'].'">'.$field_value.'</textarea>';

                    if (USE_FCKEDITOR) {
						$html .= '<script>$("#'.$prefix.$field['field_name'].'").sceditor({';
                        $html .= 'style: mim.assetPath + "scripts/sceditor/themes/default.min.css",';
                        $html .= 'width: "100%",';
                        $html .= 'toolbar: "bold,italic,underline|font,size,color,removeformat|image,link,unlink|emoticon|source"';
                        $html .= '});</script>';

                    }
                break;

				case 'textarea':
					$html .=  '<textarea name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" rows="'.$field['rows'].'" cols="'.$field['cols'].'">'.$field_value.'</textarea>';  
            
				break;
          
				case 'file_editor':
					//if (USE_FCKEDITOR) {
					//assume it's an affiliate for now...
					$fc = '';
					if (isset($data[$field['path_key']])) { 
						$sub_path =str_replace('inclusion', $data[$field['path_key']], PUBPATH.'/');
						$file = fopen($sub_path.$field_value, "r") or exit("Unable to open file: ".$sub_path.$field_value);
						
						$fc = '';
						while(!feof($file))  {
							$fc .= fgets($file);
						}
						//echo $html;
						fclose($file);
					}
					
					$html .= '<div style="padding-top: 8px;">';
					$html .= form_fckeditor($prefix.$field['field_name'], $fc);   
					$html .= '</div>';
					
					//} else {
					//	$html .=  '<textarea name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" rows="'.$field['rows'].'" cols="'.$field['cols'].'">'.$field_value.'</textarea>';  
					//}
            
				break;
          
				case 'date':
					if ($field_value == '0000-00-00') $field_value = '';
					$html .= '<div>';
					$html .=  '<input class="dp" type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="10" maxlength="10" value="'.$field_value.'">';
					$html .= '</div>';
					
				break;
					
				case 'date_range':
					$html .= '<div>';
					$html .=  '<span style="float:left;width:auto;"><input class="dp" type="text" name="date_start" id="date_start" size="10" maxlength="10" value="'.set_value('date_start').'">';
					$html .= ' </span><span style="float:left;width:auto;">&nbsp;to&nbsp;</span> ';
					$html .=  '<span style="float:left;width:auto;"><input class="dp" type="text" name="date_end" id="date_end" size="10" maxlength="10" value="'.set_value('date_end').'">';
					$html .= '</span></div>';
					
				break;
					
				case 'time':
					 if ($field_value == '00:00:00') $field_value = '';
					 $html .= '<div class="datetime-container">';
					 $html .= '<div><input type="text" id="'.$prefix.$field['field_name'].'" name="'.$prefix.$field['field_name'].'" value="'.$field_value.'" /></div>';
					 $html .= "
							<pre>
								 $('#".$prefix.$field['field_name']."').timepicker({ampm: true});
							</pre></div>";
				break;
				
				case 'datetime':
					if ($field_value == '0000-00-00 00:00:00') $field_value = '';
					if ($field_value != '') $field_value = date('M j, Y H:i:s', strtotime($field_value)); // prep for javascript 
					
					 $html .= '<div class="datetime-container">';
					 $html .= '<div><input type="text" id="'.$prefix.$field['field_name'].'" name="'.$prefix.$field['field_name'].'" value="'.$field_value.'" '.$extra_tag.'/></div>';
					 $html .= "
							<pre>
								 $('#".$prefix.$field['field_name']."').datetimepicker({ampm: false, timeFormat: 'hh:mm:ss', dateFormat:'M d, yy', hourGrid: 4, minuteGrid: 10});
							</pre></div>";
				break;
				
				case 'phone':
					$html .=  '<input type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="20" maxlength="20" value="'.$field_value.'" onkeydown="javascript:backspacerDOWN(this,event);" onkeyup="javascript:backspacerUP(this,event);">';       
				break;
				
				case 'currency':
					if (!empty($field_value)) $field_value = number_format($field_value,2,".",",");
					$html .=  '$<input type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="15" maxlength="15" value="'.$field_value.'" onkeyup="javascript:maskAmount(this); "/>';
				break;
				
				case 'float':
					//if (!empty($field_value)) $field_value = number_format($field_value,,".","");
					$html .=  '<input type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="'.$field['size'].'" maxlength="'.$field['maxlength'].'" value="'.$field_value.'" onkeyup="javascript:maskAmount(this);" '.$extra_tag.'/>';
				break;
				
				case 'int':
					$html .=  '<input type="text" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" size="'.$field['size'].'" maxlength="'.$field['maxlength'].'" value="'.$field_value.'" onkeyup="javascript:maskInteger(this);"/>';
				break;
				
				case 'calculated':
					$call = $field['function'];
					$html .=  '<span name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'">';
					$html .=  $call();
					$html .=  '</span>';
				break;
				
				case 'uploadify':
					if (USE_UPLOADIFY) {// only one per form at this point...
						//$html .= 'HERE I AM';
						$html .= '<div class="photo_button" style="text-align:left;">';
						//$html .= '<input name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'" type="hidden" />';
						$html .= '<input name="fileInput1" id="fileInput1" type="hidden" />';
						$html .= '</div>';
					} else {
						$html .= '<input type="file" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'">';
						$html .= ($field_value != '') ? '<br /><a href="'.base_url().FILE_LINK_PATH.$data['id'].'/'.$field_value.'" style="font-size:9pt;color:#3333ff;text-decoration:none;">Download File</a>' : '';
					}
					break;
          
				case 'file':
					//if (array_key_exists('format', $field) && $field['format'] == 'image') {
					//	$html .= ($field_value != '') ? '<img src="'.base_url().'images/'.$field_value.'" style="margin-left:200px;margin-top:8px;"/>' : '';
					//} else {
					//	$html .= ($field_value != '') ? '<a href="'.$href.$field_value.'" style="margin-left:200px;font-size:9pt;color:#3333ff;text-decoration:none;">Download File</a>' : '';
					//}

					$html .= '<input type="file" name="'.$prefix.$field['field_name'].'" id="'.$prefix.$field['field_name'].'">';
					//$href = (array_key_exists('url',$field)) ? $field['url'] : base_url().FILE_LINK_PATH;
					//$html .= ($field_value != '') ? '<br /><a href="'.$href.$field_value.'" style="font-size:9pt;color:#3333ff;text-decoration:none;">Download File</a>' : '';
				break;
					
				case 'image':
            clearstatcache();
          	$html .= '<div class="innerfield" style="height: auto"> ';
            if ($field_value == '') {
              $html .= '<div style="float:left;font-weight: bold;width:175px;">'.$field['label'].'</div><div style="float:left;font-weight: bold;">No image assigned.</div>';
            } else {
          	  $html .= '<div style="float:left;font-weight: bold;width:175px;">'.$field['label'].'</div>';
              $html .= '<div style="float:left;text-align:center; margin-right: 20px;line-height: 22px;margin-top: 6px;">';
          	  //$photo_file = $field['realpath'].$field_value;
          	  /*
							if (strstr($_SERVER['DOCUMENT_ROOT'], 'zoograb'))
                $photo_file = $_SERVER['DOCUMENT_ROOT'].'/'.$field['relpath'].$field_value;
              else
                $photo_file = $_SERVER['DOCUMENT_ROOT'].'/zoograb/'.$field['relpath'].$field_value;
							*/
							$photo_file = $field['realpath'].trim($field_value);
              if(file_exists($photo_file)){
                list($width, $height) = getimagesize($photo_file);
                $image_properties = array(
                'width' => $width,
                'height' => $height,
                'src' => $field['relpath'].$field_value,
                );
								$html .= img($image_properties);
								
                //$html .= '<a href="javascript:void(0);" onclick="popopImage(\''.$photo_file.'\');">'.img($image_properties).'</a>';
              } else {
                  $html .= "Image file not found. [".$photo_file.']';
              }
              $html .= '<div style="clear:both;"></div></div>';
          	} 
					//$html .= '</div>';
				break;
			}
			if (array_key_exists('tip', $field)) {
				$html .= '<div class="tip" onmouseover="Tip(\''.str_replace("'", "\\'", $field['tip']).'\')" onmouseout="UnTip()"></div>';
			}
			$html .= '</div>';
			
			//$html .= '<div style="clear:both;"></div>';
			break;
		}
		return $html;
	}
}

function date_value($d) {
	if ($d == '0000-00-00' || $d == "0000-00-00" || $d == "00:00:00" || $d == "0000-00-00 00:00:00")
		return "";
	else
		return $d;
}
/*
 * Function to output a date selector
 */
 
function output_date_selector ($id, $a='') {
  if ($a == '') $a = 'anchor_'.$id;
  return '<a href="#" onclick="cal1x.select(document.getElementById(\''.$id.'\'),\''.$a.'\',\'yyyy-MM-dd\'); return false;" title="click to select date" name="anchor_'.$id.'" id="anchor_'.$id.'"><img src="'.base_url().'images/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>';
}

function output_show_hide ($id, $show=TRUE) {
	$l = ($show) ? "show" : "hide";
  return '<a class="showhide" id="'.$id.'-a" href="javascript:void(0);" onclick="show_hide(\''.$id.'\');">'.$l.'</a>';
}

function get_picklist($list) {
  $option_list = array();
  
  $CI =& get_instance();
  $option_array = $CI->picklist->select_values($list);
	
	foreach ($option_array as $opt) {
		$option_list[] = $opt;
	}
	
	return $option_list;
}

/*
 * Function to output select options from select list
 */
function get_select_options($list,$select='',$blank_first=TRUE){

  $option_list = '';
  
  if (is_array($list)) {
    
    $option_array = $list;
    
  } else {
    
    $CI =& get_instance();
    $option_array = $CI->picklist->select_values($list);
    
  }
  if ($blank_first) $option_list .= '<option value=""></option>';

  foreach($option_array as $key => $value){
    $selected = '';
    if((is_array($select) && in_array($key,$select)) || $key == $select)
      $selected = ' selected="selected"';
    $option_list .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
    
  }
  return $option_list;
}

/*
 * Function to get a single value from an option list
 */
function get_select_value($list,$select){
  
  $CI =& get_instance();
  return $CI->picklist->select_value($list, $select);
}

/*
 * Function to get a single value from an option list
 */
function select_value_count($list){
  
  $CI =& get_instance();
  return $CI->picklist->select_value_count($list);
}
/*
 * Function to output a red asterisk
 */
function mark_required($f){

	$html = '';
 if (array_key_exists('required',$f) && $f['required'] == 'yes') {
	$html = '<span style="color:red;">*</span>';
 }
 return $html;
}

///*
// * Function to output select options in a popup container
// */
//function get_popup_options($list,$field_name, &$select=''){
//  $option_list = '<div class="optionlist">';
//  
//  $CI =& get_instance();
//  $option_array = $CI->picklist->select_values($list);
//	
//	$cur_group = '';
//  foreach($option_array as $key => $value){
//	
//		if ($opt['group'] != $cur_group) {
//			$option_list .= '<div class="grouphead">'.$opt['group'].'</div>';
//			$cur_group = $opt['group'];
//		}
//		
//    $selected = '';
//    if((is_array($select) && in_array($opt['key'],$select)) || $opt['key'] == $select) {
//		
//      $selected = 'selected';
//			$select = $opt['value'];
//		}
//    $option_list .= '<div class="optionlist-item" id='.$list.$opt['key'].'" onclick="select_option(\''.$field_name.'\',\''.$opt['key'].'\',\''.str_replace("'","\\'",$opt['value']).'\');" onmouseover="this.style.backgroundColor = \'#EAEAAE\';" onmouseout="this.style.backgroundColor = \'#ffffff\';">'.$opt['value'].'</div>';
//    
//  }
//  return $option_list.'</div>';
//}
function permission($target, $action){
  $CI =& get_instance();
  return $CI->permissions->get('form', $action, $target);
}


function get_option_array($list, $id= ''){
  $option_list = '';
  
  $CI =& get_instance();
  return $CI->picklist->select_values($list);
}

/*
 * Function to output radio buttons from select list
 */
function get_radio_options($name, $list ,$select=''){
  $CI =& get_instance();
  $option_array = $CI->picklist->select_values($list);

  if ($list == 'yesno_list') {
		$option_list = '<div style="">';
		foreach($option_array as $key => $value){
			$selected = '';
			if((is_array($select) && in_array($key,$select)) || $key == $select)
			$selected = 'checked="checked"';
			$option_list .= '	<span style="margin-left:20px; width:15px;float:left;margin-top:5px;"><input type="radio" name="'.$name.'" id="'.$name.'[]" value="'.$key.'" '.$selected.' /></span>
							<span style="float:left;position:relative;top:-2px;margin:4px;">'.$value.'</span>';
		}
		$option_list.='</div>';
  } else {
	
		$option_list = ' <div><br />';
		foreach($option_array as $key => $value){
			$selected = '';
			if((is_array($select) && in_array($key,$select)) || $key == $select)
			$selected = 'checked="checked"';
			$option_list .= '<div style="clear:both;">
							<div style="width:15px;float:left;margin-top:2px;"><input type="radio" name="'.$name.'" id="'.$name.'[]" value="'.$key.'" '.$selected.' /></div>
							<div style="float:left;width:auto;margin:2px; 4px 4px 4px;">'.$value.'</div>
						</div>';
			
		}
		$option_list.='</div>';
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
			if (array_key_exists('field_name', $f) and !empty($_POST[$list['list_prefix'].$f['field_name']])) {
			//echo 'processing... '.$this->_children[$this->data['table']][$list]['list_prefix'].$f['field_name'].'<br />';
			$i=0;    foreach ($_POST[$list['list_prefix'].$f['field_name']] as $v) $r[$i++][$f['field_name']] = $v;  
			}
		}
	//if (!empty($r)) { // add in the id field, because it is not part of the form def.
	  //$i=0;    foreach ($this->input->post($this->_children[$this->data['table']][$list]['list_prefix'].'id') as $v) $r[$i++]['id'] = $v;  
	  return $r;
	//}
  }



function to_html($string, $encode=TRUE){
	
	$toHTML = array(
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'& ' => '&amp; ',
		"'" => '&#039;',
	);
	
	if($encode && is_string($string)){//$string = htmlentities($string, ENT_QUOTES);
		if(is_array($toHTML)) { // cn: causing errors in i18n test suite ($toHTML is non-array)
			$string = str_replace(array_keys($toHTML), array_values($toHTML), $string);
		}
	}
	return $string;
}

function from_html($string, $encode=TRUE){
	$toHTML = array(
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'& ' => '&amp; ',
		"'" => '&#039;',
	);
//if($encode && is_string($string))$string = html_entity_decode($string, ENT_QUOTES);
	if($encode && is_string($string)){
		$string = str_replace(array_values($toHTML), array_keys($toHTML), $string);
	}
	return $string;
}

function clear_both($h ='') {
	if ($h != '') $h = "height:{$h}px;";
	return '<div style="clear:both;'.$h.'"></div>';
}

/* End of file my_form_helper.php */
/* Location: ./application/helpers/my_form_helper.php */