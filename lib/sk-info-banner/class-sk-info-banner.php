<?php
class SK_Info_Banner {

    function __construct() {}

    /**
     * Check if banner is activated.
     *
     * @return boolean
     */
    public static function is_active() {

        if ( get_field( 'startpage_banner_activated', 'options' ) ) {
            if ( !empty( get_field( 'startpage_banner_content', 'options' ) ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns content for info-banner.
     *
     * @return void
     */
    public static function get_data() {
        $banner = [];
        
        $banner['bg_color']   = get_field( 'startpage_banner_bg_color', 'options' );
        $banner['text_color'] = get_field( 'startpage_banner_text_color', 'options' );

        if ( !empty( get_field( 'startpage_banner_link', 'options' ) ) ) {
            $banner['content'] = sprintf( 
                '<a href="%s"%s><span>%s</span><a>', 
                get_field( 'startpage_banner_link', 'options' ), 
                !empty( $banner['text_color'] ) ? ' style="color: ' . $banner['text_color'] . '"' : '', 
                get_field( 'startpage_banner_content', 'options' ) );
        } else {
            $banner['content'] = sprintf( '<span>%s</span>', get_field( 'startpage_banner_content', 'options' ) );
        }



        return $banner;
    }
}