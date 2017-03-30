<?php

/**
 * Form helper class
 * Render form elements from argument arrays
 */

class Form_helper {

    /**
     * @var bool
     */
    private $is_xhtml;

    /**
     * @var string|bool
     */
    private $wrapper_tag;

    /**
     * Constructor.
     *
     * @param bool        $is_xhtml     (optional) If true self closing tags generated like <br />
     *                                  and input params like checked="checked". Default true, because WP Admin
     *                                  use XHTML markup.
     *
     * @param string|bool $wrapper_tag  (optional) If this is a html tag name, all fields wrapped
     *                                  inside that. If false, no wrapper generated. Default 'div'.
     *
     */
    public function __construct( $is_xhtml = true, $wrapper_tag = 'div' ) {
        $this->is_xhtml = $is_xhtml;
        $this->wrapper_tag = true === $wrapper_tag ? 'div' : $wrapper_tag;
    }

    /**
     * Render <input type="email">
     *
     * @param array        $args
     * @param string|null  $group (optional) If this param is a string, and the wrapper_tag set,
     *                            format of wrapper class: class="wrap-{$field} wrap-{$group}"
     */
    public function render_input_email( $args, $group = null ) {
        $this->render_input_text( $args, $group );
    }

    /**
     * Render <input type="text">
     *
     * @param array        $args
     * @param string|null  $group (optional)
     */
    public function render_input_text( $args, $group = null ) {
        $this->check_args( $args, 'name' );
        $args = array_merge( [
                'class' => 'regular-text',
                'size'  => 72
            ], $args );

        /**
         * @var $name
         */
        extract( $args );

        $this->open_wrapper( $name, $group );

        if ( isset( $label ) ) {
            if ( isset( $id ) ) {
                $for = $id;
            } else {
                $for = $name;
                $args['id'] = $name;
            }
            $this->html_tag( [ 'label', [ 'for' => $for ], $label, true ] );
        }

        $valid = 'name|id|class|type|value|size|minlength|maxlength|disabled|readonly|placeholder|required|spellcheck';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'input', $atts ] );

        if ( isset( $desc ) ) {
            $this->html_tag( [ 'br' ] );
            $this->html_tag( [ 'span', [ 'class' => 'description' ], $desc, true ] );
        }

        $this->close_wrapper();
    }

    /**
     * Render <input type="hidden">
     *
     * @param array $args
     */
    public function render_input_hidden( $args ) {
        $this->check_args( $args, [ 'name', 'value' ] );

        $valid = 'name|id|class|type|value';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'input', $atts ] );
    }

    /**
     * Render <textarea>
     *
     * @param array        $args
     * @param string|null  $group (optional)
     */
    public function render_textarea( $args, $group = null ) {
        $this->check_args( $args, 'name' );
        $args = array_merge( [
                'class' => 'regular-text',
                'cols'  => 72,
                'rows'  => 5
            ], $args );

	    /**
	     * @var $name
	     */
        extract( $args );

        $this->open_wrapper( $name, $group );

        if ( isset( $label ) ) {
            if ( isset( $id ) ) {
                $for = $id;
            } else {
                $for = $name;
                $args['id'] = $name;
            }
            $this->html_tag( [ 'label', [ 'for' => $for ], $label, true ] );
        }

        if ( ! isset( $value ) ) {
            $value = '';
        }

        $valid = 'name|id|class|cols|rows|minlength|maxlength|disabled|readonly|placeholder|required|spellcheck';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'textarea', $atts, $value, true ] );

        if ( isset( $desc ) ) {
            $this->html_tag( [ 'br' ] );
            $this->html_tag( [ 'span', [ 'class' => 'description' ], $desc, true ] );
        }

        $this->close_wrapper();
    }

    /**
     * Render <select>
     *
     * @param array        $args
     * @param string|null  $group (optional)
     */
    public function render_select( $args, $group = null ) {
        $this->check_args( $args, [ 'name', 'value', 'options' ] );

	    /**
	     * @var $name
	     * @var $options
	     * @var $value
	     */
	    extract( $args );

	    $this->open_wrapper( $name, $group );

         if ( isset( $label ) ) {
            if ( isset( $id ) ) {
                $for = $id;
            } else {
                $for = $name;
                $args['id'] = $name;
            }
            $this->html_tag( [ 'label', [ 'for' => $for ], $label, true ] );
        }

        $valid = 'name|id|class|size|multiple|disabled|required';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'select', $atts ] );

        foreach ( $options as $label => $opt ) {
            // Single select
            if ( is_int( $value ) || is_string( $value ) ) {
                if ( $opt['value'] === $value ) {
                    $opt['selected'] = true;
                }
            // Multiple select
            } elseif ( is_array( $value ) ) {
                foreach ( $value as $val ) {
                    if ( $opt['value'] === $val ) {
                        $opt['selected'] = true;
                    }
                }
            }

            $valid = 'selected|value|disabled';
	        $atts = $this->get_valid_atts( $opt, $valid );
            $this->html_tag( [ 'option', $atts, $opt['label'], true ] );
        }

        echo "</select>\n";

        if ( isset( $desc ) ) {
            $this->html_tag( [ 'br' ] );
            $this->html_tag( [ 'span', [ 'class' => 'description' ], $desc, true ] );
        }

        $this->close_wrapper();
    }

    /**
     * Render <input type="checkbox">
     *
     * @param array        $args
     * @param string|null  $group (optional)
     */
    public function render_input_checkbox( $args, $group = null ) {
        $this->check_args( $args, [ 'name', 'label', 'value' ] );

	    /**
	     * @var $name
	     * @var $label
	     * @var $value
	     */
        extract( $args );

        $this->open_wrapper( $name, $group );

        if ( isset( $id ) ) {
            $for = $id;
        } else {
            $for = $name;
            $args['id'] = $name;
        }
        $this->html_tag( [ 'label', [ 'for' => $for ] ] );

        if ( 0 == $value ) {
            unset( $args['checked'] );
            $args['value'] = 1;  // If user checks back, save positive value
        }

        $valid = 'name|id|class|type|value|checked|disabled|required';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'input', $atts ] );

        echo "$label</label>";

        if ( isset( $desc ) ) {
            $this->html_tag( [ 'br' ] );
            $this->html_tag( [ 'span', [ 'class' => 'description' ], $desc, true ] );
        }

        $this->close_wrapper();
    }

    /**
     * Render <button type="submit">
     *
     * @param array        $args
     * @param string|null  $group (optional)
     */
    public function render_button_submit( $args, $group = null ) {
        $this->check_args( $args, 'text' );

	    /**
	     * @var $text
	     */
        extract( $args );
	    $name = isset( $name ) ? $name : 'submit';

        $this->open_wrapper( $name, $group );

        $valid = 'name|id|class|type|value|disabled';
        $atts = $this->get_valid_atts( $args, $valid );
        $this->html_tag( [ 'button', $atts, $text, true ] );

        $this->close_wrapper();
    }

    /**
     * Print wrapper tag with class="wrap-{$field} wrap-{$group}"
     *
     * @param string      $field
     * @param string|null  $group (optional)
     */
    public function open_wrapper( $field, $group = null ) {
        if ( false === $this->wrapper_tag ) {
            return;
        }

        $class = 'wrap-' . $field;
        $class .= isset( $group ) ? ' wrap-' . $group : '';

        $this->html_tag( [ $this->wrapper_tag, [ 'class' => $class ] ] );
    }

    /**
     * Close wrapper
     */
    public function close_wrapper() {
        if ( false === $this->wrapper_tag ) {
            return;
        }

        echo '</' . $this->wrapper_tag . ">\n";
    }

    /**
     * Print an html tag
     *
     * @param array $args [0] string Tag name
     *                    [1] array  Attribute name => value pairs
     *                    [2] string After tag
     *                    [3] bool   Close tag
     */
    public function html_tag( $args ) {
        echo '<' . $args[0];
        if ( isset( $args[1] ) ) {
            foreach ( $args[1] as $attr => $val ) {
                if ( true === $val ) {
                    if ( $this->is_xhtml ) {
                        echo ' ' . $attr , '="' . $attr . '"';  // checked="checked"
                    } else {
                        echo ' ' . $attr;  // checked
                    }
                } elseif ( false !== $val ) {
                    echo ' ' . $attr . '="' . $val . '"';
                }
            }
        }
        if ( $this->is_xhtml ) {
            echo in_array( $args[0], [ 'input', 'img', 'br', 'hr' ] ) ? ' />' : '>';
        } else {
            echo '>';
        }

        echo isset( $args[2] ) ? $args[2] : '';
        echo isset( $args[3] ) && true === $args[3] ? '</' . $args[0] . ">\n" : "\n";
    }

    /**
     * Check important arguments
     *
     * @param array $args Array to check for
     * @param mixed $keys String or array with keys
     */
    public function check_args( $args, $keys ) {
        if ( is_string( $keys ) ) {
            $keys = [ $keys ];
        }

        foreach ( $keys as $key ) {
            if ( ! isset( $args[$key] ) || empty( $args[$key] ) ) {
                trigger_error( "Misssing required param <strong>\$args['$key']</strong>", E_USER_ERROR );
            }
        }
    }

	/**
	 * Drop invalid attributes
	 *
	 * @param array  $args       All html-element arguments
	 * @param string $valid_atts Valid attributes separated by horizontal line
	 * @return array Filtered valid attributes only
	 */
	protected function get_valid_atts( $args, $valid_atts ) {
		return array_intersect_key( $args, array_flip( explode( '|', $valid_atts ) ) );
	}

}
