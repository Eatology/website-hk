<?php 
if (ICL_LANGUAGE_CODE == 'zh')
{
	$shop_page_id = 3215;
}
else {
	$shop_page_id = 451;
}
?>

<?php
echo '<script>
    var extra_days_info = "'.__('5 Days are delivered only on weekdays.<br>6 Days are delivered every day except Sundays.', 'eatology').'";
    var per_days_text = "'.__(' PER DAY', 'eatology').'";
</script>
<div class="back-to-meal-plans">
    <a href="'.get_the_permalink($shop_page_id). '">
        <span class="icon-icon-back"></span> '.__('Our Meal Plans','eatology').'
    </a>
</div>';