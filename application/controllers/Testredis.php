<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Predis\Client;

class Testredis extends CI_Controller {

    public function index() {
// Load the Predis library if it's not already loaded
        if (!class_exists('Predis\Client')) {
            require APPPATH . '../vendor/autoload.php';
        }

// Your Redis host and port
        $redis_host = '192.168.0.244';
        $redis_port = 6379;

// Redis authentication password
        $redis_password = 'ce27dc161bee69d07f62fb803ae4976e272e4ed103789f673d0832afade34ac8'; // Replace 'your_redis_password' with your actual Redis password

        try {
// Connect to Redis with authentication
            $redis = new Client(array(
                'scheme' => 'tcp',
                'host' => $redis_host,
                'port' => $redis_port,
                'password' => $redis_password,
            ));

// Try setting a test key
            $redis->set('test_key', 'Hello, Redis!');
            echo 'Connection to Redis successful. Test key set successfully!';
        } catch (Exception $ex) {
            echo 'Error connecting to Redis: ' . $ex->getMessage();
        }

        $max_calls_limit = 3;
        $time_period = 100;
        $total_user_calls = 0;
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $user_ip_address = $_SERVER['REMOTE_ADDR'];
        }
        if (!$redis->exists($user_ip_address)) {
            $redis->set($user_ip_address, 1);
            $redis->expire($user_ip_address, $time_period);
            $total_user_calls = 1;
        } else {
            $redis->INCR($user_ip_address);
            $total_user_calls = $redis->get($user_ip_address);
            if ($total_user_calls > $max_calls_limit) {
                echo "User " . $user_ip_address . " limit exceeded.";
                exit();
            }
        }

        echo "Welcome " . $user_ip_address . " total calls made " . $total_user_calls . " in " . $time_period . " seconds";
    }

}
