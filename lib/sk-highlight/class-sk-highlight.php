<?php
class SK_Highlight {

    function __construct() {
        add_action( 'init', function () {
            $this->init();
        } );
    }

    /**
     * Init
     *
     * @return void
     */
    public function init() {

        if( self::is_active() ){
            add_action( 'the_post', [$this, 'add_badge_to_title'] );
        }
    
        add_action( 'acf/save_post', [$this, 'save_acf_option'], 20 );
    }

    /**
     * Check if highlight is activated.
     *
     * @return boolean
     */
    public static function is_active() {

        if ( get_field( 'highlight_activated', 'options' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Add the badge to title.
     *
     * @return void
     */
    public function add_badge_to_title() {
        global $post;
        if ( get_field( 'sk_highlight', $post->ID ) ) {
            add_filter( 'the_title', ['SK_Highlight', 'rerender_title'], 10, 2 );
        }
    }

    /**
     * If highlighted add label to titel.
     *
     * @return boolean
     */
    public static function is_highlighted( $title = '', $post_id = '' ) {

        if ( self::is_active() && get_field( 'sk_highlight', $post_id ) ) {
            return self::rerender_title( $title );
        }
        return $title;

    }

    /**
     * Add badge into title tag.
     *
     * @param [type] $title
     * @param [type] $id
     * @return void
     */
    public static function rerender_title( $title, $id = null ) {

        $text = get_transient( 'highlight_transient' );
        return $title . $text;

    }

    /**
     * Handle transient on save option.
     *
     * @return void
     */
    function save_acf_option() {
        $screen = get_current_screen();

        if ( $screen->id === 'webbplatsen_page_acf-options-msva' ) {
            delete_transient( 'highlight_transient' );

            $text          = get_field( 'highlight_text', 'options' );
            $color['text'] = !empty( get_field( 'highlight_text_color', 'options' ) ) ? 'color: ' . get_field( 'highlight_text_color', 'options' ) . ';' : '';
            $color['bg']   = !empty( get_field( 'highlight_bg_color', 'options' ) ) ? 'background-color: ' . get_field( 'highlight_bg_color', 'options' ) . ';' : '';

            $style = '';
            if ( !empty( $color['text'] || $color['bg'] ) ) {
                $style = sprintf( ' style="%s %s"', $color['text'], $color['bg'] );
            }

            $badge = sprintf( ' <span class="highlight-badge"%s>%s</span>', $style, $text );

            set_transient( 'highlight_transient', $badge );

        }

    }
}
