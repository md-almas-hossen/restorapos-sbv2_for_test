<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Messenger_model extends CI_Model
{
    public function get_config()
    {
        return $this->db->get('messenger_settings')->result_array() ?: [];
    }

    public function upsert_config(array $user_configs)
    {
        // build current configs
        $config = $this->get_config();
        $config_key_Arr = array_column($config, 'conf_key');

        // build new config
        $to_insert = $to_update = [];

        foreach ($user_configs as $user_conf_key => $user_conf_val) {

            if (($exist_conf_key = array_search($user_conf_key, $config_key_Arr)) === false) {
                // config not exits in database
                $to_insert[] = ['conf_key' => $user_conf_key, 'conf_value' => $user_conf_val];
                continue;
            }

            // update existing config
            $exist_conf_id = $config[$exist_conf_key]['id'];
            $to_update[] = ['id' => $exist_conf_id, 'conf_value' => $user_conf_val];
        }

        $to_insert && $this->db->insert_batch('messenger_settings', $to_insert);
        $to_update && $this->db->update_batch('messenger_settings', $to_update, 'id');
    }
}
