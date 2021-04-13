<header>
    <?php 
        // template acf
        $recipe_svg          = get_field('recipe_svg', 'option'); 
        $time_svg            = get_field('time_svg', 'option'); 
        $serves_svg          = get_field('serves_svg', 'option'); 
        $difficulty_svg      = get_field('difficulty_svg', 'option'); 
        $cals_svg            = get_field('cals_svg', 'option'); 
        $ingredients_text    = get_field('ingredients_text', 'option'); 
        $methods_text        = get_field('methods_text', 'option'); 

        // post acf 
        $serving_time       = get_field('serving_time'); 
        $serves             = get_field('serves'); 
        $difficulty         = get_field('difficulty'); 
        $cals               = get_field('cals'); 
        $ingredients_list   = get_field('ingredients_list'); 
        $methods_list       = get_field('methods_list'); 
                
        $categories = get_the_category();

        $recipe_overview = '<div class="recipe-overview">
            <div class="time">'. $time_svg .'<span class="details">'.$serving_time .'</span></div>
            <div class="serves">'. $serves_svg .'<span class="details">'.$serves .'</span></div>
            <div class="difficulty">'. $difficulty_svg .'<span class="details">'.$difficulty .'</span></div>
            <div class="cals">'. $cals_svg .'<span class="details">'.$cals .'</span></div>
        </div>';

        if (ICL_LANGUAGE_CODE == 'zh') {
            $new_date = esc_html( get_the_date('Y年m月j日'));   
        } else {
            $new_date = esc_html( get_the_date('F j, Y'));
        }                

        if ($recipe_svg) {
            $recipe_svg = '<div class="svg-span-wrapper">'.$recipe_svg. '</div>';
        }
        $image_tag = '';
        $bg_circle = '<div class="work-circle-wrapper">'.svg_circle('work-circle'). '</div>';
        
        if(has_post_thumbnail()):
            $image_tag  = '<div class="img-wrapper"><img src="'.esc_url(get_the_post_thumbnail_url(get_the_ID(),'full')).'" alt="'.__('Recipe Image', 'eatology').'" /></div>';
        endif;


             
                $content_div = '<div class="entry-meta">
        <div class="date">
            <time class="entry-date published" datetime="'. get_the_date( DATE_W3C ).'>" itemprop="datePublished">'. $new_date.'</time>
        </div>
    </div>


    <h1 itemprop="headline">'.get_the_title().'</h1>

    <div class="header-author">
        <span class="category">'. esc_html( $categories[0]->name ) .'</span>

        <span class="byline">
            '. __('Written by', 'eatology').' 
            <span class="author vcard" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
                    <span itemprop="name">'. esc_html( get_the_author() ).'</span>
            </span>
        </span>
    </div>';
    ?>


    <div class="about-detail-row">
        <?php
            echo '<div class="svg-wrapper">'.$bg_circle  . $recipe_svg.'</div>';
            echo '<div class="about-detail-wrapper">';
            echo $image_tag;
            echo '<div class="content-wrapper">';
            echo $content_div; 
            include "content.php";
            echo $recipe_overview;
            echo '</div>';
            echo '</div>';                          
        ?>
    </div>


    <div class="recipe-details">
        <div class="ingredients">
            <h3><?php echo $ingredients_text ;?></h3>
            <?php echo $ingredients_list;?>
        </div>
        <div class="methods">
            <h3><?php echo $methods_text ;?></h3>
            <?php echo $methods_list;?>
        </div>
    </div>
</header>