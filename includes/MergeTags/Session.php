<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Session_Add_On_MergeTags_Session
 */
final class NF_Session_Add_On_MergeTags_Session extends NF_Abstracts_MergeTags
{
    protected $id = 'session';

    public function __construct()
    {
        parent::__construct();
        $this->title = __( 'Session', 'ninja-forms' );

        $this->merge_tags = array(
            '' => array(
                'tag' => '{session:}',
                'label' => __( 'Session', 'ninja_forms' ),
            ),
        );

        add_filter( 'ninja_forms_render_options', [ $this, 'session_default' ], 10, 2 );

        $wp_session = \WP_Session::get_instance();

        foreach( $wp_session as $key => $value ){
            if (!preg_match('/^'.NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix.'/', $key)) {
                continue;
            }
            $value = $wp_session[ $key ];
            $key = preg_replace('/^'.NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix.'/', '', $key);
            $this->set_merge_tags( $key, $value );
        }
    }

    public function __call($name, $arguments)
    {
        return $this->merge_tags[ $name ][ 'value' ];
    }

    public function set_merge_tags( $key, $value )
    {
        $callback = ( is_numeric( $key ) ) ? 'session_' . $key : $key;

        $this->merge_tags[ $callback ] = array(
            'id' => $key,
            'tag' => "{session:" . $key . "}",
            'callback' => $callback,
            'value' => $value
        );
    }

    public function session_default( $options, $settings )
    {
        if( ! isset( $settings[ 'key' ] ) ) return $options;

        $field_key = $settings[ 'key' ];


        $wp_session = \WP_Session::get_instance();

        if( ! isset( $wp_session[ NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix . $field_key]) ) return $options;

        foreach( $options as $key => $option ){

            if( ! isset( $option[ 'value' ] ) ) continue;

            if( $option[ 'value' ] != $wp_session[ NF_Session_Add_On_Actions_SaveToSession::$sessionPrefix . $field_key ] ) continue;

            $options[ $key ][ 'selected' ] = 1;
        }

        return $options;
    }

} // END CLASS NF_Session_Add_On_MergeTags_Session
