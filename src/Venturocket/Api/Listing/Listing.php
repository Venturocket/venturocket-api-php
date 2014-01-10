<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 2:48 PM
 */

namespace Venturocket\Api\Listing;


class Listing {
    const USER_TYPE_SEEKER = "seeker";
    const USER_TYPE_PROVIDER = "provider";

    const TELECOMMUTE_YES = "yes";
    const TELECOMMUTE_NO = "no";
    const TELECOMMUTE_ONLY = "only";

    const LISTING_TYPE_CONTRACT = "contract";
    const LISTING_TYPE_FULL_TIME = "full-time";
    const LISTING_TYPE_PART_TIME = "part-time";
    const LISTING_TYPE_CONTRACT_TO_HIRE = "contract-to-hire";
    const LISTING_TYPE_INTERNSHIP = "internship";

    protected $params = array();

    protected $locations = array();

    protected $keywords = array();

    protected $listingTypes = array();

    /**
     * @param string $userId
     * @param string $userType
     * @param string $headline
     */
    public function __construct($userId, $userType, $headline){
        $this->setUserId($userId)->setUserType($userType);
        $this->setHeadline($headline);
    }

    /**
     * @param string $userId the user id associated with this listing
     * @return $this
     */
    public function setUserId($userId){
        return $this->setParam("userId", $userId);
    }

    /**
     * Set the user type for this listing
     * @param string $userType see USER_TYPE_* constants for valid options
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setUserType($userType){
        if($userType != self::USER_TYPE_PROVIDER && $userType != self::USER_TYPE_SEEKER){
            throw new \InvalidArgumentException("userType must be either 'provider' or 'seeker'. {$userType} given.");
        }
        return $this->setParam("userType", $userType);
    }

    /**
     * Set the headline for this listing
     * @param string $headline 140 characters max
     * @return $this
     */
    public function setHeadline($headline){
        return $this->setParam("headline", $headline);
    }

    /**
     * Set telecommuting for this listing
     * @param string $telecommute see TELECOMMUTE_* constants for valid options
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setTelecommute($telecommute){
        $validOptions = array(
            self::TELECOMMUTE_NO,
            self::TELECOMMUTE_ONLY,
            self::TELECOMMUTE_YES
        );
        if(!in_array($telecommute, $validOptions)){
            throw new \InvalidArgumentException("telecommute must be 'yes', 'no', or 'only'. {$telecommute} given.");
        }
        return $this->setParam("telecommute", $telecommute);
    }

    /**
     * Add a location to this listing
     * @param string $zip a zip code
     * @param int $commute maximum commute radius in miles. Not used if userType is "provider"
     * @param bool $relocate true if willing to relocate to this location. Not used if userType is "provider"
     * @return $this
     */
    public function addLocation($zip, $commute = 0, $relocate = false){
        $this->locations[] = array(
            "zip" => $zip,
            "commute" => $commute,
            "relocate" => $relocate
        );
        return $this;
    }

    /**
     * Add a keyword to this listing
     * @param string $word a valid keyword
     * @param int $experience an integer value from 100 to 1000
     * @param int $range only used if userType is "provider". experience + range cannot exceed 1000
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addKeyword($word, $experience, $range = 0){
        if($experience < 100 || $experience > 1000){
            throw new \InvalidArgumentException("experience must be between 100 and 1000. {$experience} given.");
        }
        if($range < 0){
            throw new \InvalidArgumentException("range cannot be negative. {$range} given.");
        }
        if($range + $experience > 1000){
            throw new \InvalidArgumentException("range + experience cannot exceed 1000. range: {$range}, experience: {$experience}");
        }
        $this->keywords[] = array(
            "word" => $word,
            "experience" => (int) $experience,
            "range" => (int) $range
        );
        return $this;
    }

    /**
     * Add a listing type to this listing
     * @param string $type see LISTING_TYPE_* constants for valid options
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addListingType($type){
        $validOptions = array(
            self::LISTING_TYPE_CONTRACT,
            self::LISTING_TYPE_FULL_TIME,
            self::LISTING_TYPE_PART_TIME,
            self::LISTING_TYPE_CONTRACT_TO_HIRE,
            self::LISTING_TYPE_INTERNSHIP
        );
        if(!in_array($type, $validOptions)){
            throw new \InvalidArgumentException("invalid listing type given: {$type}");
        }
        $this->listingTypes[] = array(
            "type" => $type
        );
        return $this;
    }

    /**
     * Convert this listing object into an array which can then be sent to the API
     * @return array
     */
    public function toArray(){
        $data = $this->params;
        $data["locations"] = $this->locations;
        $data["keywords"] = $this->keywords;
        $data["listingTypes"] = $this->listingTypes;
        return $data;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setParam($key, $value){
        $this->params[$key] = $value;
        return $this;
    }
} 