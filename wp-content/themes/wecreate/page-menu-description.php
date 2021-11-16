<?php
/**
 * Template Name: Menu Description
 */

// Need to loop for gutenberg the_content()
?>

<section id="menu-description-page" class="white-header">
  <!-- <h1><?php echo the_title();?></h1> -->
    <?php
    //   while ( have_posts() ) : the_post();
    //     the_content();
    //   endwhile;
    ?>

    <div class="main-container">

        <a href="<?= get_bloginfo('url') ?>/menu" class="back-top-menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 11.4477 4.44772 11 5 11H19C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13H5C4.44772 13 4 12.5523 4 12Z" fill="#948E97"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7071 4.29289C13.0976 4.68342 13.0976 5.31658 12.7071 5.70711L6.41421 12L12.7071 18.2929C13.0976 18.6834 13.0976 19.3166 12.7071 19.7071C12.3166 20.0976 11.6834 20.0976 11.2929 19.7071L4.29289 12.7071C3.90237 12.3166 3.90237 11.6834 4.29289 11.2929L11.2929 4.29289C11.6834 3.90237 12.3166 3.90237 12.7071 4.29289Z" fill="#948E97"/>
            </svg>
            Back to Menu
        </a>

        <div class="main-wrapper">

            <div class="photo-image"  id="menuImage">
                <picture>
                    <img src="https://www.fillmurray.com/640/360" alt="">
                </picture>
            </div>

            <div class="layout">
                <span class="category" id="menuCategory">Ketogenic diet light</span>
                <h1 id="menuTitle">Creamy Chive Chicken</h1>
                <span class="description"><span id="menuDescription"> Lemony Rice & Roasted Broccoli </span> • <i><span id="menuType">Breakfast</span></i></span>

                <div class="rating">
                    <ul class="star-rating" id="menuRating">
                        <li class="star-full"></li>
                        <li class="star-full"></li>
                        <li class="star-full"></li>
                        <li class="star-full"></li>
                        <li class="star-half"></li>
                    </ul>
                    <span id="menuRatingValue"></span>
                </div>

                <p class="long-description" id="menuDetails">Tangy lemon juice, punchy dijon, sour cream, and fresh chives mingle to create a condiment that truly takes seared chicken to the next level. On the side, there’s zesty lemon rice and a green salad that’s bursting with crispy pieces of apple... because you only deserve the best!</p>

                <div class="allergies-wrap" id="menuAllergens">
                    
                </div>

                <div class="calories-wrap">
                    <span class="calories-label">NUMBER OF CALORIES:</span>
                    <input type="radio" id="tab1" name="tab" checked>
                    <label for="tab1">1200 <span>KCAL</span></label>
                    <input type="radio" id="tab2" name="tab">
                    <label for="tab2">1500 <span>KCAL</span></label>
                    <input type="radio" id="tab3" name="tab">
                    <label for="tab3">1800 <span>KCAL</span></label>
                    <input type="radio" id="tab4" name="tab">
                    <label for="tab4">2200 <span>KCAL</span></label>

                    <div class="description-container">

                        <div class="grams" id="d1">
                            <ul>
                                <li>
                                    <span>32g</span>
                                    <p>PROTEIN</p>
                                </li>
                                <li>
                                    <span>40g</span>
                                    <p>CARBS</p>
                                </li>
                                <li>
                                    <span>12g</span>
                                    <p>FAT</p>
                                </li>
                                <li>
                                    <span>350mg</span>
                                    <p>SODIUM</p>
                                </li>
                            </ul>
                        </div>

                        <div class="grams" id="d2">
                            <ul>
                                <li>
                                    <span>36g</span>
                                    <p>PROTEIN</p>
                                </li>
                                <li>
                                    <span>43g</span>
                                    <p>CARBS</p>
                                </li>
                                <li>
                                    <span>16g</span>
                                    <p>FAT</p>
                                </li>
                                <li>
                                    <span>500mg</span>
                                    <p>SODIUM</p>
                                </li>
                            </ul>
                        </div>

                        <div class="grams" id="d3">
                            <ul>
                                <li>
                                    <span>40g</span>
                                    <p>PROTEIN</p>
                                </li>
                                <li>
                                    <span>48g</span>
                                    <p>CARBS</p>
                                </li>
                                <li>
                                    <span>20g</span>
                                    <p>FAT</p>
                                </li>
                                <li>
                                    <span>800mg</span>
                                    <p>SODIUM</p>
                                </li>
                            </ul>
                        </div>

                        <div class="grams" id="d4">
                            <ul>
                                <li>
                                    <span>46g</span>
                                    <p>PROTEIN</p>
                                </li>
                                <li>
                                    <span>52g</span>
                                    <p>CARBS</p>
                                </li>
                                <li>
                                    <span>24g</span>
                                    <p>FAT</p>
                                </li>
                                <li>
                                    <span>900mg</span>
                                    <p>SODIUM</p>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="weekly-dish">
            <div class="menu-item-wrap">
                <span class="menu-item__header">Other dishes for the week</span>
                <div class="menu-item__row" id="otherDishes">
                </div>
            </div>
        </div>

    </div>

</section>