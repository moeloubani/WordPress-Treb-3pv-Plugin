<?php

namespace Loubani\WPTrebs3v;


class ProcessData
{
    private $data;
    private $loginInfo;

    function __construct(GetData $data)
    {
        $this->data = $data->fetch();
        $this->loginInfo = $data->login_info;
        self::checkUpdateOrCreate($this->data);
    }

    public function checkUpdateOrCreate($properties)
    {
        global $post;


        foreach ($properties as $property) {

            $args = array(
                'meta_query' => array(
                    array(
                        'key' => '3pv_ml_num',
                        'value' => $property['ml_num']
                    )
                ),
                'post_type' => 'property'
            );

            $propertyQuery = new \WP_Query($args);

            if ($propertyQuery->have_posts() && $propertyQuery !== null) {

                while ($propertyQuery->have_posts()) {

                    $propertyQuery->the_post();
                    self::checkPropertyForUpdate($post->ID, $property);

                }

            } else {

                self::createProperty($property);
            }

            wp_reset_postdata();
        }
    }

    private function checkPropertyForUpdate($property, $data)
    {
        $last_update = get_post_meta($property, '3pv_lud', true);

        if ($last_update !== $data['lud']) {
            self::updateProperty($property, $data);
        }

    }

    public function updateProperty($property_id, $property)
    {

        update_post_meta($property_id, '3pv_a_c', $property['a_c']);
        update_post_meta($property_id, '3pv_acres', $property['acres']);
        update_post_meta($property_id, '3pv_apt_num', $property['apt_num']);
        update_post_meta($property_id, '3pv_bath_tot', $property['bath_tot']);
        update_post_meta($property_id, '3pv_br', $property['br']);
        update_post_meta($property_id, '3pv_br_plus', $property['br_plus']);
        update_post_meta($property_id, '3pv_bsmt1_out', $property['bsmt1_out']);
        update_post_meta($property_id, '3pv_bsmt2_out', $property['bsmt2_out']);
        update_post_meta($property_id, '3pv_bus_type', $property['bus_type']);
        update_post_meta($property_id, '3pv_chattels', $property['chattels']);
        update_post_meta($property_id, '3pv_class', $property['class']);
        update_post_meta($property_id, '3pv_community', $property['community']);
        update_post_meta($property_id, '3pv_constr1_out', $property['constr1_out']);
        update_post_meta($property_id, '3pv_constr2_out', $property['constr2_out']);
        update_post_meta($property_id, '3pv_county', $property['county']);
        update_post_meta($property_id, '3pv_cross_st', $property['cross_st']);
        update_post_meta($property_id, '3pv_depth', $property['depth']);
        update_post_meta($property_id, '3pv_den_fr', $property['den_fr']);
        update_post_meta($property_id, '3pv_drive', $property['drive']);
        update_post_meta($property_id, '3pv_extras', $property['extras']);
        update_post_meta($property_id, '3pv_fuel', $property['fuel']);
        update_post_meta($property_id, '3pv_gar_type', $property['gar_type']);
        update_post_meta($property_id, '3pv_heating', $property['heating']);
        update_post_meta($property_id, '3pv_lp_dol', $property['lp_dol']);
        update_post_meta($property_id, '3pv_ld', $property['ld']);
        update_post_meta($property_id, '3pv_municipality', $property['municipality']);
        update_post_meta($property_id, '3pv_oth_struc1_out', $property['oth_struc1_out']);
        update_post_meta($property_id, '3pv_park_fac', $property['park_fac']);
        update_post_meta($property_id, '3pv_pool', $property['pool']);
        update_post_meta($property_id, '3pv_s_r', $property['s_r']);
        update_post_meta($property_id, '3pv_sewer', $property['sewer']);
        update_post_meta($property_id, '3pv_sqft', $property['sqft']);
        update_post_meta($property_id, '3pv_st', $property['st']);
        update_post_meta($property_id, '3pv_st_num', $property['st_num']);
        update_post_meta($property_id, '3pv_st_sfx', $property['st_sfx']);
        update_post_meta($property_id, '3pv_style', $property['style']);
        update_post_meta($property_id, '3pv_taxes', $property['taxes']);
        update_post_meta($property_id, '3pv_type_own1_out', $property['type_own1_out']);
        update_post_meta($property_id, '3pv_unit_num', $property['unit_num']);
        update_post_meta($property_id, '3pv_water', $property['water']);
        update_post_meta($property_id, '3pv_zip', $property['zip']);
        update_post_meta($property_id, '3pv_zoning', $property['zoning']);
        update_post_meta($property_id, '3pv_disp_addr', $property['disp_addr']);
        update_post_meta($property_id, '3pv_lud', $property['lud']);
        update_post_meta($property_id, '3pv_pix', $property['pix']);

    }


    private function createProperty($property)
    {
        $post = array(
            'post_content' => $property['ad_text'],
            'post_title' => $property['addr'],
            'post_status' => 'publish',
            'post_type' => 'property'
        );

        $posted = wp_insert_post($post);

        add_post_meta($posted, '3pv_a_c', $property['a_c'], true);
        add_post_meta($posted, '3pv_acres', $property['acres'], true);
        add_post_meta($posted, '3pv_apt_num', $property['apt_num'], true);
        add_post_meta($posted, '3pv_bath_tot', $property['bath_tot'], true);
        add_post_meta($posted, '3pv_br', $property['br'], true);
        add_post_meta($posted, '3pv_br_plus', $property['br_plus'], true);
        add_post_meta($posted, '3pv_bsmt1_out', $property['bsmt1_out'], true);
        add_post_meta($posted, '3pv_bsmt2_out', $property['bsmt2_out'], true);
        add_post_meta($posted, '3pv_bus_type', $property['bus_type'], true);
        add_post_meta($posted, '3pv_chattels', $property['chattels'], true);
        add_post_meta($posted, '3pv_class', $property['class'], true);
        add_post_meta($posted, '3pv_community', $property['community'], true);
        add_post_meta($posted, '3pv_constr1_out', $property['constr1_out'], true);
        add_post_meta($posted, '3pv_constr2_out', $property['constr2_out'], true);
        add_post_meta($posted, '3pv_county', $property['county'], true);
        add_post_meta($posted, '3pv_cross_st', $property['cross_st'], true);
        add_post_meta($posted, '3pv_depth', $property['depth'], true);
        add_post_meta($posted, '3pv_den_fr', $property['den_fr'], true);
        add_post_meta($posted, '3pv_drive', $property['drive'], true);
        add_post_meta($posted, '3pv_extras', $property['extras'], true);
        add_post_meta($posted, '3pv_fuel', $property['fuel'], true);
        add_post_meta($posted, '3pv_gar_type', $property['gar_type'], true);
        add_post_meta($posted, '3pv_heating', $property['heating'], true);
        add_post_meta($posted, '3pv_ld', $property['ld'], true);
        add_post_meta($posted, '3pv_lp_dol', $property['lp_dol'], true);
        add_post_meta($posted, '3pv_ml_num', $property['ml_num'], true);
        add_post_meta($posted, '3pv_municipality', $property['municipality'], true);
        add_post_meta($posted, '3pv_oth_struc1_out', $property['oth_struc1_out'], true);
        add_post_meta($posted, '3pv_park_fac', $property['park_fac'], true);
        add_post_meta($posted, '3pv_pool', $property['pool'], true);
        add_post_meta($posted, '3pv_s_r', $property['s_r'], true);
        add_post_meta($posted, '3pv_sewer', $property['sewer'], true);
        add_post_meta($posted, '3pv_sqft', $property['sqft'], true);
        add_post_meta($posted, '3pv_st', $property['st'], true);
        add_post_meta($posted, '3pv_st_num', $property['st_num'], true);
        add_post_meta($posted, '3pv_st_sfx', $property['st_sfx'], true);
        add_post_meta($posted, '3pv_style', $property['style'], true);
        add_post_meta($posted, '3pv_taxes', $property['taxes'], true);
        add_post_meta($posted, '3pv_type_own1_out', $property['type_own1_out'], true);
        add_post_meta($posted, '3pv_unit_num', $property['unit_num'], true);
        add_post_meta($posted, '3pv_water', $property['water'], true);
        add_post_meta($posted, '3pv_zip', $property['zip'], true);
        add_post_meta($posted, '3pv_zoning', $property['zoning'], true);
        add_post_meta($posted, '3pv_disp_addr', $property['disp_addr'], true);
        add_post_meta($posted, '3pv_lud', $property['lud'], true);
        add_post_meta($posted, '3pv_pix', $property['pix'], true);

        $addImages = new ManageImages($this->loginInfo['username'], $this->loginInfo['password'], $posted, $property['ml_num']);

        return $posted;
    }
}