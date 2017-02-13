<?php
require("simple_html_dom.php");
require("connection.php");
function ThalluAccessAPI($url)
{	 $http_head = array(
        "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
        "Accept-Language:en-US,en;q=0.8",
        "Connection:keep-alive",
        "Upgrade-Insecure-Requests:1",
        "User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36"
    );
    $ch        = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // Target URL
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_head);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

	#change your URL
    $amazon_url='https://www.amazon.com/s/ref=lp_193870011_nr_p_72_0?fst=as%3Aoff&rh=n%3A172282%2Cn%3A%21493964%2Cn%3A541966%2Cn%3A193870011%2Cp_72%3A1248879011&bbn=193870011&ie=UTF8&qid=1487008258&rnid=1248877011&lo=computers&tag=wwwwarriorsof-20';

	$amz_url  = 'http://thallu.com/api/amazon.php?auth_code=YourAuthcode&amazonURL=' . urlencode($amazon_url);
    $html    = ThalluAccessAPI($amz_url);
	$html    = str_get_html($html);

if (!empty($html)) {
    
    if ($html->find("div[id=resultsCol] ul li")) {
        foreach ($html->find("div[id=resultsCol] ul li") as $elem) {
			if($elem->{"data-asin"}){
				$tot_scrap++;
				$parent_asin  = $elem->{"data-asin"};
				$country_url = 'https://www.amazon.co.uk/dp/'.$parent_asin;
				$amz_url  = 'http://thallu.com/api/amazon.php?auth_code=YourAuthcode&amazonURL=' . urlencode($country_url);
				
				$html2        = ThalluAccessAPI($amz_url);
				$html2        = str_get_html($html2);
				
				if (!empty($html2)) {
					$category_main= @$html2->find("span[class=a-list-item]", 0)->plaintext;
					$category_main=trim($category_main);
					$product_name = @$html2->find("h1[id=title]", 0)->plaintext;
					$product_name = trim($product_name); //product name
					  
					$brand_name = @$html2->find("a[id=brand]", 0)->plaintext; // seller name
					$brand_name=trim($brand_name);
				   
					$pdt_descript = @$html2->find("div[id=productDescription]", 0)->plaintext;
					$pdt_descript = trim($pdt_descript);
					
					##Scrape MOre info you want
						 
				   ## YOUR INSER QUERY HERE
				   $amz_table = "insert into table table_name yourfield ";
				   mysql_query($amz_table);
				   ## End of Your INSER QUERY HERE
				}
			}	
		}
	 }
} 
 
?>
