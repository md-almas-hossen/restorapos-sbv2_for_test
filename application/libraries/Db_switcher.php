<?php
// application/libraries/Db_switcher.php
class Db_switcher {

    protected $CI;
    protected static $db_instance = null; // Static variable to hold the database connection

    public function __construct() {
        $this->CI =& get_instance();
    }

    // public function switch_db() {
    //     // Ensure the session library is loaded before accessing session data
    //     $this->CI->load->library('session');

    //     // Get the logged-in user (example)
    //     $user = $this->CI->session->userdata('user_for_hook');  // Adjust as needed for your session logic

    //     if ($user) {
    //         // Assuming you have a method to get DB config for the user
    //         $user_db_config = $this->get_user_db_config($user);

    //         // Check if the DB config is properly loaded
    //         if ($user_db_config) {
    //             // Debug the DB config to ensure it's correct
    //             // echo "<pre>";
    //             // print_r($user_db_config);

    //             // Attempt to load the database connection with user-specific configuration
    //             $this->CI->load->database($user_db_config, FALSE, TRUE);

    //             // Check if the connection is successful
    //             if ($this->CI->db->conn_id) {
    //                 // echo 'Database connected successfully!';exit;
    //             } else {
    //                 // log_message('error', 'Failed to connect to database!');
    //                 echo 'Failed to connect to database!';exit;
    //             }
    //         } else {
    //             // log_message('error', 'Database configuration not found for the user');
    //             echo 'Database configuration not found for the user';exit;
    //         }
    //     }
    // }

    // public function switch_db() {
    //     // Load the session library
    //     $this->CI->load->library('session');

    //     // Get the logged-in user (from session)
    //     $user = $this->CI->session->userdata('user_for_hook');  // Adjust according to your session structure

    //     if ($user) {
    //         // Check if the database configuration is already stored in the session
    //         $user_db_config = $this->CI->session->userdata('user_db_config');

    //         if (!$user_db_config) {
    //             // No configuration found in session, so establish a new one
    //             $user_db_config = $this->get_user_db_config($user);

    //             // Store the database configuration in session to reuse in the future
    //             $this->CI->session->set_userdata('user_db_config', $user_db_config);
    //         }

    //         // Load the database connection with the user-specific configuration
    //         $this->CI->load->database($user_db_config, FALSE, TRUE);
    //     }
    // }

    public function switch_db() {
        // Load the session library
        $this->CI->load->library('session');

        // dd($this->CI->db);

        // Get the logged-in user from session
        $user = $this->CI->session->userdata('user_for_hook');

        if ($user) {
            // Check if the database configuration is already stored in the session
            $user_db_config = $this->CI->session->userdata('user_db_config');

            if (!$user_db_config) {
                // No configuration found in session, so establish a new one
                $user_db_config = $this->get_user_db_config($user);

                // Store the database configuration in session to reuse in the future
                $this->CI->session->set_userdata('user_db_config', $user_db_config);
            }

            // Check if the database instance is already created
            if (!self::$db_instance) {
                // Load the database connection with the user-specific configuration
                self::$db_instance = $this->CI->load->database($user_db_config, TRUE);
            }

            // Assign the static database instance to CI's DB object
            $this->CI->db = self::$db_instance;

            // dd($this->CI->db);
        }
    }

    public static function db_connectio_reset() {
        self::$db_instance = null;
    }

    // Example function to retrieve user-specific database config
    private function get_user_db_config($user) {
        // Return user-specific database configuration
        return array(
            'dsn'      => '',
            'hostname' => $user['hostname'],
            'username' => $user['db_username'],
            'password' => $user['password'],
            'database' => $user['database'],
            'dbdriver' => 'mysqli',
            'dbprefix' => '',
            'pconnect' => FALSE,  // Disable persistent connection
            'db_debug' => (ENVIRONMENT !== 'production'),
            'cache_on' => FALSE,
            'cachedir' => '',
            'char_set' => 'utf8',
            'dbcollat' => 'utf8_general_ci',
            'swap_pre' => '',
            'encrypt' => FALSE,
            'compress' => FALSE,
            'stricton' => FALSE,
            'failover' => array(),
            'save_queries' => TRUE
        );
    }
}
