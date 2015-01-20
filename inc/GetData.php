<?php
namespace Loubani\WPTrebs3v;

class GetData
{

    public $login_info;
    private $url;
    private $today;
    private $cache_folder;

    function __construct( $username, $password, $url )
    {
        $this->login_info = array(
            'username' => $username,
            'password' => $password
        );
        $this->url        = $url;

        $this->today = new \DateTime('now', new \DateTimeZone('America/Toronto'));

        $this->cache_folder = '' . dirname(__DIR__) . '/cache/';
    }

    public function fetch($available = 1)
    {
        $ch = curl_init();

        if ($available === 1) {
            $available = 'avail';
        } else {
            $available = 'unavail';
        }

        curl_setopt($ch, CURLOPT_URL,$this->url);
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array(
                'user_code'     => $this->login_info['username'],
                'password'      => $this->login_info['password'],
                'sel_fields'    => '*',
                'au_both'       => $available,
                'dlDay'         => '01',
                'dlMonth'       => '05',
                'dlYear'        => '2014',
                'dl_type'       => 'file',
                'incl_names'    => 'yes',
                'use_table'     => 'MLS',
                'send_done'     => 'no',
                'submit1'       => 'Submit',
                'query_str'     => "lud>='20140501'"
            )));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        $path = self::save($server_output, $available);
        $properties = self::get($path);

        return $properties;
    }

    private function save($data, $availability)
    {

        $filename = 'data' . $this->today->format('Ymd') . '.csv';

        if ($availability === 'unavail')
            $filename = 'data' . $this->today->format('Ymd') . 'u.csv';

        $full_path = $this->cache_folder . $filename;

        if (!file_exists ($full_path)) {
            $save = file_put_contents($full_path,$data);

            if ($save === false) {
                return 'error';
            }
        }

        return $full_path;
    }

    public function get($path) {

        $property_data = self::arrayFromCSV($path);

        return $property_data;
    }

    private static function arrayFromCSV($file)
    {
        $all_rows = array();
        $header = null;

        $file_handle = fopen($file, 'r');

        while ($row = fgetcsv($file_handle)) {
            if ($header === null) {
                $header = $row;
                continue;
            }
            $all_rows[] = array_combine($header, $row);
        }

        return $all_rows;
    }

}