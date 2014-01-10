<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 1:58 PM
 */

namespace Venturocket\Api\Keyword;


use Venturocket\Api\AbstractClient;

/**
 * Class KeywordClient
 * @package Venturocket\Api\Keyword
 * @link http://venturocket.com/api/v1#keywords
 */
class KeywordClient extends AbstractClient{
    /**
     * Retrieve validity and synonym information regarding the requested keyword
     * @param string $word the desired keyword
     * @return array
     */
    public function getKeyword($word){
        return $this->get("keyword/{$word}");
    }

    /**
     * Given one or more keywords, retrieve a list of keywords ordered by descending popularity which are commonly
     * used with the given keyword(s)
     * @param string[] $words one or more keywords for which to retrieve suggestions
     * @return string[] a list of suggested keywords
     */
    public function getSuggestions(array $words){
        $response = $this->get("keyword-suggestions/".implode(',', $words));
        return $response["suggestions"];
    }

    /**
     * Parse valid Venturocket keywords from raw text
     * @param string $text the text from which to parse keywords
     * @return string[] a list of valid keywords ordered by descending popularity
     */
    public function parseKeywords($text){
        $response = $this->post("keyword-parser", array(), array("text" => $text));
        return $response['keywords'];
    }
} 