<?php

/**
 * Class NF_Session_Add_On_Actions_SaveToSession
 */
final class NF_Session_Add_On_Actions_SaveToSession extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'save-to-session';

    /**
     * @var array
     */
    protected $_tags = [];

    /**
     * @var string
     */
    protected $_timing = 'late';

    /**
     * @var int
     */
    protected $_priority = '9';

    static $sessionPrefix = 'NF_SESSION_';

    /**
     * Constructor
     */
    public function __construct()
    {

        parent::__construct();

        $this->_nicename = __( 'Save to Session', 'ninja-forms' );
    }

    /*
    * PUBLIC METHODS
    */
    public function process( $action_settings, $form_id, $data )
    {
        $wp_session = \WP_Session::get_instance();

        foreach ($data['fields'] as $field) {
            if (isset($field['manual_key']) && $field['manual_key'] && isset($field['key'])) {
                $wp_session[self::$sessionPrefix . $field['key']] = $field['value'];

                // Add option information to the session
                $matchingOptions = array_filter($field['options'], function($option) use ($field) {
                    return $field['value'] == $option['value'];
                });
                $selectedOption = count($matchingOptions) == 1 ? array_pop($matchingOptions) : false;
                if ($selectedOption && isset($selectedOption['calc'])) {
                    $wp_session[self::$sessionPrefix . $field['key'] . ':calc'] = $selectedOption['calc'];
                }
                if ($selectedOption && isset($selectedOption['label'])) {
                    $wp_session[self::$sessionPrefix . $field['key'] . ':label'] = $selectedOption['label'];
                }
            }
        }

        if(isset($data['extra'])) {
            foreach ($data['extra'] as $extraKey => $extraValue) {
                $wp_session[self::$sessionPrefix . $extraKey] = $extraValue;
            }
        }

        $wp_session->write_data();
        $wp_session->set_cookie();

        return $data;
    }
}
