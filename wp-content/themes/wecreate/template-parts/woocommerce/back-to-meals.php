<?php 

$url = $_SERVER['REQUEST_URI'];
$url = explode("/",$url);

$currentURL = $_SERVER['REQUEST_URI'];


$shop_page = "";

if (strpos($currentURL, "wellness-boutique")!==false || strpos($currentURL, "wellness-boutique-zh")!==false){
    $title_header = "BACK TO WELLNESS BOUTIQUE";
    $shop_page = "wellness-boutique";
}
else {
    $title_header = "OUR MEAL PLANS";
    $shop_page = "meal-plans";
}

if (ICL_LANGUAGE_CODE == 'zh')
{
	$shop_page_id = "/zh/".$shop_page;
}
else {
	$shop_page_id = "/".$shop_page;
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