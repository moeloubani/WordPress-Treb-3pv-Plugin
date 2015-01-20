<?php

namespace Loubani\WPTrebs3v;


class ManageImages {

    private $post;
    private $uploadFolder;
    private $server;
    private $username;
    private $password;
    private $property;

    function __construct($username, $password, $id, $property, $server = '3pv.torontomls.net')
    {
        $dir = wp_upload_dir();
        $this->username = $username . '@photos';
        $this->uploadFolder = $dir['basedir'] . '/3pv/';
        $this->password = $password;
        $this->server = $server;
        $this->post = $id;
        $this->property = $property;

        self::downloadImages();
    }


    public function downloadImages()
    {

        $lastThree = substr($this->property, -3);

        $base = '/mlsmultiphotos/';

        $counter = 1;

        $conn_id = ftp_connect($this->server);

        $login_result = ftp_login($conn_id, $this->username, $this->password);

        ftp_pasv($conn_id, true);

        while ($counter <= 9) {

            if ($counter === 1) {
                $localFile = $this->property . '.jpg';
                $remoteFile = $base . $counter . '/' . $lastThree . '/' . $this->property . '.jpg';
            } else {
                $localFile = $this->property . '_' . $counter . '.jpg';
                $remoteFile = $base . $counter . '/' . $lastThree . '/' . $this->property . '_' . $counter . '.jpg';
            }

            $localFile = $this->uploadFolder . $localFile;

            if (!file_exists($localFile) && $get = ftp_get($conn_id, $localFile, $remoteFile, FTP_BINARY)) {
                $downloaded[] = $localFile;
            }

            $counter++;

        }

        ftp_close($conn_id);

        self::assignImages($downloaded, $this->post);

    }

    public function assignImages($files, $post)
    {

        foreach ($files as $file) {

            $filetype = wp_check_filetype( basename( $file ), null );

            $attachment = array(
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $file, $post );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            wp_update_attachment_metadata( $attach_id, $attach_data );
        }


    }


}