#!/usr/bin/env php
<?php

print '# blows away all reviews for product #<?php print $product_id; ?> and adds <?php print $num_reviews; ?> new reviews to that product.\n';
print '# execute this on the command line like this:\n';
print '# php -f fake_reviews.sql.php | mysql -u user -p dbname\n';

$product_id     = 1;
$store_id       = 1;
$customer_id    = "null";
//$customer_id    = "(select entity_id from customer_entity order by rand() limit 1)";
$num_reviews    = 20;

$lipsum_url     = "http://lipsum.com/feed/html";
$text_url       = "$lipsum_url?what=paragraphs&amount=%num%";

$randomRateSql  = "select random.rating_id, random.rand_option_id, r3.code, r3.value, r3.position from (select r.rating_id, (select r2.option_id from rating_option r2 where r2.rating_id = r.rating_id order by rand() limit 1) as 'rand_option_id' from rating_option r group by r.rating_id) random left join rating_option as r3 on r3.option_id = rand_option_id";

$titleNickSrc   = getLipsumPart(file_get_contents("$lipsum_url?what=words&amount=20"), true);

?>

delete from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>;

<?php

    for($i = 0; $i < $num_reviews; $i++) {
        $textLength     = rand(1, 3);
        $titleLength    = rand(5, 20);
        $nickLength     = rand(2, 4);
        
        $text           = getLipsumPart(file_get_contents(str_replace("%num%", $textLength, $text_url)), true);
        $title          = trimLength($titleNickSrc, $titleLength);
        $nick           = trimLength($titleNickSrc, $nickLength);
        
        print "insert into review (created_at, entity_id, entity_pk_value, status_id) values (now(), 1, $product_id, 1);";
        print "insert into review_detail (review_id, store_id, title, detail, nickname, customer_id) values ((select max(review_id) from review), $store_id, '$title', '$text', '$nick', $customer_id);";
        
        print "insert into rating_option_vote (option_id, remote_ip, remote_ip_long, customer_id, entity_pk_value, rating_id, review_id, percent, value)
                select rand_option_id, '127.0.1.1', 2130706689, null, 1, rating_id, (select max(review_id) from review), value*20, value from ($randomRateSql) t;\n";
    }

?>

delete from rating_option_vote_aggregated where entity_pk_value = <?php print $product_id; ?>;
insert into rating_option_vote_aggregated (rating_id, entity_pk_value, vote_count, vote_value_sum, percent, percent_approved, store_id)
    select rating_id, <?php print $product_id; ?>, count(*), sum(value), avg(percent), avg(percent), 0 from rating_option_vote where review_id in (select review_id from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>) group by rating_id;
insert into rating_option_vote_aggregated (rating_id, entity_pk_value, vote_count, vote_value_sum, percent, percent_approved, store_id)
    select rating_id, <?php print $product_id; ?>, count(*), sum(value), avg(percent), avg(percent), 1 from rating_option_vote where review_id in (select review_id from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>) group by rating_id;

delete from review_store where review_id in (select review_id from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>);
insert into review_store (review_id, store_id) (select review_id, 0 from review);
insert into review_store (review_id, store_id) (select review_id, <?php print $store_id; ?> from review);

delete from review_entity_summary where entity_type = 1 and entity_pk_value = <?php print $product_id; ?>;
insert into review_entity_summary (entity_pk_value, entity_type, reviews_count, rating_summary, store_id) (select <?php print $product_id; ?>, 1, <?php print $num_reviews; ?>, ceil(avg(percent)), 0 from rating_option_vote where review_id in (select review_id from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>));
insert into review_entity_summary (entity_pk_value, entity_type, reviews_count, rating_summary, store_id) (select <?php print $product_id; ?>, 1, <?php print $num_reviews; ?>, ceil(avg(percent)), 1 from rating_option_vote where review_id in (select review_id from review where entity_id = 1 and entity_pk_value = <?php print $product_id; ?>));

<?php

function getLipsumPart( $text, $stripTags = false ) {
    $startTag           = "<div id=\"lipsum\">";
    $endTag             = "</div>";
    
    $startIdx           = strpos($text, $startTag);
    $endIdx             = strpos($text, $endTag, $startIdx);
    
    $relevantText       = substr($text, $startIdx+strlen($startTag), $endIdx-($startIdx+strlen($startTag)));
    
    if($stripTags) {
        $relevantText   = strip_tags($relevantText);
    }
    
    return trim($relevantText);
}

function trimLength( $text, $words ) {
    $arr = array_slice(explode(" ", $text), 0, $words);
    return trim(implode(" ", $arr));
}
