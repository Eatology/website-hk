<?php
/**
 * Template Name: Meet Dietician
 */

// Need to loop for gutenberg the_content()
?>

<section id="dietician-page" class="white-header">
  <h1><?php echo the_title();?></h1>
    <?php
      while ( have_posts() ) : the_post();
        the_content();
      endwhile; // End of the loop.
    ?>
</section>
<script>
    jQuery(document).ready(function(){

        var user_data = localStorage.getItem('user_data');
        if(user_data)
        {
            var parse_user_data= JSON.parse(user_data);

            var gender = parse_user_data['gender'];
            var height = parse_user_data['height'];
            var weight = parse_user_data['weight'];
            var age = parse_user_data['age'];
            var activity_level = parse_user_data['activity_level'];
            var goal = parse_user_data['goal'];
            var preference = parse_user_data['preference'];
            var bmr1 = parse_user_data['bmr1'];
            var bmr2 = parse_user_data['bmr2'];


            // gender
            switch(gender) {
            case "male":
                gender_select = "Male";
                break;
            case "female":
                gender_select = "Female";
                break;
            default:
                gender_select = "Male";
            }

            // activity level
            switch(activity_level) {
            case "sedentary":
                activity_level_select = "Sedentary";
                break;
            case "lightly_active":
                activity_level_select = "Lightly Active";
                break;
            case "active":
                activity_level_select = "Active";
                break;
            case "gym_rat":
                activity_level_select = "Gym Rat";
                break;
            default:
                activity_level_select = "Sedentary";
            }

            // goal
            switch(goal) {
            case "weight_loss":
                goal_select = "Weight Loss";
                break;
            case "healthy_living":
                goal_select = "Healthy Living";
                break;
            case "muscle_building":
                goal_select = "Muscle Building";
                break;
            default:
                goal_select = "Weight Loss";
            }

             // preference
            switch(preference) {
            case "all_time_eat":
                preference_select = "Eat meat all the time";
                break;
            case "ocassional_meat":
                preference_select = "Eat meat ocassionally";
                break;
            case "never_meat":
                preference_select = "Never Meat";
                break;
            case "plant_based":
                preference_select = "Plant Based";
                break;
            default:
                preference_select = "Eat meat all the time";
            }


            // assign the values to form fields
            jQuery('#dietician-page form select#gender').val(gender_select)
            jQuery('#dietician-page form input#height').val(height)
            jQuery('#dietician-page form input#weight').val(weight)
            jQuery('#dietician-page form input#age').val(age);
            jQuery('#dietician-page form select#activity_level').val(activity_level_select);
            jQuery('#dietician-page form select#goal').val(goal_select);
            jQuery('#dietician-page form select#preference').val(preference_select);
            jQuery('#dietician-page form input#bmr').val(bmr1 + ' - ' + bmr2);

        }
    })

</script>
