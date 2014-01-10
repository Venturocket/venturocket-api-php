venturocket-api-php
===================

The official PHP client library for [Venturocket's API](https://venturocket.com/api/v1).

## Usage
### Installing via [Composer](http://getcomposer.org/)
```bash
php composer.phar require venturocket/venturocket-api-php:~1.0
```

### Making API calls
#### Initialize the client object
```php
use Venturocket\Api\Venturocket;
use Venturocket\Api\Listing\Listing;
$venturocket = new Venturocket("your-api-key", "your-api-secret");
```

#### Keyword calls
```php
// retrieve validity and synonym data for a specific keyword
$keyword = $venturocket->keyword()->getKeyword("php");

// retrieve keyword suggestions based on one or more provided keywords
$suggestions = $venturocket->keyword()->getSuggestions(array("php", "python", "java"));

// parse valid keywords from raw text
$text = "We are looking for rock star web developer with expertise in Javascript and PHP.";
$keywords = $venturocket->keyword()->parseKeywords($text);
```

#### Listing calls
```php
// create a Listing
$listing = new Listing("a_user_id", Listing::USER_TYPE_PROVIDER, "Your headline here!");
$listing->addKeyword("php", 400, 102);
$listing->addLocation("94105");
$listing->addListingType(Listing::LISTING_TYPE_FULL_TIME);

$listingId = $venturocket->listing()->createListing($listing);

// retrieve a listing
$retrievedListing = $venturocket->listing()->getListing($listingId);

// update a listing
$retrievedListing['userType'] = Listing::USER_TYPE_SEEKER;
$venturocket->listing()->updateListing($listingId, $retrievedListing);

// disable a listing
$venturocket->listing()->disableListing($listingId);

// enable a listing
$venturocket->listing()->enableListing($listingId);

// retrieve matches for a listing
$matches = $venturocket->listing()->getMatches($listingId);
```