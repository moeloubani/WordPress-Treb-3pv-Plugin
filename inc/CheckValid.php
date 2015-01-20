<?php

namespace Loubani\WPTrebs3v;


class CheckValid {

    private $data;
    private $raw_unavailable;

    function __construct(GetData $data)
    {
        $this->raw_unavailable = $data->fetch(0);

        $this->data = self::getUnavailable($this->raw_unavailable);

    }

    private function deleteUnavailable()
    {

        $pagePosts = get_posts(
            array(
                'posts_per_page' => -1,
                'post_type'      => 'property',
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'   => '3pv_ml_num',
                        'value' => $this->data
                    )
                ),
            )
        );

        foreach ($pagePosts as $singlePost) {

            wp_delete_post($singlePost->ID, true);
        }

        return $pagePosts;

    }

    private function getUnavailable($data)
    {
        foreach ($data as $mls) {
            $unavailable[] = $mls['ml_num'];
        }

        $moved = self::deleteUnavailable();

        return $moved;
    }


}