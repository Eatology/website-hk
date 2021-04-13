<?php
/**
 * Template Name: FAQs
 */

// Need to loop for gutenberg the_content()
?>
<section id="faqs" class="half-page">
	<h1 style="height: 0; overflow: hidden; margin: 0"><?php echo get_the_title() ?></h1>
    <?php
        $questions_array = array();
        $reg_string = '/[^a-zA-Z0-9]/';
        if( have_rows('faqs') ): while ( have_rows('faqs') ) : the_row();
            // get section title
            $title = get_sub_field('title');
            if( have_rows('questions') ):
                $question_array = array();
                // get questions and answers for each section
                while ( have_rows('questions') ) : the_row();
                    $question   = get_sub_field('question');
                    $answer     = get_sub_field('answer');
                    array_push($question_array, array($question, $answer));
                endwhile;
                array_push($questions_array, array($title, $question_array));
            endif;
        endwhile; endif;

        // faq nav
        echo '<nav class="faq-nav">';
        foreach($questions_array as $questions):
            echo '<a href="#'.preg_replace($reg_string,'', strtolower($questions[0])).'">'.strip_tags($questions[0]).'</a>';
        endforeach;
        echo '</nav></section>';

        // title and question and answers loop
        foreach($questions_array as $key => $questions):
			echo '<section class="question-group">';
			echo '<h2 class="h1" id="'.preg_replace($reg_string,'', strtolower($questions[0])).'">'.strip_tags($questions[0]).'</h2>';
            foreach($questions[1] as $question_group):
                echo '<div class="question-group__answer">';
                echo '<a href="#"><h3 class="h4">'.strip_tags($question_group[0]).'</h3></a>';
                echo '<div class="question-content">'.$question_group[1].'</div>';
                echo '</div>';
            endforeach;
            echo '</section>';
        endforeach;
    ?>

