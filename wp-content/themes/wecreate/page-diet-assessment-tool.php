<?php

/**
 * Template Name: Diet Assessment Tool Template
 */
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/resources/scripts/circle-progress.min.js"></script>

<script>
    // script to add the filter product select option for country and region
    jQuery(document).ready(function($) {
        // initialize select2 for diet assessment tools select
        jQuery('#height_selector').select2({
            width: '100%'
        });
        jQuery('#weight_selector').select2({
            width: '100%'
        });
        jQuery('#age_selector').select2({
            width: '100%'
        });
    });
</script>

<!-- loading icon here -->
<div class="eat_loading">Loading&#8230;</div>
<section id="diet_assessment-page" class="white-header">
    <div class="diet-assessment-wrapper">
        <div class="diet_assessment-image-wrapper">
            <div class="diet_assessment-image diet_assessment-image-1" id="diet_assessment-image-1">
                <?php $image = get_field('diet_assessment_image_1'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover']; ?>" alt="<?php the_field('diet_assessment_title'); ?>" />
            </div>

            <div class="diet_assessment-image diet_assessment-image-2" id="diet_assessment-image-2">
                <?php $image = get_field('diet_assessment_image_2'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover']; ?>" alt="<?php the_field('diet_assessment_title'); ?>" />
            </div>

            <div class="diet_assessment-image diet_assessment-image-3" id="diet_assessment-image-3">
                <?php $image = get_field('diet_assessment_image_3'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover']; ?>" alt="<?php the_field('diet_assessment_title'); ?>" />
            </div>

            <div class="diet_assessment-image diet_assessment-image-4" id="diet_assessment-image-4">
                <?php $image = get_field('diet_assessment_image_4'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover']; ?>" alt="<?php the_field('diet_assessment_title'); ?>" />
            </div>
            <div class="diet_assessment-image diet_assessment-image-5" id="diet_assessment-image-5">
                <?php $image = get_field('diet_assessment_image_5'); ?>
                <img src="<?php echo $image['sizes']['wecreate_half_cover']; ?>" alt="<?php the_field('diet_assessment_title'); ?>" />
            </div>
        </div>

        <div class="diet_assessment-form">
            <div class="form_wrapper">
                <div class="tool_title">
                    <h1><?php echo __('Find your meal', 'eatology'); ?> </h1>
                </div>


                <div class="tab-header">
                    <a class="tablinks active" data-ids="step1"><?php echo __('Step 1', 'eatology'); ?> </a>
                    <a class="tablinks" data-ids="step2"><?php echo __('Step 2', 'eatology'); ?></a>
                    <a class="tablinks" data-ids="step3"><?php echo __('Step 3', 'eatology'); ?></a>
                    <a class="tablinks" data-ids="step4"><?php echo __('Step 4', 'eatology'); ?></a>
                </div>


                <div id="step1" class="tabcontent">
                    <div class="first_section">
                        <h3>
                            <?php echo __('What is your gender?', 'eatology'); ?>
                        </h3>

                        <div class="male_female_wrapper">

                            <div class="radio-group">
                                <div class='radio male selected' data-value="male">
                                    <p class="icon">
                                        <span class="custom-icon icon-male"></span>
                                    </p>
                                    <p>
                                        <?php echo __('Male', 'eatology'); ?>
                                    </p>
                                </div>
                                <div class='radio female' data-value="female">
                                    <p class="icon">
                                        <span class="custom-icon icon-female"></span>
                                    </p>
                                    <p>
                                        <?php echo __('Female', 'eatology'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="second_section">
                        <h3>
                            <?php echo __('Personal Information', 'eatology'); ?>
                        </h3>
                        <div class="personal_info_wrapper">

                            <div class="height_wrapper">


                                <label for="height_selector">
                                    <?php echo __('Height (cm)', 'eatollogy');

                                    $height = '';
                                    for ($h = 120; $h <= 220; $h++) {
                                        $height .= '<option value="' . $h . '">' . __($h, 'eatology') . '</option>';
                                    }
                                    ?>

                                    <select class="js-example-basic-single js-states form-control" id="height_selector">

                                        <option value=""><?php echo __('- - -', 'eatology'); ?> </option>
                                        <?php echo $height; ?>

                                    </select>
                                </label>

                            </div>

                            <div class="weight_wrapper">

                                <label for="weight_selector">
                                    <?php echo __('Weight (kg)', 'eatollogy');

                                    $weight = '';
                                    for ($w = 38; $w <= 181; $w++) {
                                        $weight .= '<option value="' . $w . '">' . __($w, 'eatology') . '</option>';
                                    }
                                    ?>

                                    <select class="js-example-basic-single js-states form-control" id="weight_selector">
                                        <option value=""><?php echo __('- - -', 'eatology'); ?> </option>
                                        <?php echo $weight; ?>
                                    </select>
                                </label>

                            </div>
                            <div class="age_wrapper">

                                <label for="age_selector">
                                    <?php echo __('Age (yrs)', 'eatollogy');

                                    $age = '';
                                    for ($a = 12; $a <= 75; $a++) {
                                        $age .= '<option value="' . $a . '">' . __($a, 'eatology') . '</option>';
                                    }
                                    ?>
                                    <select class="js-example-basic-single js-states form-control" id="age_selector">
                                        <option value=""><?php echo __('- - -', 'eatology'); ?> </option>
                                        <?php echo $age; ?>
                                    </select>
                                </label>

                            </div>
                        </div>

                    </div>
                    <div class="third_section">
                        <button class="next-button" data-id="step2" data-image="diet_assessment-image-2">
                            <p>
                                <?php echo __('Next', 'eatology'); ?>
                            </p>
                            <p class="icon_after icon-chevron-up"></p>
                        </button>
                    </div>
                </div>


                <div id="step2" class="tabcontent">
                    <div class="first_section">
                        <h3>
                            <?php echo __('What is your activity level?', 'eatology'); ?>
                        </h3>

                        <div class="activity_level_wrapper">

                            <div class="radio-group">
                                <!-- sedentary -->
                                <div class='radio sedentary selected' data-value="sedentary">
                                    <p class="icon">
                                        <span class="custom-icon icon-sedentary"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Just Starting', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('You want to get out of your "little-to-no exercise" and "have an office job" habit', 'eatology'); ?>
                                    </p>
                                </div>

                                <!-- lightly active -->
                                <div class='radio lightly_active' data-value="lightly_active">
                                    <p class="icon">
                                        <span class="custom-icon icon-light-active"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Lightly Active', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('You perform light exercise 1-3 times per week and have a busy lifestyle that requires you to walk frequently', 'eatology'); ?>
                                    </p>
                                </div>

                                <!-- active -->
                                <div class='radio active' data-value="active">
                                    <p class="icon">
                                        <span class="custom-icon icon-active"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Active', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('You engage in moderately intesive physical activity 3-4 times per week', 'eatology'); ?>
                                    </p>
                                </div>

                                <!-- gym rat -->
                                <div class='radio gym_rat' data-value="gym_rat">
                                    <p class="icon">
                                        <span class="custom-icon icon-gym-rat"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Gym Rat', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('You engage vigorous physical activity 5-7 times per week.', 'eatology'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="second_section">
                        <button class="prev-button" data-id="step1" data-image="diet_assessment-image-1">
                            <p class="icon_before icon-chevron-up"></p>
                            <p>
                                <?php echo __('Prev', 'eatology'); ?>
                            </p>
                        </button>

                        <button class="next-button" data-id="step3" data-image="diet_assessment-image-3">
                            <p>
                                <?php echo __('Next', 'eatology'); ?>
                            </p>
                            <p class="icon_after icon-chevron-up"></p>
                        </button>
                    </div>
                </div>



                <div id="step3" class="tabcontent">
                    <div class="first_section">
                        <h3>
                            <?php echo __('What is your goal?', 'eatology'); ?>
                        </h3>

                        <div class="goal_wrapper">

                            <div class="radio-group">
                                <!-- weight_loss -->
                                <div class='radio weight_loss selected' data-value="weight_loss">
                                    <p class="icon">
                                        <span class="custom-icon icon-weight-loss"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Fat/Weight Loss', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('We will help you to achieve your weight loss goals by providing less than 90% of your total daily calorie expenditure.', 'eatology'); ?>
                                    </p>
                                </div>

                                <!-- healthy living -->
                                <div class='radio healthy_living' data-value="healthy_living">
                                    <p class="icon">
                                        <span class="custom-icon icon-healthy-living"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Healthy Living', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('To maintain a healthy weight, we provide you with enough calories to cover your basal metabolic rate and physical activities.', 'eatology'); ?>
                                    </p>
                                </div>

                                <!-- muscle building -->
                                <div class='radio muscle_building' data-value="muscle_building">
                                    <p class="icon">
                                        <span class="custom-icon icon-muscle-building"></span>
                                    </p>
                                    <p class="title">
                                        <?php echo __('Muscle Building', 'eatology'); ?>
                                    </p>
                                    <p class="desc">
                                        <?php echo __('To fuel your active lifestyle and training, our meals consist of adequate amounts of lean protein to ensure you maintain a positive energy balance.', 'eatology'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="second_section">
                        <button class="prev-button" data-id="step2" data-image="diet_assessment-image-2">
                            <p class="icon_before icon-chevron-up"></p>
                            <p>
                                <?php echo __('Prev', 'eatology'); ?>
                            </p>
                        </button>

                        <button class="next-button" data-id="step4" data-image="diet_assessment-image-4">
                            <p>
                                <?php echo __('Next', 'eatology'); ?>
                            </p>
                            <p class="icon_after icon-chevron-up"></p>
                        </button>
                    </div>
                </div>

                <div id="step4" class="tabcontent">
                    <div class="first_section">
                        <h3>
                            <?php echo __('What is your preference?', 'eatology'); ?>
                        </h3>

                        <div class="preference_wrapper">

                            <div class="radio-group">
                                <!-- eat meat all the time -->
                                <div class='radio all_time_meat selected' data-value="all_time_meat">
                                    <!-- <p class="icon">
                                        <span class="custom-icon icon-icon-incredibly-nutritious"></span>
                                    </p> -->
                                    <p class="title">
                                        <?php echo __('Eat meat all the time', 'eatology'); ?>
                                    </p>
                                    <!-- <p class="desc">
                                        <?php //echo __('We will help you to achieve your weight loss goals by providing less than 90% of your total daily calorie expenditure.', 'eatology'); 
                                        ?>
                                    </p> -->
                                </div>

                                <!-- eat meat ocassionally -->
                                <div class='radio ocassional_meat' data-value="ocassional_meat">
                                    <!-- <p class="icon">
                                        <span class="custom-icon icon-icon-incredibly-nutritious"></span>
                                    </p> -->
                                    <p class="title">
                                        <?php echo __('Eat meat ocassionally', 'eatology'); ?>
                                    </p>
                                    <!-- <p class="desc">
                                        <?php // echo __('To maintain a healthy weight, we provide you with enough calories to cover your basal metabolic rate and physical activities.', 'eatology'); 
                                        ?>
                                    </p> -->
                                </div>

                                <!-- never meat -->
                                <div class='radio never_meat' data-value="never_meat">
                                    <!-- <p class="icon">
                                        <span class="custom-icon icon-icon-incredibly-nutritious"></span>
                                    </p> -->
                                    <p class="title">
                                        <?php echo __('Never Meat', 'eatology'); ?>
                                    </p>
                                    <!-- <p class="desc">
                                        <?php// echo __('To maintain a healthy weight, we provide you with enough calories to cover your basal metabolic rate and physical activities.', 'eatology'); ?>
                                    </p> -->
                                </div>

                                <!-- plant based -->
                                <div class='radio plant_based' data-value="plant_based">
                                    <!-- <p class="icon">
                                        <span class="custom-icon icon-icon-incredibly-nutritious"></span>
                                    </p> -->
                                    <p class="title">
                                        <?php echo __('Plant Based', 'eatology'); ?>
                                    </p>
                                    <!-- <p class="desc">
                                        <?php// echo __('To maintain a healthy weight, we provide you with enough calories to cover your basal metabolic rate and physical activities.', 'eatology'); ?>
                                    </p> -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="second_section">
                        <button class="prev-button" data-id="step3" data-image="diet_assessment-image-3">
                            <p class="icon_before icon-chevron-up"></p>
                            <p>
                                <?php echo __('Prev', 'eatology'); ?>
                            </p>
                        </button>

                        <button class="next-button" data-id="submit" data-image="diet_assessment-image-5">
                            <p>
                                <?php echo __('Submit', 'eatology'); ?>
                            </p>
                        </button>
                    </div>
                </div>
            </div>

            <div class="result_wrapper">
                <div class="result_header">
                    <h2><?php echo __('Results', 'eatology'); ?></h2>
                </div>
                <div class="result_body">
                    <div class="">
                        <div class="circle" id="circle-b">
                            <p id="calorie_val"></p>
                            <p id="kcal_text"></p>
                        </div>
                    </div>
                    <div class="result_content">

                        <?php

                        if (have_rows('result_content')) : while (have_rows('result_content')) : the_row(); ?>
                                <h3><?php echo the_sub_field('title'); ?></h3>
                                <p>
                                    <?php echo the_sub_field('description'); ?>
                                </p>
                        <?php endwhile;
                        endif;
                        ?>

                    </div>
                </div>
                <div class="recommended_products" id="recommended_products">

                </div>
                <div class="meet_dietician_wrapper">
                    <a href="<?php echo get_field('footer_button_url'); ?>"><?php echo get_field('footer_button_text'); ?> </a>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>