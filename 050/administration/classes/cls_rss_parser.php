<?php
// $Id: cls_rss_parser.php 1128 2005-08-03 22:16:55Z mysz $

class rss_parser {
    
    var $CHANNELS       = array();
    var $CHANNELINFO    = array();
    var $COUNT          = 0;
    
    // konstruktor
    function rss_parser($data = "", $simple) {
        
        if($simple) {
            
            $temp           = array();
            $temp[0][0]     = $data;
            $this->COUNT    = 1;
            
            $this->parseItems($temp);
        } else {
            
            $this->assignDATA($data);
        }
    }
    
    
    function error($msg = "") {
        
        print "<h3>Error: [$msg]</h3>\n";
        return;
    }
    
    
    function getCount() {
        
        return $this->COUNT;
    }
    
    
    function getChannel($channelID) {
        
        return $this->CHANNELS[$channelID];
    }
    
    
    function getChannelInfo($channelID) {
        
        return $this->CHANNELINFO[$channelID];
    }
    
    
    function itemCount($channelID) {
        
        return count($this->CHANNELS[$channelID]['ITEMS']);
    }
    
    
    function getItems($channelID) {
        
        return $this->CHANNELS[$channelID]['ITEMS'];
    }
    
    
    function getAllItems() {
        
        $count  = $this->getCount();
        $ticker = 0;
        
        $allItems = array();
        
        for($x = 0; $x < $count; $x++) {
            
            $itemCount  = $this->itemCount($x);
            $itemData   = $this->getItems($x);
            
            for($y = 0; $y < $itemCount; $y++) {
                
                $allItems[$ticker]['TITLE']         = $itemData[$y]['TITLE'];
                $allItems[$ticker]['LINK']          = $itemData[$y]['LINK'];
                $allItems[$ticker]['DESCRIPTION']   = $itemData[$y]['DESCRIPTION'];
                $allItems[$ticker]['DATE']          = $itemData[$y]['DATE'];
                
                $ticker++;
            }
        }
        
        return $allItems;
    }
    
    
    function assignDATA($data = "") {
        
        if(empty($data)) {
            
            $this->error("No RSS data submitted");
        } else {
            
            $this->parse($data);
        }
        
        return;
    }
    
    
    function parseChannels($data = "") {
        
        $channelCount = preg_match_all("|<channel>(.*)</channel>|iUs", $data, $channels, PREG_SET_ORDER);
        if(!$channelCount) {
            
            $this->error("No channels in RSS data");
            return;
        } else {
            
            $this->COUNT = $channelCount;
        }
        
        return $channels;
    }
    
    
    function storeItems($itemData = "", $channelID, $itemID) {
        
        if(preg_match_all("|<title>(.+)</title>|iUs", $itemData, $match, PREG_SET_ORDER)) {
            
            $title = $match[0][1];
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['TITLE'] = "$title";
        } else {
            
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['TITLE'] = "";
        }
        
        if(preg_match_all("|<link>(.+)</link>|iUs", $itemData, $match, PREG_SET_ORDER)) {
            
            $link = $match[0][1];
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['LINK'] = "$link";
        } else {
            
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['LINK'] = "";
        }
        
        if(preg_match_all("|<description>(.+)</description>|iUs", $itemData, $match, PREG_SET_ORDER)) {
            
            $desc = $match[0][1];
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['DESCRIPTION'] = "$desc";
        } else {
            
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['DESCRIPTION'] = "";
        }
        
        if(preg_match_all("|<dc:date>(.+)</dc:date>|iUs", $itemData, $match, PREG_SET_ORDER)) {
            
            $date = $match[0][1];
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['DATE'] = "$date";
        } else {
            
            $this->CHANNELS[$channelID]['ITEMS'][$itemID]['DATE'] = "";
        }
        
        return;
    }
    
    
    function storeChannelData($data="", $channelID) {
        
        $data   = str_replace("<channel>", "", $data);
        $data   = str_replace("</channel>", "", $data);
        $lines  = split("\n", $data);
        
        foreach ($lines as $key => $line) {
        //while(list($key, $line) = each($lines)) {
            
            $line = trim($line);
            if(!empty($line)) {
                
                if(preg_match("|<([^>]+)>(.*)</\\1>|U", $line, $matches)) {
                    
                    $tagName = $matches[1];
                    $tagVal  = $matches[2];
                    
                    $this->CHANNELS[$channelID][$tagName]       = $tagVal;
                    $this->CHANNELINFO[$channelID][$tagName]    = $tagVal;
                }
            }
        }
        
        return;
    }
    
    
    function parseItems($channels) {
        
        $channelCount = count($channels);
        if(!$channelCount) {
            
            $this->error("Could not locate any channel data to parse");
            exit;
        }
        
        for($x = 0; $x < $channelCount; $x++) {
            
            $channelData    = $channels[$x][0];
            $leftOvers      = $channelData;
            $itemCount      = preg_match_all("|<item(.*)>(.*)</item>|iUs", $channelData, $items, PREG_SET_ORDER);
            
            if($itemCount) {
                
                for($y = 0; $y < $itemCount; $y++) {
                    
                    $itemData   = $items[$y][0];
                    $leftOvers  = str_replace("$itemData", "", $leftOvers);
                    $this->storeItems($itemData, $x, $y);
                }
            }
            
            $this->storeChannelData($leftOvers, $x);
        }
        return;
    }
    
    
    function parse($data = "") {
        
        $channels = $this->parseChannels($data);
        if(empty($channels)) {
            
            return;
        }
        
        $this->parseItems($channels);
        return;
    }
    
}

?>
