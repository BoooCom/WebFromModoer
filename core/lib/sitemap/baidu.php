<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class sitemap_baidu extends se_sitemap  {

    public function get_url_xml() {

        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content = str_replace('UTF-8', $this->_charset, $content) . "\n";
        $content .= "<urlset>\n";
        if($this->_urls) {
            $index = 1;
            foreach ($this->_urls as $key => $urls) {
                $content .= "<url>";
                foreach ($urls as $key => $value) {
                    if(!$value) continue;
                    $content .= "<$key>$value</$key>";
                }
                $content .= "</url>\n";
                if($index++ > 50000) break;
            }
        }
        $content .= "</urlset>";

        return $content;
    }
}
?>