<?php
/**
 * User: Joe Linn
 * Date: 1/9/14
 * Time: 3:38 PM
 */

namespace Venturocket\Api\Tests\Listing;


use Venturocket\Api\Listing\Listing;
use Venturocket\Api\Listing\ListingClient;
use Venturocket\Api\Tests\BaseTest;

class ListingClientTest extends BaseTest{
    /**
     * @var ListingClient
     */
    protected $client;

    protected function setUp(){
        parent::setUp();
        $this->client = new ListingClient($this->key, $this->secret);
    }

    public function testStorage(){
        $headline = "testing the php api client";
        $listing = new Listing("php_test_user", Listing::USER_TYPE_PROVIDER, $headline);
        $listing->addKeyword("php", 400, 102);
        $listing->addLocation("94105");
        $listing->addListingType(Listing::LISTING_TYPE_FULL_TIME);

        // create the listing
        $listingId = $this->client->createListing($listing);

        sleep(1);   // wait for VR to store the listing

        // retrieve the listing
        $retrievedListing = $this->client->getListing($listingId);

        // ensure that the listing was stored properly
        $this->assertEquals($headline, $retrievedListing['headline']);
        $this->assertEquals($listingId, $retrievedListing['listingId']);

        // modify the listing
        $retrievedListing['userType'] = Listing::USER_TYPE_SEEKER;

        // store the modifications
        $this->client->updateListing($listingId, $retrievedListing);

        sleep(1);   // wait for VR to store the modifications

        // ensure that the modifications were stured
        $modifiedListing = $this->client->getListing($listingId);
        $this->assertEquals($retrievedListing['userType'], $modifiedListing['userType']);

        // disable the listing
        $this->client->disableListing($listingId);

        sleep(1);

        // ensure that the listing was disabled
        $modifiedListing = $this->client->getListing($listingId);
        $this->assertFalse($modifiedListing['enabled']);
    }

    public function testMatching(){
        // create and store two listings which should match each other
        $listing1 = new Listing("test_match_1", Listing::USER_TYPE_PROVIDER, "testing matching in php -- provider");
        $listing1->addKeyword("php", 400, 500);
        $listing1->addListingType(Listing::LISTING_TYPE_FULL_TIME);
        $listing1->addLocation("92109");

        $listing2 = new Listing("test_match_2", Listing::USER_TYPE_SEEKER, "testing matching in php -- seeker");
        $listing2->addKeyword("php", 500);
        $listing2->addListingType(Listing::LISTING_TYPE_FULL_TIME);
        $listing2->addLocation("92109", 5, false);

        $listing1Id = $this->client->createListing($listing1);
        $listing2Id = $this->client->createListing($listing2);

        sleep(1);   // wait for storage

        // ensure that the two listings are matched
        $listing1Matches = $this->client->getMatches($listing1Id);
        $listing2Matches = $this->client->getMatches($listing2Id);

        $matchFound = false;
        $score = 0;
        foreach($listing1Matches as $match){
            if($match['listing']['listingId'] == $listing2Id){
                $matchFound = true;
                $score = $match['score'];
            }
        }

        $this->assertTrue($matchFound);
        $this->assertEquals(1.0, $score);

        $matchFound = false;
        foreach($listing2Matches as $match){
            if($match['listing']['listingId'] == $listing1Id){
                $matchFound = true;
                $this->assertEquals($score, $match['score']);
            }
        }

        $this->assertTrue($matchFound);

        // disable both test listings
        $this->client->disableListing($listing1Id);
        $this->client->disableListing($listing2Id);

        sleep(1);   // wait for disabling

        // ensure that the disabled test listing is no longer among the match results
        $listing1Matches = $this->client->getMatches($listing1Id);
        $matchFound = false;
        foreach($listing1Matches as $match){
            if($match['listing']['listingId'] == $listing2Id){
                $matchFound = true;
            }
        }

        $this->assertFalse($matchFound);
    }
}
 