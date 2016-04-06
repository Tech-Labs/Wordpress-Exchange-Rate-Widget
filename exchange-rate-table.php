<?php
/*
Plugin Name: Exchange Rate Widget
Description: Advanced Exchange Rate Widget
Author: Ibrahim Mohamed Abotaleb
Version: 1.0
Author URI: http://mrkindy.com/
Text Domain: exchange-rate
Domain Path: /languages
*/
// Creating the widget
class exchange_rate_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct( // Base ID of your widget
            'exchange_rate_widget', // Widget name will appear in UI
            __('Exchange Rate Widget', 'exchange-rate'), // Widget description
            array('description' => __('Advanced Exchange Rate Widget .','exchange-rate'))
            );
    }
    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if(! empty($title))
            echo str_replace('fa-bars','fa-usd',$args['before_title']) .  $title . $args['after_title'];
        // This is where you run the code and display the output
        
        $to = explode(',',$instance['to']);
        $from = $instance['from'];
        $result = get_transient( 'exchange_rate_widget' );
        if ( false == $result ) {
            foreach($to as $cur)
            {
                $res = file_get_contents("http://download.finance.yahoo.com/d/quotes.csv?s=$cur$from=X&f=sl1d1t1ba&e=.csv");
                $res = explode(',',$res);
                $currency[$cur] = $res[1];
            }
            set_transient( 'exchange_rate_widget', $currency ,1800);
          
            $data = $currency;
        }else{
            $data = $result;
        }
        require 'exchange-rate-table-view.php';
        echo $args['after_widget'];
    }
    // Widget Backend
    public function form($instance)
    {
        if(isset($instance['title']))
        {
            $title = $instance['title'];
        }
        else
        {
            $title = __('Exchange Rate', 'exchange-rate');
        }
        if(isset($instance['from']))
        {
            $from = $instance['from'];
        }
        else
        {
            $from = 'usd';
        }
        if(isset($instance['to']))
        {
            $to = $instance['to'];
        }
        else
        {
            $to = 'eur,gbp,inr,aud,cad,zar,nzd,jpy';
        }
        if(isset($instance['Currency_title']))
        {
            $Currency_title = $instance['Currency_title'];
        }
        else
        {
            $Currency_title = '$';
        }
        // Widget admin form
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title :' , 'exchange-rate'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'from' ); ?>"><?php _e( 'Master Currency :' , 'exchange-rate'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'from' ); ?>" name="<?php echo $this->get_field_name( 'from' ); ?>" type="text" value="<?php echo esc_attr( $from ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'to' ); ?>"><?php _e( 'Currencies Table:' , 'exchange-rate'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'to' ); ?>" name="<?php echo $this->get_field_name( 'to' ); ?>" type="text" value="<?php echo esc_attr( $to ); ?>" />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'Currency_title' ); ?>"><?php _e( 'Currency Symbol :' , 'exchange-rate'); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'Currency_title' ); ?>" name="<?php echo $this->get_field_name( 'Currency_title' ); ?>" type="text" value="<?php echo esc_attr( $Currency_title ); ?>" />
        </p>
        <?php 
    }
    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        delete_transient( 'exchange_rate_widget' );
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['from'] = (! empty($new_instance['from'])) ? strip_tags($new_instance['from']) : '';
        $instance['to'] = (! empty($new_instance['to'])) ? strip_tags($new_instance['to']) : '';
        $instance['Currency_title'] = (! empty($new_instance['Currency_title'])) ? strip_tags($new_instance['Currency_title']) : '';
        return $instance;
    }
} // Class exchange_rate_widget ends here
// Register and load the widget
function exchange_rate_widget_load_widget()
{
    register_widget('exchange_rate_widget');
}
add_action('widgets_init', 'exchange_rate_widget_load_widget');

function exchange_rate_widget_style() {
	wp_enqueue_style( 'gold_price-style', plugins_url( 'css/style.css', dirname(__FILE__) ));
}

add_action( 'wp_enqueue_scripts', 'exchange_rate_widget_style' );