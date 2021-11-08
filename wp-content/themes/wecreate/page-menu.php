<?php
/**
 * Template Name: Menu
 */

// Need to loop for gutenberg the_content()
?>

<section id="menu-page" class="white-header">
  <!-- <h1><?php echo the_title();?></h1> -->
    <?php
    //   while ( have_posts() ) : the_post();
    //     the_content();
    //   endwhile;
    ?>

    <div class="main-container">

        <div class="main-wrapper">
            <div class="js-side-nav-bar side-nav">
                <aside class="js-side-nav">
                    <a href="javascript:void(0);" class="js-close-side-nav close-side-nav">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M24.9426 7.05721C25.4633 7.57791 25.4633 8.42213 24.9426 8.94283L8.94265 24.9428C8.42195 25.4635 7.57773 25.4635 7.05703 24.9428C6.53633 24.4221 6.53633 23.5779 7.05703 23.0572L23.057 7.05721C23.5777 6.53651 24.4219 6.53651 24.9426 7.05721Z" fill="#4A0D66"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.05703 7.05721C7.57773 6.53651 8.42195 6.53651 8.94265 7.05721L24.9426 23.0572C25.4633 23.5779 25.4633 24.4221 24.9426 24.9428C24.4219 25.4635 23.5777 25.4635 23.057 24.9428L7.05703 8.94283C6.53633 8.42213 6.53633 7.57791 7.05703 7.05721Z" fill="#4A0D66"/>
                        </svg>
                    </a>
                    <div class="js-side-nav-wrap side-nav-wrap">
                        <span class="menu-header">Diet</span>
                        <ul>
                            <li><a class="is-active" href="javascript:void(0);" data-link="11">Ketogenic Light</a></li>
                            <li><a href="javascript:void(0);" data-link="19">Vegan</a></li>
                            <li><a href="javascript:void(0);" data-link="16">Vegetarian</a></li>
                        </ul>
                        <span class="menu-header">Healthy</span>
                        <ul>
                            <li><a href="javascript:void(0);" data-link="157">F45 Challenge</a></li>
                            <li><a href="javascript:void(0);" data-link="5">GLUTEN-FREE LOW CARB</a></li>
                            <li><a href="javascript:void(0);" data-link="8">Mediterranean</a></li>
                            <li><a href="javascript:void(0);" data-link="14">OPTIMAL PERFORMANCE</a></li>
                        </ul>
                        <span class="menu-header">Lifestyle</span>
                        <ul>
                            <li><a href="javascript:void(0);" data-link="1">Asian</a></li>
                            <li><a href="javascript:void(0);" data-link="8">Lighter Delights</a></li>
                            <li><a href="javascript:void(0);" data-link="25">Paleo</a></li>
                        </ul>
                    </div>
                </aside>
            </div>

            <div class="layout">

                <div class="js-calendar-header calendar-header--mobile">
                    <div class="calendar-header">
                        <a href="javascript:void(0);" class="js-prev-arrow prev-arrow menu-calendar">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 5.29289C8.68342 4.90237 9.31658 4.90237 9.70711 5.29289L15.7071 11.2929C16.0976 11.6834 16.0976 12.3166 15.7071 12.7071L9.70711 18.7071C9.31658 19.0976 8.68342 19.0976 8.29289 18.7071C7.90237 18.3166 7.90237 17.6834 8.29289 17.2929L13.5858 12L8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289Z" fill="#4A0D66"/>
                            </svg>
                        </a>
                        <span class="calendar-title1">September 20 - 25</span>
                        <a href="javascript:void(0);" class="js-next-arrow next-arrow menu-calendar">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 5.29289C8.68342 4.90237 9.31658 4.90237 9.70711 5.29289L15.7071 11.2929C16.0976 11.6834 16.0976 12.3166 15.7071 12.7071L9.70711 18.7071C9.31658 19.0976 8.68342 19.0976 8.29289 18.7071C7.90237 18.3166 7.90237 17.6834 8.29289 17.2929L13.5858 12L8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289Z" fill="#4A0D66"/>
                            </svg>
                        </a>
                    </div>
                    <div class="calendar-dates">
                        <ul id="calendarDates">
                            <li><a class="calender-link is-active" data-link="#Monday" href="javascript:void(0);">M</a></li>
                            <li><a class="calender-link" data-link="#Tuesday" href="javascript:void(0);">T</a></li>
                            <li><a class="calender-link" data-link="#Wednesday" href="javascript:void(0);">W</a></li>
                            <li><a class="calender-link" data-link="#Thursday" href="javascript:void(0);">T</a></li>
                            <li><a class="calender-link" data-link="#Friday" href="javascript:void(0);">F</a></li>
                            <li><a class="calender-link" data-link="#Saturday" href="javascript:void(0);">S</a></li>
                        </ul>
                    </div>
                </div>

                <a href="javascript:void(0);" class="js-mobile-sidemenu-nav mobile-sidemenu-nav">Select a meal plan
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 11.4477 4.44772 11 5 11H19C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13H5C4.44772 13 4 12.5523 4 12Z" fill="#4A0D66"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2929 4.29289C11.6834 3.90237 12.3166 3.90237 12.7071 4.29289L19.7071 11.2929C20.0976 11.6834 20.0976 12.3166 19.7071 12.7071L12.7071 19.7071C12.3166 20.0976 11.6834 20.0976 11.2929 19.7071C10.9024 19.3166 10.9024 18.6834 11.2929 18.2929L17.5858 12L11.2929 5.70711C10.9024 5.31658 10.9024 4.68342 11.2929 4.29289Z" fill="#4A0D66"/>
                    </svg>
                </a>
                
                <div class="layout__header">
                    <h1 class="header-h1">Ketogenic Diet Light</h1>
                    <p>With a generous amount of protein and fat and a controlled amount of carbohydrates, the keto diet puts uses your body fat as an energy source, causing lasting weight loss results.</p>
                </div>

                <div class="layout__body">
                    <div class="js-calendar-header calendar-header--desktop">
                        <div class="calendar-header">
                            <a href="javascript:void(0);" class="js-prev-arrow prev-arrow menu-calendar">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 5.29289C8.68342 4.90237 9.31658 4.90237 9.70711 5.29289L15.7071 11.2929C16.0976 11.6834 16.0976 12.3166 15.7071 12.7071L9.70711 18.7071C9.31658 19.0976 8.68342 19.0976 8.29289 18.7071C7.90237 18.3166 7.90237 17.6834 8.29289 17.2929L13.5858 12L8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289Z" fill="#4A0D66"/>
                                </svg>
                            </a>
                            <span class="calendar-title2">September 20 - 25</span>
                            <a href="javascript:void(0);" class="js-next-arrow next-arrow menu-calendar">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.29289 5.29289C8.68342 4.90237 9.31658 4.90237 9.70711 5.29289L15.7071 11.2929C16.0976 11.6834 16.0976 12.3166 15.7071 12.7071L9.70711 18.7071C9.31658 19.0976 8.68342 19.0976 8.29289 18.7071C7.90237 18.3166 7.90237 17.6834 8.29289 17.2929L13.5858 12L8.29289 6.70711C7.90237 6.31658 7.90237 5.68342 8.29289 5.29289Z" fill="#4A0D66"/>
                                </svg>
                            </a>
                        </div>
                        <div class="calendar-dates">
                            <ul id="calendarDates">
                                <li><a class="calender-link is-active" data-link="#Monday" href="javascript:void(0);">Mon</a></li>
                                <li><a class="calender-link" data-link="#Tuesday" href="javascript:void(0);">Tue</a></li>
                                <li><a class="calender-link" data-link="#Wednesday" href="javascript:void(0);">Wed</a></li>
                                <li><a class="calender-link" data-link="#Thursday" href="javascript:void(0);">Thu</a></li>
                                <li><a class="calender-link" data-link="#Friday" href="javascript:void(0);">Fri</a></li>
                                <li><a class="calender-link" data-link="#Saturday" href="javascript:void(0);">Sat</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="js-menu-section" id="menuList"></div>

                </div>

            </div>
        </div>

    </div>

</section>