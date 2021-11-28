#!/usr/bin/env php
<?php
    try {
        function test_url($url, $timeout) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_exec($ch);
            $http_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            curl_close($ch);
            return ($http_header_size > 0);
        }

        function main() {
            //==========================================================================================================
            $options = getopt(null, ["ip:", "nmap-t:", "http-t:", "scr-dir:"]);

            if (isset($options['ip']))
                $ip = $options['ip'];
            else
                throw new Exception("\nUsage: php searcher.php --ip X [--nmap-t X] [--http-t X] [--scr-dir X]");

            $nmap_timeout = isset($options['nmap-t']) ? $options['nmap-t'] : 5;
            $http_timeout = isset($options['http-t']) ? $options['http-t'] : 2;
            $screenshots_dir = isset($options['scr-dir']) ? $options['scr-dir'] : "./screenshots/";

            $output = [];

            //==========================================================================================================
            $cmd = "nmap ${ip} -T${nmap_timeout} -p 80 --open";
            echo "Running [${cmd}]\n";
            exec($cmd, $output, $return_code);
            if ($return_code !== 0)
                throw new Exception("Сommand [${cmd}] cannot be executed");
            echo "OK\n";

            //==========================================================================================================
            echo "Parsing IP addresses...\n";
            $ip_http_arr = [];
            foreach ($output as $i => $item) {
                if (strpos($item, "80/tcp open") !== false) {
                    for ($j = 1; strpos($output[$k = $i - $j], "Nmap scan report for") === false; $j++);
                    $ip = substr($output[$k], 21);
                    array_push($ip_http_arr, $ip);
                }
            }
            echo "OK. " . count($ip_http_arr) . " addresses\n";
            if (empty($ip_http_arr))
                exit();
            foreach ($ip_http_arr as $ip)
                echo "${ip} ";
            echo "\n";

            //==========================================================================================================
            echo "Searching for active HTTP servers...\n";
            $ip_http_ok_arr = [];
            foreach ($ip_http_arr as $ip) {
                $url = "http://${ip}";
                if (test_url($url, $http_timeout))
                    array_push($ip_http_ok_arr, $ip);
            }
            echo "OK. " . count($ip_http_ok_arr) . "/" . count($ip_http_arr) . "\n";
            if (count($ip_http_ok_arr) == 0) exit();
            foreach ($ip_http_ok_arr as $ip) {
                echo "${ip} ";
            }
            echo "\n";

            //==========================================================================================================
            echo "Taking screenshots...\n";
            if (!file_exists($screenshots_dir))
                mkdir($screenshots_dir);

            $screenshots_dir = realpath($screenshots_dir);
            foreach ($ip_http_ok_arr as $ip) {
                $cmd = "chrome --headless --screenshot=${screenshots_dir}/${ip}.jpg --window-size=1024,768 http://${ip}/";
                exec($cmd, $output, $return_code);
                if ($return_code !== 0)
                    throw new Exception("Сommand [${cmd}] cannot be executed");
            }
            echo "OK\n";
        }

        main();
    } catch (Exception $error) {
        echo 'Error: ',  $error->getMessage(), "\n";
    }
?>
