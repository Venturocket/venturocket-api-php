<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 2:46 PM
 */

namespace Venturocket\Api\Listing;


use Venturocket\Api\AbstractClient;

class ListingClient extends AbstractClient{
    /**
     * Create a new listing
     * @param array|Listing $listing an array constituting valid listing data or a Listing object
     * @return string the id of the newly created listing
     */
    public function createListing($listing){
        if($listing instanceof Listing){
            $listing = $listing->toArray();
        }
        $response = $this->post("listing", array(), $listing);
        return $response['listingId'];
    }

    /**
     * Retrieve a listing by id
     * @param string $listingId the id of the desired listing
     * @return array a listing
     */
    public function getListing($listingId){
        return $this->get("listing/{$listingId}");
    }

    /**
     * Update an existing listing
     * @param string $listingId the id of the listing to be updated
     * @param array|Listing $listing an array constituting valid listing data or a Listing object
     * @return string the id of the updated listing
     */
    public function updateListing($listingId, $listing){
        if($listing instanceof Listing){
            $listing = $listing->toArray();
        }
        $response = $this->put("listing/{$listingId}", array(), $listing);
        return $response['listingId'];
    }

    /**
     * Disable a listing
     * @param string $listingId the id of the listing to be disabled
     * @return string the id of the disabled listing
     */
    public function disableListing($listingId){
        $response = $this->put("listing/{$listingId}/disable");
        return $response['listingId'];
    }

    /**
     * Enable a listing
     * @param string $listingId the id of the listing to be enabled
     * @return string the id of the enabled listing
     */
    public function enableListing($listingId){
        $response = $this->put("listing/{$listingId}/enable");
        return $response['listingId'];
    }

    /**
     * Retrieve matches for a specific listing
     * @param string $listingId the id of the listing for whcih to retrieve matches
     * @return array a list of match objects. If no matches were found, an empty array will be returned.
     */
    public function getMatches($listingId){
        $response = $this->get("listing/{$listingId}/matches");
        return $response['matches'];
    }
} 