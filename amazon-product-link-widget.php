<?php 
/*
Plugin Name: Amazon Product Link Widget
Plugin URI: http://www.BlogsEye.com
Description: Displays a random Amazon link box from a list of asin product ids.
Author: Keith P. Graham
Version: 1.1
Author URI: http://www.BlogsEye.com
*/

/**
 * kpg_amazon_widget Class
 */
 
 
 /*
 
 class My_Widget extends WP_Widget {
	function My_Widget() {
		// widget actual processes
	}

	function form($instance) {
		// outputs the options form on admin
	}

	function update($new_instance, $old_instance) {
		// processes widget options to be saved
	}

	function widget($args, $instance) {
		// outputs the content of the widget
	}

}
register_widget('My_Widget');
*/
class kpg_amazon_widget extends WP_Widget {
    // private default list
	private $defaultlist=array(
			"B004GEB31Q",
			"1456336584",
			"B004C05DTC",
			"B004LDLBDW",
			"1463529961"
	);
    function kpg_amazon_widget() {
        parent::WP_Widget(false, $name = 'Amazon Product Link Widget');
		/** constructor */
   }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
		extract($args);
	    $title=$instance['title'];
		$affid=$instance['affid'];
		$list=$instance['list'];
		// set the defaults
		if (!isset($title)) $title='Amazon';
		if (!isset($affid)||empty($affid)) $affid='thenewjt30page';
		if (!isset($list)||!is_array($list)||count($list)==0) {
			$list=$this->defaultlist;
		}	
        $title = apply_filters('widget_title', $instance['title']);
		if (trim($affid)=='') $affid='thenewjt30page';
		$asin=$list[0];
		if (count($list)>1) 
			$asin=$list[array_rand($list)];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
						<p align="center">
						<iframe src="http://rcm.amazon.com/e/cm?lt1=_blank&bc1=000000&IS2=1&bg1=FFFFFF&fc1=000000&lc1=0000FF&t=<?php echo $affid; ?>&o=1&p=8&l=as1&m=amazon&f=ifr&md=10FE9736YVPPT7A0FBG2&asins=<?php echo $asin; ?>" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>
						</p>              
		<?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['affid'] = strip_tags($new_instance['affid']);
		$ulist=$new_instance['ulist'];
		$list=$this->defaultlist;
		if (isset($ulist)&&!empty($ulist)) {
			// split the ulist out and make it into a list

			$list=explode("\n",$ulist);
			for ($j=count($list)-1;$j>=0;$j--) {
				$list[$j]=trim($list[$j]);
				if ($list[$j]=='') unset($list[$j]);
			}
		}
		if (!isset($list)||!is_array($list)||count($list)==0) {
			$list=$this->defaultlist;
		} 
		$instance['list']=$list;
		return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		if(empty($instance)||!is_array($instance)) {
			$instance=array();
		}
		if (!array_key_exists('affid',$instance)) {
			// check to see if they might have used the old method
			$options2 = (array) get_option('kpg_amazon_widget');
			if (!empty($options2)&&is_array($options2)&&array_key_exists('affid',$options2)) {
				$affid=$instance['affid'];
				$instance['list']=$options2['alist'];
				$instance['affid']=$options2['affid'];
				$instance['title']=$options2['title'];
			}
		}
        $title = esc_attr($instance['title']);
		$affid = esc_attr($instance['affid']);
		$list = $instance['list'];
		if (!isset($affid)||empty($affid)) $affid='thenewjt30page';
		if (!isset($list)||!is_array($list)||count($list)==0) $list=$this->defaultlist;

       ?>
         <p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		<label for="<?php echo $this->get_field_id('affid'); ?>"><?php _e('Affiliate ID:'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id('affid'); ?>" name="<?php echo $this->get_field_name('affid'); ?>" type="text" value="<?php echo $affid; ?>" />
		<label for="<?php echo $this->get_field_id('list'); ?>">Asin List</label> 
		<textarea style="width: 200px;height:300px;"  name="<?php echo $this->get_field_name('ulist'); ?>"><?php 
			if ($list!=null&& count($list)>0) {
				for ($j=0;$j<count($list);$j++) {
					echo "$list[$j]\n";
				}
			}
		?></textarea>
        </p>
        <?php 
    }

} // class kpg_amazon_widget

// register kpg_amazon_widget widget
add_action('widgets_init', create_function('', 'return register_widget("kpg_amazon_widget");'));

?>