<?php
/*
Plugin Name:	Horizontal Tabs
Plugin URI:
Description:	Show posts from 3 selected categories 
Version:	1.0
Author:
Author URI:		
Licence:	GPLv2		
 * 
*/


class horizontal_tabs_widget extends WP_Widget{
    function horizontal_tabs_widget (){
        $widgets_ops = array(
            'classname' => 'horizontal_tabs_widget',
            'descrption' => 'Create Horizontal Tab Widgets'
        );
        
        $this->WP_Widget('horizontal_tabs_widget', 'Horizontal Tab Widgets',$widgets_ops);
    }
    
    public function form($instance){
        $category1 = $instance['category1'];
        $category2 = $instance['category2'];
        $category3 = $instance['category3'];
        
        $image_width1 = $instance['image_width1'];
        $image_height1 = $instance['image_height1'];
                
        $number_of_categories = $instance['number_of_categories'];                
                
        $categories = get_categories();
        ?>
    

<p>Select category: <select name="<?php echo $this->get_field_name('category1');?>">
    <?php foreach ($categories as $category) { ?>
        <option value='<?php echo $category->cat_ID;?>' <?php selected($category1, $category->cat_ID)?> ><?php echo $category->name;?></option>       
                <?php }?>
    </select>
    </p>
    
<p>Select category: <select name="<?php echo $this->get_field_name('category2');?>">
    <?php foreach ($categories as $category) { ?>
        <option value='<?php echo $category->cat_ID;?>' <?php selected($category2, $category->cat_ID)?> ><?php echo $category->name;?></option>       
                <?php }?>
    </select>
    </p>
    
 <p>Select category: <select name="<?php echo $this->get_field_name('category3');?>">
    <?php foreach ($categories as $category) { ?>
        <option value='<?php echo $category->cat_ID;?>' <?php selected($category3, $category->cat_ID)?> ><?php echo $category->name;?></option>       
                <?php }?>
    </select>
  </p>   
  
  
  <p>Set image width: <input  type="text" name="<?php echo $this->get_field_name('image_width1');?>" value="<?php echo esc_attr($image_width1);?>"/></p>
  <p>Set image height: <input  type="text" name="<?php echo $this->get_field_name('image_height1');?>" value="<?php echo esc_attr($image_height1);?>"/></p>
  <p>Set number of posts per tab: <input  type="text" name="<?php echo $this->get_field_name('number_of_categories');?>" value="<?php echo esc_attr($number_of_categories);?>"/></p>
  
<?php
    }
    
    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['category1'] = strip_tags($new_instance['category1']);
        $instance['category2'] = strip_tags($new_instance['category2']);        
        $instance['category3'] = strip_tags($new_instance['category3']); 
        $instance['image_width1'] = strip_tags($new_instance['image_width1']);        
        $instance['image_height1'] = strip_tags($new_instance['image_height1']);        
        $instance['number_of_categories'] = strip_tags($new_instance['number_of_categories']);        
      
        return $instance;
    }
    
    function widget($args,$instance){
        extract($args);
        
        $category1 = empty ($instance['category1'])? 'Uncategorized' : $instance['category1'];
        $category2 = empty ($instance['category2'])? 'Uncategorized' : $instance['category2'];
        $category3 = empty ($instance['category3'])? 'Uncategorized' : $instance['category3']; 
        $image_width1 = empty ($instance['image_width1'])? 200 : $instance['image_width1'];        
        $image_height1 = empty ($instance['image_height1'])? 200 : $instance['image_height1'];   
        $number_of_categories1 = empty ($instance['number_of_categories'])? 4 : $instance['number_of_categories'];   

        echo $before_widget;
      
        $this->set_thumb_images($image_width1,$image_height1);        
        $number_of_tabs = $this->get_arbitary_array(3);
        $this->create_tabs($number_of_tabs[3]);
        ?>
 
<div id="tabs-<?php echo $number_of_tabs[3]?>">
  <ul>
    <li><a href="#tabs-<?php echo $number_of_tabs[0]?>"><?php echo get_cat_name($category1);?></a></li>
    <li><a href="#tabs-<?php echo $number_of_tabs[1]?>"><?php echo get_cat_name($category2);?></a></li>
    <li><a href="#tabs-<?php echo $number_of_tabs[2]?>"><?php echo get_cat_name($category3);?></a></li>
  </ul>
    	
	
<div id='tabs-<?php echo $number_of_tabs[0]?>'>
<?php
$this->get_htabs_post($category1,$number_of_categories1);
?>
</div>

<div id='tabs-<?php echo $number_of_tabs[1]?>'>
<?php
$this->get_htabs_post($category2,$number_of_categories1);
?>
</div>

<div id='tabs-<?php echo $number_of_tabs[2]?>'>
<?php
$this->get_htabs_post($category3,$number_of_categories1);
?>
</div>
    
</div>
<?php       
        echo $after_widget;

    }    
    
    function get_htabs_post($category,$number_of_categories){
// The Query
        $args = array (
	'post_type'              => 'post',
	'post_status'            => 'publish',
	'cat' 		             => $category,
	'posts_per_page'         => $number_of_categories,
	'order'                  => 'DESC',
	'orderby'                => 'date',
);
        
$htabs = new WP_Query($args);
        
        if($htabs->have_posts()):?> 
<?php while ($htabs->have_posts()): $htabs->the_post(); ?>
<div class='custom_htabs'>
        <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink();?>"><?php the_post_thumbnail();?></a>
    <?php endif?>
	<div class='custom_htabs_title'>
	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	</div>
</div>
<?php
endwhile; 
wp_reset_postdata();
else:
    echo 'No posts found';
endif;
    }

    function set_thumb_images($image_width,$image_height){ ?>
        <script>
            jQuery(document).ready(function(){
                jQuery('.custom_htabs img').width(<?php echo $image_width?>);
                jQuery('.custom_htabs img').height(<?php echo $image_height?>);
            });
        </script>		
  <?php
        }

    function create_tabs($tab_id){ ?>
        <script>
            jQuery(document).ready(function(){
            jQuery('#tabs-<?php echo $tab_id?>').tabs({
            collapsible: true
});
});
        </script>		
<?php    }
        
	function get_arbitary_array($array_size){
		$arbitary_array = array();
		
		for($i = 0; $i < $array_size+1; $i++)
				$arbitary_array[$i] = mt_rand(0,10000);
	
		  return $arbitary_array;
	}
	
}

add_action('widgets_init','horizontal_tabs_register_widget');

function horizontal_tabs_register_widget(){
    register_widget('horizontal_tabs_widget');
}

function horizontalTabsScript(){
    wp_enqueue_script('jquery-ui-tabs');
}

add_action('wp_enqueue_scripts', 'horizontalTabsScript');

function horizontalTabsCss(){

	wp_register_style('jquery-ui-structure', plugins_url('css/jquery-ui.structure.css',__FILE__));
    wp_enqueue_style('jquery-ui-structure');
	
    wp_register_style('jquery-ui-smoothness', plugins_url('css/jquery-ui.smoothness.css',__FILE__));
    wp_enqueue_style('jquery-ui-smoothness');
    
    wp_register_style('htabsCss1', plugins_url('css/style.css',__FILE__));
    wp_enqueue_style('htabsCss1');
}

add_action('wp_enqueue_scripts', 'horizontalTabsCss');

?>