<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$page_counter = 0;
$next_page = FALSE;

do { 
$kovetkezo = "";
$page_counter++;
$pageurl = "http://www.hasznaltauto.hu/partner/ber-car_autokereskedes-448/page{$page_counter}";

 
$html_content = scraperWiki::scrape($pageurl);

$html = str_get_html($html_content);
foreach ($html->find("div.talalati_lista") as $talalat) {  
    foreach ($talalat->find("h2 a") as $el) {
    $tipus = $el->innertext;
    $url = $el->href;
    $kod = substr($url, -7); 
    }
    foreach ($talalat->find("div.talalati_lista_vetelar strong") as $ar) {
    $ar = str_replace("&nbsp;", " ", $ar->innertext);
    }
    foreach ($talalat->find("p.talalati_lista_infosor") as $info) {

    $info = str_replace("&ndash;", ",", $info->innertext);
    $info = str_replace("&nbsp;", " ", $info);
    $info = str_replace("&sup3;", "3", $info);
    $info = explode(",",$info);
    }

    scraperwiki::save(   
            array('id'),
            array(
                'id' => $kod,
                'type' => $el->innertext,
                'price' => $ar,
                'year' => $info[0],
                'benzin' => $info[1],
                'hub' => $info[2],
                'power' => $info[3],
                'url' => $url,

            )
        );
}
    foreach ($html->find("div.oldalszamozas a[title=Következő]") as $kovetkezo) {
    print $page_counter . "\n";
    }

} while ($kovetkezo != "");
?>
