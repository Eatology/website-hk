<div id="calendar-wrapper">
    <img src="/wp-content/themes/wecreate/resources/assets/images/loading.gif" id="image-loader"/>
    <div id="calendar"></div>
</div>

<div id="calendar-action-wrapper">
<div id="calendar-action">
    <div class="calendar-action-contents">
        <button id="calendar-action-close"><span class="icon-cart_delete"></span></button>        
        
        <div id="calendar-edit">
            <h3 id="calendar-h3"></h3>
            <p id="calendar-intro"></p>

            <div id="calendar-action-slot">
                <h3><?php esc_html_e( 'Actions', 'eatology' ); ?></h3>
                <div class="calendar-action-button-wrapper">
                    <button id="calendar-confirm-address"><?php esc_html_e( 'Update Address', 'eatology' ); ?></button>
                    <button id="calendar-confirm-delivery"><?php esc_html_e( 'Update Delivery Time', 'eatology' ); ?></button>
                    <button id="calendar-confirm-postpone"><?php esc_html_e( 'Postpone', 'eatology' ); ?></button>
                    <button id="calendar-confirm-meal"><?php esc_html_e( 'Update Meal Plan', 'eatology' ); ?></button>
                </div>
            </div>
            <div id="calendar-action-space">
                <div id="calendar-action-space--address">

                </div>
                <div id="calendar-action-space--delivery">

                </div>
                <div id="calendar-action-space--postpone">
                    
                </div>

                <div id="calendar-action-space--meal">

                </div>
            </div>
        </div>

        <div id="calendar-new-edit-address">
            <h3 id="add-address-h3"><?php esc_html_e( 'Add a New Destination Address', 'eatology' ); ?></h3>
            <h3 id="edit-address-h3"><?php esc_html_e( 'Change Destination Address', 'eatology' ); ?></h3>

            <form id="address-form" method="POST">
                <input name="post-address-id" id="post-address-id" type="hidden"/>
            <div class="address-row name">
                    <label for="post-address-name"><?php esc_html_e( 'Address name', 'eatology' ); ?>
                        <input name="post-address-name" id="post-address-name"/>
                    </label>                
                </div>

                <div class="address-row floor">
                    <label for="post-address-floor_number"><?php esc_html_e( 'Floor', 'eatology' ); ?>
                        <input name="post-address-floor_number" id="post-address-floor_number"/>
                    </label>  
                    
                    <label for="post-address-room"><?php esc_html_e( 'Room', 'eatology' ); ?>
                        <input name="post-address-room" id="post-address-room"/>
                    </label>  
                    
                    <label for="post-address-tower_block"><?php esc_html_e( 'Block/ Tower', 'eatology' ); ?>
                        <input name="post-address-tower_block" id="post-address-tower_block"/>
                    </label>                  
                </div>

                <div class="address-row building">
                    <label for="post-address-building_name"><?php esc_html_e( 'Building Name', 'eatology' ); ?>
                        <input name="post-address-building_name" id="post-address-building_name"/>
                    </label>                
                </div>    
                
                <div class="address-row street">
                    <label for="post-address-number_street_name"><?php esc_html_e( 'Street Number and name', 'eatology' ); ?>
                        <input name="post-address-number_street_name" id="post-address-number_street_name"/>
                    </label>                
                </div>                
                
                <div class="address-row area">
                    <label for="post-address-district"><?php esc_html_e( 'District', 'eatology' ); ?>
                        <span class="select-span">
                            <select name="post-address-district" id="post-address-district"/></select>
                        </span>                        
                    </label>                  
                </div>
                
                <div class="address-row remarks">
                    <label for="post-address-remark"><?php esc_html_e( 'Remarks', 'eatology' ); ?>
                        <textarea name="post-address-remark" id="post-address-remark" placeholder="- - -"></textarea>
                    </label>                
                </div>            
                
                <button id="post-add-address" type="submit"><?php esc_html_e( 'Add Address', 'eatology' ); ?></button>
                <button id="post-edit-address" type="submit"><?php esc_html_e( 'Change Address', 'eatology' ); ?></button>

            </form>            
        </div>

        <div id="calendar-new-meal">
            <h3><?php esc_html_e( 'Set Delivery Dates', 'eatology' ); ?></h3>
            <p id="new-meal-intro"></p>
            <form action="#" id="add-orders">
                <div id="new-meal-wrapper"></div>
                <div id="hidden-wrapper"></div>
            </form>
        </div>
    </div>
</div>
</div>    

<p class="update-notice"><?php esc_html_e( '*To update date, drag the box. For more actions, click on the box.', 'eatology' ); ?></p>

<div class="addresses-wrapper">
    <div class="addresses">
        <h3><?php esc_html_e( 'Addresses', 'eatology' ); ?></h3>
        <div id="address-wrapper"></div>
    </div>
    <div class="add-address" id="add-address-wrapper">
        <button id="add-new-address" type="submit"><?php esc_html_e( 'Add a New Address', 'eatology' ); ?></button>        
    </div>
</div>

<?php
  /**
   * get footer information
   */
  $footer_section = get_field('footer', 'option');
?>

<div id="error-modal" class="modal">
    <div class="modal-wrapper">
        <div class="modal-content">
            <button id="error-modal-close"><span class="icon-cart_delete"></span></button>
            <div class="section-content">
                <div class="h3"><?php esc_html_e('Need any help?', 'eatology');?></div>
                <div class="content-message">
                    <p><?php esc_html_e('Contact Us', 'eatology');?></p>
                    <div class="contact-wrapper wrapper-address">
                        <div class="contact-icon">
                            <span class="icon-icon-location contact-address"></span>
                        </div>
                        <div class="contact-detail contact-address">
                            <p><?php echo $footer_section['contact_address']; ?></p>
                        </div>
                    </div>

                    <div class="contact-wrapper wrapper-phone">
                        <div class="contact-icon">
                            <span class="icon-icon-phone"></span>
                        </div>
                        <div class="contact-detail">
                            <?php echo $footer_section['contact_phone_number']; ?>				
                        </div>
                    </div>

                    <div class="contact-wrapper wrapper-mail">
                        <div class="contact-icon">
                            <span class="icon-icon-mail"></span>
                        </div>
                        <div class="contact-detail">
                        <a href="mailto:<?php echo $footer_section['contact_email']; ?>"><?php echo $footer_section['contact_email']; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

