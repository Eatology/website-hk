<?php 

$url = $_SERVER['REQUEST_URI'];
$url = explode("/",$url);

if (ICL_LANGUAGE_CODE == 'zh')
{
	$shop_page_id = "/zh/".$url[2];
}
else {
	$shop_page_id = "/".$url[2];
}

if($url[2] == "wellness-boutique"){
    $title_header = "BACK TO WELLNESS BOUTIQUE";
}else{
    $title_header = "OUR MEAL PLANS";
}
?>

<?php

echo '<script>
    var extra_days_info = "'.__('5 Days are delivered only on weekdays.<br>6 Days are delivered every day except Sundays.', 'eatology').'";
    var per_days_text = "'.__(' PER DAY', 'eatology').'";
</script>
<div class="back-to-meal-plans">
    <a href="'.$shop_page_id. '">
        <span class="icon-icon-back"></span> '.__($title_header,'eatology').'
    </a>
</div>';