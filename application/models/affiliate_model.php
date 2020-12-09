<?php

class Affiliate_model extends CI_Model
{
    public function get_affiliates($vars = null)
    {
        $arr_local_overrides = array('otto', 'ps');

        if (! empty($vars['affiliate_subdomain']) && in_array(strtolower($vars['affiliate_subdomain']), $arr_local_overrides)) {
            unset($vars['affiliate_subdomain']);
            
            $vars['id_affiliate'] = 1;
        }

        $sql = "
			SELECT *, c.id_affiliate, c.state
			AS `affiliate_state`
			FROM `ltp_affiliates` AS c
			WHERE c.`id_affiliate` > '0'
		";

        if (! empty($vars['id_affiliate'])) {
            $sql .= "AND c.id_affiliate =  " . $this->db->escape($vars['id_affiliate']);
        } elseif (! empty($_SESSION['affiliate']['id_affiliate'])) {
            //$sql .= " AND c.id_affiliate =  ".$this->db->escape($_SESSION['affiliate']['id_affiliate']);
        }

        if (! empty($vars['affiliate_subdomain'])) {
            $sql .= "AND LOWER(c.affiliate_subdomain) = " . strtolower($this->db->escape($vars['affiliate_subdomain']));
        }

        if (! empty($vars['affiliate_address_states'])) {
            if (! is_array($vars['affiliate_address_states'])) {
                $affiliate_address_states = explode(',', $vars['affiliate_address_states']);
            } else {
                $affiliate_address_states = $vars['affiliate_address_states'];
            }

            $vars['affiliate_address_states'] = array();

            foreach ($affiliate_address_states as $cur_state) {
                $vars['affiliate_address_states'][]	= "'" . $cur_state . "'";
            }

            $vars['affiliate_address_states'] = implode(',', $vars['affiliate_address_states']);

            $sql .= " AND LOWER(c.affiliate_address_state) IN (" . strtolower($vars['affiliate_address_states']) . ")";
        } elseif (! empty($vars['affiliate_address_state'])) {
            $sql .= " AND LOWER(c.affiliate_address_state) = " . strtolower(
                $this->db->escape($vars['affiliate_address_state'])
            );
        }


        if (! empty($vars['affiliate_address_countries'])) {
            if (! is_array($vars['affiliate_address_countries'])) {
                $affiliate_address_countries = explode(',', $vars['affiliate_address_countries']);
            } else {
                $affiliate_address_countries = $vars['affiliate_address_countries'];
            }

            $vars['affiliate_address_countries'] = array();

            foreach ($affiliate_address_countries as $cur_country) {
                $vars['affiliate_address_countries'][]	= "'" . $cur_country . "'";
            }

            $vars['affiliate_address_countries'] = implode(',', $vars['affiliate_address_countries']);

            $sql .= " AND LOWER(c.affiliate_address_country) IN (" . strtolower($vars['affiliate_address_countries']) . ")";
        } elseif (! empty($vars['affiliate_address_country'])) {
            $sql .= " AND LOWER(c.affiliate_address_country) = " . strtolower($this->db->escape($vars['affiliate_address_country']));
        }


        if (isset($vars['state'])) {
            $sql .= " AND c.state = " . $this->db->escape($vars['state']);
        } else {
            $sql .= " AND c.state > 0";
        }

        if (! empty($vars['last_update'])) {
            $sql .= " AND c.date_mod >=  " . $this->db->escape($vars['last_update']);
        }
        $sql .= " GROUP BY c.id_affiliate";

        if (isset($vars['order'])) {
            $sql .= " ORDER BY " . $this->db->escape($vars['order']);
        } else {
            $sql .= " ORDER BY c.affiliate_name";
        }

        if (isset($vars['order_dir'])) {
            $sql .= " " . $this->db->escape($vars['order_dir']);
        } else {
            $sql .= " ASC";
        }

        if (isset($vars['limit'])) {
            $sql .= " LIMIT " . $vars['limit'];
        } else {
            $sql .= " LIMIT 0,2000";
        }

        if (! empty($vars['debug']) && $vars['debug'] == 'stop') {
            echo '<p><strong>get_affiliates Query: </strong>' . $sql . '</p>';
            exit();
        } elseif (! empty($vars['debug'])) {
            echo '<p><strong>get_affiliates Query: </strong>' . $sql . '</p>';
        }

        $query = $this->db->query($sql, false);

        if ($query -> num_rows() > 0) {
            $r = $query->result_array();

            foreach ($r as $key => $val) {
                $r[$key]['id_affiliate_encoded'] = url_enc($r[$key]['id_affiliate']);
                $r[$key]['params'] = $this->get_affiliates_params(array('id_affiliate' => $r[$key]['id_affiliate']));

                //Set param status
                if (! empty($r[$key]['params'])) {
                    foreach ($r[$key]['params'] as $cur_check) {
                        if (stripos($cur_check['param_type'], 'compliance') !== false) {
                            $r[$key]['params']['has_custom_compliance']	= 1;
                        }
                    }
                }
            }

            return $r;
        } elseif (! empty($vars['return_schema'])) {
            $schema = $this->db->list_fields('ltp_affiliates');
            
            foreach ($schema as $key => $val) {
                $r[$key]	= null;
            }
            return $r;
        } else {
            return null;
        }
    }

    public function get_affiliate($vars = null)
    {
        if (! empty($vars) && !is_array($vars) && is_numeric($vars)) {
            $new = $vars;
            unset($vars);
            $vars['id_affiliate']	= $new;
            unset($new);
        }

        $result = $this->get_affiliates($vars);

        if (! empty($result) && count($result) > 0) {
            $r = $result[0];
            return $r;
        } elseif (! empty($vars['return_schema'])) {
            $schema 		= $this->db->list_fields('ltp_affiliates');
            foreach ($schema as $key => $val) {
                $r[$key]	= null;
            }
            return $r;
        } else {
            return null;
        }
    }

    public function save_affiliate($data = null)
    {
        $post_check = $this->input->post('posted');
        $db_update = false;
        $password = null;
        $reset_password = false;
        $debug	= null;
        $user = array();


        if (! empty($_SESSION['logged_in'])) {
            $user = $_SESSION['logged_in'];
        }

        if (empty($user['id_people'])) {
            $user['id_people'] = 1;
        }


        if (isset($post_check) && empty($data)) {
            $data = $this->input->post();
        }

        // Backup Vars
        $vars = $data;

        // Salesforce ID Check
        if (empty($data['id_affiliate']) && ! empty($data['id_salesforce'])) {
            $data['id_affiliate'] = $this->get_affiliate_id_by_salesforce_id($data['id_salesforce']);
            if (! empty($data['id_affiliate'])) {
                $debug .= 'Found Affiliate ID: ' . $data['id_affiliate'] . ' By Matching Salesforce ID:' . $data['id_salesforce'];
            }
        }

        if (isset($data['affiliate_state'])) {
            $data['state'] 	= $data['affiliate_state'];
        } elseif (! isset($data['state'])) {
            $data['state'] = 1;
        }


        $checkboxes	= array(
            'allow_interest_care_portal',
            'restrict_cross_church_volunteers',
            'enable_regions',
        );

        foreach ($checkboxes as $chk_key => $chk_name) {
            $prev_key = $chk_name . '_prev';

            if (isset($data[$chk_name])) {
                if (! empty($data[$chk_name])) {
                    $data[$chk_name] = 1;
                } else {
                    $data[$chk_name] = 0;
                }
            } elseif (! empty($vars[$prev_key])) {
                $data[$chk_name] = 0;
            }
            //echo '<br />'.$prev_key.' '.$chk_name;
        }

        if (isset($data)) {
            $schema = $this->db->list_fields('ltp_affiliates');

            foreach ($data as $key => $val) {
                if (! in_array($key, $schema)) {
                    unset($data[$key]);
                }
            }
        }

        $data['id_mod']	= $user['id_people'];
        $data['date_mod'] = time();

        // Check to update table first
        if ($data['id_affiliate'] > 0) {
            $cur_affiliate	= $this->get_affiliates(array('id_affiliate' => $data['id_affiliate']));

            $this->db->where('id_affiliate', $data['id_affiliate']);
            if ($this->db->update('ltp_affiliates', $data)) {
                $result['db_update'] = true;
                $result['status'] = true;
                $result['id_affiliate']	= $data['id_affiliate'];
                $result['result'] = 'success';
                $result['method'] = 'update';
                $result['data']	= $data;
            }
        } else {
            $data['id_add']	= $user['id_people'];
            $data['date_add'] = time();


            if ($this->db->insert('ltp_affiliates', $data)) {
                $result['db_update'] = true;
                $result['status'] = true;
                $result['id_affiliate']	= $this->db->insert_id();
                $result['result'] = 'success';
                $result['method'] = 'insert';
                $result['data']	= $data;
            }
        }

        $result['data'] = $data;
        $result['debug'] = $debug;

        // Update Affiliate Names in session variables
        $this->get_affiliate_name_by_id(
            array(
                'id_affiliate' => $result['id_affiliate'],
                'force_update' => 1,
            )
        );

        return $result;
    }

    public function get_affiliate_name($vars)
    {
        $vars = format_array_vals($vars, 'id_affiliate');

        $bypass_affiliate_state		= !empty($vars['bypass_affiliate_state']) ? $vars['bypass_affiliate_state'] : null;

        if (! empty($vars['id_affiliate']) && empty($vars['affiliate_name'])) {
            $vars = $this->get_affiliate(array('id_affiliate' => $vars['id_affiliate']));
        }

        $cur_name = '';
        if (! empty($vars['affiliate_address_state']) && empty($bypass_affiliate_state)) {
            $cur_name 	= $vars['affiliate_name'].' - '.$vars['affiliate_address_state'];
        } elseif (! empty($vars['afffiliate_name'])) {
            $cur_name	= $vars['affiliate_name'];
        }

        if (empty($cur_name) && !empty($vars['id_affiliate'])) {
            $cur_name	= $this->get_affiliate_name_by_id($vars['id_affiliate']);
        }

        if (empty($cur_name) && !empty($vars['assign_id_affiliate'])) {
            $cur_name	= $this->get_affiliate_name_by_id($vars['assign_id_affiliate']);
        }

        return $cur_name;
    }

    public function get_affiliate_short_name($vars)
    {
        $vars = format_array_vals($vars, 'id_affiliate');

        if (! empty($vars['id_affiliate']) && empty($vars['affiliate_name'])) {
            $vars = $this->get_affiliate(array('id_affiliate' => $vars['id_affiliate']));
        }

        if (! empty($vars['affiliate_name_short'])) {
            return $vars['affiliate_name_short'];
        }

        if (! empty($vars['affiliate_subdomain']) && empty($vars['bypass_subdomain'])) {
            return $vars['affiliate_subdomain'];
        }

        if (! empty($vars['afffiliate_name'])) {
            return substr($vars['affiliate_name'], 0, 10);
        }

        return null;
    }

    public function get_affiliate_data($vars)
    {
        if (! empty($vars) && !is_array($vars) && is_numeric($vars)) {
            $new = $vars;

            unset($vars);

            $vars['id_affiliate']	= $new;

            unset($new);
        }

        if (empty($vars['bypass_session']) && ! empty($_SESSION['affiliate'][$vars['id_affiliate']])) {
            return $_SESSION['affiliate'][$vars['id_affiliate']];
        }

        return $this->get_affiliate($vars);
    }

    public function get_avatar_filename($vars)
    {
        $cur_avatar	= null;
        $cur = $vars;

        if (empty($cur['id_affiliate'])) {
            $cur['id_affiliate']	= 0;
        }

        if (! empty($cur['url_avatar'])) {
            $cur_avatar = $cur['url_avatar'];
        }

        return correct_img_path($cur_avatar);
    }

    public function get_affiliate_status_from_state($list)
    {
        $status = '';

        if (isset($list['affiliate_state'])) {
            $list['state'] = $list['affiliate_state'];
        }

        switch ($list['state']) {
            case '-2':
                $status				= 'Deleted';
            break;
            case '1':
                $status				= 'Not Set';
            break;
            case '10':
                $status				= 'Prospect';
            break;
            case '22':
                $status				= 'On Hold';
            break;
            case '24':
                $status				= 'Closed';
            break;
            case '40':
                $status				= 'Active';
            break;
        }

        return $status;
    }

    public function get_affiliate_assignmen($vars)
    {
        if (! empty($vars['assignment_type'])) {
            switch (strtolower($vars['assignment_type'])) {
                case 'people_to_church':
                    if (! empty($vars['id_church'])) {
                        $sql = "SELECT * FROM `ltp_assignments` WHERE `state` > 0 AND `assignment_type` = 'church_to_affiliate' AND `id_church` = ".$this->db->escape($vars['id_church']);
                    }
                break;
            }
        }
    }

    public function get_affiliate_id_by_salesforce_id($vars)
    {
        if (! is_array($vars)) {
            $id_salesforce = $vars;

            unset($vars);

            $vars['id_salesforce'] = $id_salesforce;
        }

        if (empty($vars['id_salesforce'])) {
            return null;
        }

        $sql = "
			SELECT `id_affiliate` 
			FROM `ltp_affiliates` AS p 
			WHERE  (p.id_salesforce = " . $this->db->escape($vars['id_salesforce']) . " 
			OR SUBSTRING(p.id_salesforce,1,15) = " . $this->db->escape($vars['id_salesforce']) . ")";
        $query = $this->db->query($sql, false);

        if ($query -> num_rows() > 0) {
            $r = $query->result_array();

            return $r[0]['id_affiliate'];
        }

        return null;
    }

    public function set_affiliate_data_session($vars = null)
    {
        if (empty($_SESSION['affiliate']['id_affiliate'])) {

        //Clear out affiliate session & reset
            unset($_SESSION['affiliate']);
            $_SESSION['affiliate']				= array();

            //Override or set ID Affiliate if it's not provided
            if (! empty($vars['force_id_affiliate'])) {
                $vars['id_affiliate']													= $vars['force_id_affiliate'];
            }
            if (empty($vars['id_affiliate'])) {
                $vars['id_affiliate']													= $this->get_active_affiliate_id_by_subdomain();
            }

            $affiliate																	= $this->get_affiliate($vars);
            $_SESSION['affiliate']														= $affiliate;

            if (! empty($affiliate['affiliate_style_settings'])) {
                $_SESSION['affiliate']['styles']										= json_decode($affiliate['affiliate_style_settings'], true);
            }
            if (empty($_SESSION['affiliate']['styles']['meta_title'])) {
                $_SESSION['affiliate']['styles']['meta_title']							= 'Promise Serves';
            }
            if (empty($_SESSION['affiliate']['styles']['meta_desc'])) {
                $_SESSION['affiliate']['styles']['meta_desc']							= 'Volunteer coordination system for Live The Promise / Promise 686';
            }
            if (empty($_SESSION['affiliate']['styles']['color_set_navbar'])) {
                $_SESSION['affiliate']['styles']['color_set_navbar']					= '';
            }

            if (empty($vars['protect_limiters']) && !empty($_SESSION['view_limiter'])) {
                unset($_SESSION['view_limiter']);
                $this->website_model->set_limiter_sessions();
            }
        }

        if (! empty($vars['return_vals'])) {
            return $_SESSION['affiliate'];
        }

        return true;
    }

    public function get_active_affiliate_id($vars = null)
    {
        $affiliate = $this->get_active_affiliate_data($vars);

        if (! empty($affiliate['id_affiliate'])) {
            return $affiliate['id_affiliate'];
        } else {
            return 1;
        }
    }

    public function get_active_affiliate_id_by_subdomain()
    {
        $subdomain 	= get_subdomain();

        if (! empty($subdomain)) {
            $aff = $this->get_affiliate(array('affiliate_subdomain' => strtolower($subdomain)));
            if (! empty($aff['id_affiliate'])) {
                return $aff['id_affiliate'];
            }
        }

        return 1;
    }

    public function get_active_affiliate_data($vars = null)
    {
        if (is_localhost()) {
            if (! empty($_SESSION['affiliate']['id_affiliate']) && empty($vars['force_update']) && !empty($_SESSION['localhost']['id_affiliate'])) {
                return $this->get_affiliate(array('id_affiliate' => $_SESSION['localhost']['id_affiliate']));
            } elseif (! empty($_SESSION['affiliate']['id_affiliate'])) {
                return $_SESSION['affiliate'];
            }

            return $this->get_affiliate(array('id_affiliate' => 1, 'debug' => 0));
        }

        $subdomain 	= get_subdomain();

        if (! empty($_SESSION['affiliate']['id_affiliate']) && empty($vars['force_update'])) {
            if (! empty($subdomain) && hash_challenge($subdomain) == hash_challenge($_SESSION['affiliate']['affiliate_subdomain'])) {
                return $_SESSION['affiliate'];
            }
        }


        if (! empty($subdomain)) {
            $affiliate				= $this->get_affiliate(array('affiliate_subdomain' => strtolower($subdomain)));
        }


        if (! empty($affiliate['id_affiliate'])) {
            return $affiliate;
        }

        return $this->get_affiliate(array('id_affiliate' => 1));
    }


    public function replace_affiliate_css_colors($file)
    {
        $css = file_get_contents($file);
        // $css = str_replace('#95ba3d', '#006faf', $css);

        //print_array($_SESSION['affiliate']['styles']);


        if (! empty($_SESSION['affiliate']['styles'])) {
            foreach ($_SESSION['affiliate']['styles'] as $key => $val) {
                $key_contrast			= $key.'_contrast';
                $key_offset				= $key.'_offset';
                $get_key				= get_req($key);
                if (strpos($key, 'color') !== false) {
                    if (! empty($get_key)) {
                        $val = $get_key;
                    }
                    if (! empty($_SESSION['affiliate']['styles'][$key_contrast]) && $_SESSION['affiliate']['styles'][$key_contrast] == 'offset') {
                        $css = str_replace("[".$key_contrast."]", '#'.str_replace('#', '', $_SESSION['affiliate']['styles'][$key_offset]), $css);
                    } else {
                        $css = str_replace("[".$key_contrast."]", '#'.str_replace('#', '', $val), $css);
                    }

                    $css = str_replace('['.$key.']', '#'.str_replace('#', '', $val), $css);
                } else {
                    $css = str_replace('['.$key.']', $val, $css);
                }
            }
        }

        $css  = correct_img_path($css);


        echo '<style type="text/css">'.minify_css($css).'</style>';
    }


    public function display_affiliate_avatar($vars)
    {
        $list 			= $vars;
        $html 		= null;


        if (! empty($list['url_avatar_one_pager'])) {
            $list['url_avatar'] 	=  correct_img_path($list['url_avatar_one_pager']);
        } elseif (empty($list['url_avatar']) && !empty($list['author_url_avatar'])) {
            $list['url_avatar'] = $list['author_url_avatar'];
        }
        if (! empty($list['url_avatar'])) {
            //$html .= '<div class="avatar-circle center-block" style="background: url('.correct_img_path($list['url_avatar']).') middle center cover; ">';
            $html .= '<div class="avatar-circle center-block">';
            $html .= '<img src="'.correct_img_path($list['url_avatar']).'" class="img-responsive" alt="" width="50" 	height="50">';
            $html .= '</div>';
        } else {
            $html .= '<div class="avatar-circle center-block">';
            if (! empty($list['affiliate_name'])) {
                $arr_tmp		= explode(' ', $list['affiliate_name']);
                if (! empty($arr_tmp[1])) {
                    $html 			.= '<span class="initials">'.strtoupper(substr($arr_tmp[0], 0, 1).substr($arr_tmp[1], 0, 1)).'</span>';
                } elseif (! empty($arr_tmp[1])) {
                    $html 			.= '<span class="initials">'.strtoupper(substr($arr_tmp[0], 0, 1)).'</span>';
                } else {
                    $html .= '<span class="initials"></span>';
                }
            } else {
                $html .= '<span class="initials"></span>';
            }

            $html .= '</div>';
        }

        if (! empty($vars['avatar_code_only'])) {
            return $html;
        } else {
            echo $html;
        }
    }


    public function get_affiliate_name_by_id($vars)
    {
        if (!is_array($vars)) {
            $new = $vars;
            unset($vars);
            $vars['id_affiliate']		= $new;
        }


        if (empty($vars['id_affiliate']) && !empty($vars['assign_id_affiliate'])) {
            $vars['id_affiliate']			= $vars['assign_id_affiliate'];
        }

        if (empty($vars['id_affiliate'])) {
            return null;
        }

        $id_affiliate		= $vars['id_affiliate'];

        if (! empty($vars['force_update']) || empty($_SESSION['affiliates_associated'])) {
            $aff																		= $this->get_affiliates();
            $_SESSION['affiliates_associated']						= array();
            foreach ($aff as $cur) {
                $cur_id																= $cur['id_affiliate'];
                $_SESSION['affiliates_associated'][$cur_id]		= $cur;
            }
        }

        return $_SESSION['affiliates_associated'][$id_affiliate]['affiliate_name'];
    }

    public function get_affiliate_id_by_church_id($vars)
    {
        if (!is_array($vars)) {
            $new 				= $vars;
            unset($vars);
            $vars['id_church']	= $new;
        }

        $church = $this->churches_model->get_church(array('id_church' => $vars['id_church'], 'bypass_church'=> 1, 'bypass_affiliate' => 1));


        if (! empty($church['assign_id_affiliate'])) {
            return $church['assign_id_affiliate'];
        }

        return null;
    }


    public function correct_affiliate_mismatch($vars)
    {

    //Check first if multiple affiliates are being passed to check
        if (! empty($vars['allowed_affiliates'])) {
            $vars['allowed_affiliates']	= array_unique($vars['allowed_affiliates']);

            //If there is one affiliate then reset it as the affiliate to check
            if (count($vars['allowed_affiliates']) == 1) {
                $vars['assign_id_affiliate']	 = $vars['allowed_affiliates'][0];
            } else {
                //TODO: if there are multiple affiliates - YIKES!!!!!! For now just pick the first one - may need to address later
                $vars['assign_id_affiliate']	 = $vars['allowed_affiliates'][0];
            }
        }

        if (! empty($vars['assign_id_affiliate']) && !empty($_SESSION['affiliate']['id_affiliate'])) {
            if ($_SESSION['affiliate']['id_affiliate'] != $vars['assign_id_affiliate']) {
                $actual_affiliate			= $this->affiliates_model->get_affiliate(array('id_affiliate' => $vars['assign_id_affiliate']));
                $uri						= str_replace('/ltp/', '', $_SERVER['REQUEST_URI']);
                $base_url 					= base_url();

                if (! empty($actual_affiliate['affiliate_subdomain'])) {
                    $base_url = 'https://'.$actual_affiliate['affiliate_subdomain'].'.promiseserves.org/';
                }

                $url	= str_replace('..', '.', $base_url.$uri);

                if (empty($vars['return_base'])) {
                    redirect($url);
                } else {
                    return $base_url;
                }
            }
        }

        return false;
    }


    public function correct_url_for_affiliate($vars)
    {
        $vars			= format_array_vals($vars, 'id_affiliate');
        $uri			= null;
        $base_url 		= base_url();
        if (empty($vars['url'])) {
            $vars['url']	= base_url().$_SERVER['REQUEST_URI'];
        }

        if (! empty($vars['id_affiliate']) && !empty($vars['url'])) {
            $url_parsed					= parse_url($vars['url']);
            $actual_affiliate			= $this->affiliates_model->get_affiliate(array('id_affiliate' => $vars['id_affiliate']));

            if (! empty($url_parsed['path'])) {
                $uri						= str_replace('/ltp/', '', $url_parsed['path']);
            }

            if (! empty($actual_affiliate['affiliate_subdomain'])) {
                $base_url = 'https://'.$actual_affiliate['affiliate_subdomain'].'.promiseserves.org/';
            }

            $url	= str_replace('.org//', '.org/', $base_url.$uri);
            $url	= str_replace('..', '.', $url);

            return $url;
        }
        return false;
    }

    public function get_affiliate_params($vars)
    {
        return $this->get_affiliates_params($vars);
    }

    public function get_affiliates_params($vars)
    {
        $sql = "SELECT p.*,
	aff.affiliate_name
	FROM `ltp_affiliate_params` AS p
	LEFT JOIN `ltp_affiliates` AS c ON c.id_affiliate = p.id_affiliate
	LEFT JOIN `ltp_affiliate_params` AS pa ON pa.id_affiliate_param = p.id_param_link AND p.param_link_type = 'affiliate'
	LEFT JOIN `ltp_church_params` AS pc ON pc.id_church_param = p.id_param_link AND p.param_link_type = 'church'
	LEFT JOIN `ltp_assignments` AS a ON a.id_affiliate = p.id_affiliate AND a.assignment_type = 'affiliate_to_church'
	LEFT JOIN `ltp_affiliates` AS aff ON aff.id_affiliate = a.id_affiliate
	WHERE 1=1 ";

        if (! empty($vars['id_affiliate_param'])) {
            $sql .= " AND p.id_affiliate_param =  ".$this->db->escape($vars['id_affiliate_param']);
        } else {
            if (! empty($vars['id_affiliate'])) {
                $sql .= " AND p.id_affiliate =  ".$this->db->escape($vars['id_affiliate']);
            }
            /*
            if( ! empty($vars['id_affiliate'])){
                $sql .= " AND .id_affiliate =  ".$this->db->escape($vars['id_affiliate']);
            }elseif( ! empty($_SESSION['affiliate']['id_affiliate']) && !$this->security_model->user_has_access(95)){
                $sql .= " AND a.id_affiliate =  ".$this->db->escape($_SESSION['affiliate']['id_affiliate']);
            }
            */
        }
        if (! empty($vars['param_type'])) {
            $sql .= " AND p.param_type =  ".$this->db->escape($vars['param_type']);
        }
        if (! empty($vars['param_name'])) {
            $sql .= " AND p.param_name =  ".$this->db->escape($vars['param_name']);
        }
        if (! empty($vars['id_salesforce'])) {
            $sql .= " AND (aff.id_salesforce =  ".$this->db->escape($vars['id_salesforce'])." OR SUBSTRING(aff.id_salesforce,1,15) = ".$this->db->escape($vars['id_salesforce']).")";
        }


        if (isset($vars['status'])) {
            switch (strtolower($vars['status'])) {
            case 'prospect':
            case 10:
                $sql .= " AND c.state = '10'";
            break;
            case 'active':
            case 40:
                $sql .= " AND c.state = '40'";
            break;
            case 'onhold':
            case 'inactive':
            case 22:
                $sql .= " AND c.state = '22'";
            break;
            case 'closed':
            case 24:
                $sql .= " AND c.state = '24'";
            break;
        }
        } elseif (isset($vars['state'])) {
            $sql .= " AND p.state = ".$this->db->escape($vars['state']);
        } else {
            $sql .= " AND p.state > 0";
        }
        if (! empty($vars['last_update'])) {
            $sql .= " AND p.date_mod >=  ".$this->db->escape($vars['last_update']);
        }
        if (! empty($vars['group_affiliate'])) {
            $sql .= " GROUP BY p.id_affiliate";
        } else {
            $sql .= " GROUP BY p.id_affiliate_param";
        }
        if (isset($vars['order'])) {
            $sql .= " ORDER BY ".$vars['order'];
        } else {
            $sql .= " ORDER BY p.ordering";
        }
        if (isset($vars['order_dir'])) {
            $sql .= " ".$vars['order_dir'];
        } else {
            $sql .= " ASC";
        }
        if (isset($vars['limit'])) {
            $sql .= " LIMIT ".$vars['limit'];
        } else {
            $sql .= " LIMIT 0,2000";
        }

        $query = $this->db->query($sql, false);
        if (! empty($vars['debug']) && $vars['debug'] == 'stop') {
            echo '<p><strong>$affiliates_model->get_affiliate_params: </strong>'.$sql.'</p>';
            exit();
        } elseif (! empty($vars['debug'])) {
            echo '<p><strong>$affiliates_model->get_affiliate_params: </strong>'.$sql.'</p>';
        }

        if ($query -> num_rows() > 0) {
            $r = $query->result_array();

            foreach ($r as $key => $val) {
                if ($key == 'param_value') {
                    $r[$key]['params_decoded']		= json_decode($r[$key]['param_value'], true);
                }
            }

            return $r;
        } else {
            return null;
        }
    }

    public function get_affiliate_param($vars = null)
    {
        $r = $this->get_affiliates_params($vars);
        if (! empty($r[0])) {
            return $r[0];
        }

        return null;
    }

    public function get_affiliate_domain($vars = null)
    {
        $vars = format_array_vals($vars, 'id_affiliate');

        if (is_localhost()) {
            $domain = str_replace('https://', '', base_url());
            $domain = str_replace('http://', '', $domain);
            return rtrim($domain, '/');
        }

        if (empty($vars['id_affiliate'])) {
            $vars['id_affiliate']	= $this->affiliates_model->get_active_affiliate_id();
        }

        if (! empty($vars['id_affiliate'])) {
            $posted_affiliate = $this->affiliates_model->get_affiliate(array('id_affiliate' => $vars['id_affiliate']));
        } elseif (! empty($_SESSION['affiliate'])) {
            $posted_affiliate = $_SESSION['affiliate'];
        }

        if (! empty($posted_affiliate) && $posted_affiliate['id_affiliate'] != 1) {
            return $posted_affiliate['affiliate_subdomain'] . ' . promiseserves.org';
        }

        return 'promiseserves.org';
    }

    public function get_affiliate_sql_limiters($vars)
    {
        $aff = array('achurch' => '', 'acomm' => '', 'acommchurch' => '', 'a' => '', 'aregion' => '', 'apeepevent' => '');

        foreach ($aff as $key => $val) {
            if (empty($vars['bypass_affiliate'])) {
                if (! empty($vars['id_affiliates'])) {
                    if (is_array($vars['id_affiliates'])) {
                        $vars['id_affiliates'] = implode(',', $vars['id_affiliates']);
                    }
                    $aff[$key] = " AND " . $key . ".id_affiliate IN (" . $vars['id_affiliates'] . ")";
                } elseif (! empty($vars['id_affiliate'])) {
                    $aff[$key] = " AND " . $key . ".id_affiliate =  " . $this->db->escape($vars['id_affiliate']);
                } elseif (! empty($_SESSION['affiliate']['id_affiliate']) && !$this->security_model->user_has_access(95)) {
                    $aff[$key] = " AND " . $key . ".id_affiliate =  " . $this->db->escape($_SESSION['affiliate']['id_affiliate']);
                }
            }
        }
        return $aff;
    }

    public function get_affiliate_logo($vars)
    {
        $vars = format_array_vals($vars, 'id_affiliate');

        if ($vars['id_affiliate'] == 1) {
            return base_url() . 'img/affiliates/1/logo_promise686.png';
        }

        if (does_file_exist(base_url() . 'img/affiliates/' . $vars['id_affiliate'] . '/logo_large.png')) {
            return base_url() . 'img/affiliates/' . $vars['id_affiliate'] . '/logo_large.png';
        }

        if (does_file_exist(base_url() . 'img/affiliates/' . $vars['id_affiliate'] . '/logo_nav.png')) {
            return base_url() . 'img/affiliates/' . $vars['id_affiliate'] . '/logo_nav.png';
        }

        return null;
    }


    public function is_active_affiliate_network_partner()
    {
        $aff['id_affiliate'] 	= $this->get_active_affiliate_id();
        $aff					= $this->get_affiliate($aff['id_affiliate']);
        //dds($aff);
        if (! empty($aff['is_promise_network_partner'])) {
            return true;
        }

        return false;
    }

    public function get_affiliate_time_zone($vars)
    {
        $vars = format_array_vals($vars, 'id_affiliate');

        if (! empty($vars['affiliate_time_zone'])) {
            return $vars['affiliate_time_zone'];
        }

        $aff = $this->get_affiliate(array('id_affiliate' => $vars['id_affiliate']));

        return ! empty($aff['affiliate_time_zone']) ? $aff['affiliate_time_zone'] : null;
    }
}
