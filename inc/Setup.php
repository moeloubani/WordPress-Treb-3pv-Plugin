<?php

namespace Loubani\WPTrebs3v;


class Install {


    function __construct()
    {
        add_action('init', array($this, 'registerPostType'));
    }

    public function registerPostType()
    {
        $args = array(
            'public' => true,
            'label'  => 'Properties',
            'supports' => array('title', 'custom-fields', 'editor')
        );

        register_post_type( 'property', $args );
    }
}