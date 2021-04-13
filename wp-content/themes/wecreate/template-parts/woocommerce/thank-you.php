<?php
    // check current language and set the page id
    if (ICL_LANGUAGE_CODE == 'zh')
    {
        $post_id        = 1234;
    }
    else {
        $post_id        = 8;
    }
    
    $heading        = get_field('heading'); 
    $description    = get_field('description'); 

    $box1            = get_field('box1');
    $box1_svg        = $box1['svg'];
    $box1_title      = $box1['title'];
    $box1_content    = $box1['content'];

    $box2            = get_field('box2');
    $box2_image      = $box2['image'];

    $box3            = get_field('box3');
    $box3_title      = $box3['title'];
    $box3_content    = $box3['content'];    

    $box4            = get_field('box4');
    $box4_svg        = $box4['svg'];
    $box4_title      = $box4['title'];
    $box4_content    = $box4['content'];    

    $box5            = get_field('box5');
    $box5_image      = $box5['image'];
    $box5_title      = $box5['title'];
    $box5_content    = $box5['content'];        
?>

<h2><?php echo $heading;?></h2>
<p class="acf-p"><?php echo $description;?></p>

<div class="boxes1">
    <div class="box1">
        <?php echo $box1_svg;?>
        <h4><?php echo $box1_title;?></h4>
        <p><?php echo $box1_content;?></p>
    </div>
    <div class="box2">
        <?php
            if ($box2_image) {
                echo '<img src="'.esc_url($box2_image['sizes']['wecreate_blog']).'" alt="'.esc_attr($box2_image['alt']).'" />';
            } 
        ?>
    </div>
    <div class="box3">
        <h4><?php echo $box3_title;?></h4>
        <p><?php echo $box4_content;?></p>
    </div>
</div>

<div class="boxes2">
    <div class="box4">
        <?php echo $box4_svg;?>
        <h4><?php echo $box4_title;?></h4>
        <p><?php echo $box4_content;?></p>        
    </div>
    <div class="box5">
        <div class="column-1">
            <h4><?php echo $box5_title;?></h4>
            <p><?php echo $box5_content;?></p>      
        </div>
        <div class="column-2">
            <?php
                if ($box5_image) {
                    echo '<img src="'.esc_url($box5_image['sizes']['wecreate_search']).'" alt="'.esc_attr($box5_image['alt']).'" />';
                } 
            ?>   
        </div>        
    </div>
</div>