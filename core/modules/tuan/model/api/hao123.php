<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class tuan_api_hao123 extends ms_base {

    function get_xml() {
        $T =& $this->loader->model(':tuan');
        list($total, $list) = $T->getlist_api();
        if(!$total) return;
        $xml = '<?xml version="1.0" encoding="'.$this->global['charset'].'" ?>'."\r\n";
        $xml .= "<urlset>\r\n";
        while($v = $list->fetch_array()) {
            $xml .= $this->_xml($v);
        }
        $xml .= "</urlset>";
        return $xml;
    }

    function _xml(&$data) {
        $xml = "\t<url>\n";
        $xml .= "\t\t<loc>".url("tuan/detail/id/$data[tid]",'',1)."</loc>\n";
        $xml .= "\t\t<data>\n";
        $xml .= "\t\t\t<display>\n";
        $xml .= "\t\t\t\t<website>".$this->global['cfg']['sitename']."</website>\n";
        $xml .= "\t\t\t\t<siteurl>".$this->global['cfg']['siteurl']."</siteurl>\n";
        $cityname = $data[city_id] ? display("modoer:area","aid/$data[city_id]") : '全国';
        $xml .= "\t\t\t\t<city>".$cityname."</city>\n";
        $xml .= "\t\t\t\t<category></category>\n"; //分类id
        $xml .= "\t\t\t\t<dpshopid></dpshopid>\n"; //商家在大众点评的shopid
        $xml .= "\t\t\t\t<range></range>\n"; //商圈
        $xml .= "\t\t\t\t<address></address>\n"; //地址
        $xml .= "\t\t\t\t<major></major>\n";  //今日主打
        $xml .= "\t\t\t\t<title>".$data['subject']."</title>\n";  //商品标题
        $xml .= "\t\t\t\t<image>".$this->global['cfg']['siteurl'].$data['thumb']."</image>\n"; //商品图片
        $xml .= "\t\t\t\t<startTime>".$data['starttime']."</startTime>\n"; //商品开始时间
        $xml .= "\t\t\t\t<endTime>".$data['endtime']."</endTime>\n"; //商品结束时间
        $xml .= "\t\t\t\t<value>".$data['market_price']."</value>\n"; //商品原价
        $xml .= "\t\t\t\t<price>".$data['price']."</price>\n"; //商品现价
        $xml .= "\t\t\t\t<rebate>".$data['price']."</rebate>\n"; //商品折扣
        $xml .= "\t\t\t\t<bought>".$data['peoples_sell']."</bought>\n"; //已购买人数
        $xml .= "\t\t\t</display>\n";
        $xml .= "\t\t</data>\n";
        $xml .= "\t</url>\n";
        return $xml;
    }

}
?>