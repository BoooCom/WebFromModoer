<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_tuan {

    //取得分类的名称或其它
    //参数 catid,keyname
    function category($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$catid) return '';
        $loader =& _G('loader');
        $category = $loader->variable('category','tuan');
        if(!isset($category[$catid][$keyname])) return '';
        return $category[$catid][$keyname];
    }

    // 计算出最终价格
    // $total_price,$peoples_sell,$people_min
    function average_price($params) {
        extract($params);
        if(!isset($total_price)||!isset($peoples_sell)) return '';
        if(!$peoples_sell) $peoples_sell = (int)$peoples_min;
        return round($total_price / $peoples_sell, 2);
    }

    // 计算出最终价格
    // $prices,$goods_sell
    function wholesale_price($params) {
        extract($params);
        if(!$prices||!isset($goods_sell)) return '';
        $loader =& _G('loader');
        $real_price = 0;
        $T =& $loader->model(':tuan');
        $prices = $T->parse_prices($prices);
        foreach($prices as $num => $price) {
            if(!$goods_sell) {
                $real_price = $price;
                break;
            }
            // 找到对应批发的价格
            if($num >= $goods_sell) $real_price = $price;
        }
        return $real_price;
    }

    function forestall_price($params) {
        extract($params);
        if(!$prices||!isset($peoples_sell)||!isset($price)) return '';
        $loader =& _G('loader');
        $real_price = $price;
        $T =& $loader->model(':tuan');
        $prices = $T->forestall_parse_prices($prices);
        foreach($prices as $num => $price) {
            if(!$peoples_sell) {
                $real_price = $price;
                break;
            }
            // 找到对应批发的价格
            if($peoples_sell+1 <=$num) {
                $real_price = $price;
                break;
            }
        }
        return $real_price;
    }
}
?>