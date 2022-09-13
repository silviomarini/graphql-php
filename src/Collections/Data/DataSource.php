<?php declare(strict_types=1);

namespace GraphQL\Collections\Data;

use function array_filter;
use function array_keys;
use function array_map;
use function array_search;
use function array_slice;
use function array_values;
use function count;
use function in_array;
use function rand;

$class = "DataSource";

//TODO: move to config file 
$collectionSource = "RND";
$eventSource = "MOCK"; 

//Mock type can be RND or MOCK , first create random info, second one return the mocked data
$mockType = "RND";
$rnd_names = array("Neo", "Morpheus", "Trinity", "Cypher", "Tank", "Monkeys", "Apes", "Gamers", "Teddy bears", "Candies", "Knights", "Lakers", "Warriors", "Timberwolvers", "Bulls");
$rnd_colors = array("White", "Black", "Green", "Blue", "Red", "Gold", "Silver", "Orange", "Dark", "Divine", "Rare", "Unique", "Common", "Crazy", "Bored", "Happy", "Sad", "Sandy");
$rnd_img = array("https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE");

$period_values = array(
    "1m" => 1,
    "5m" => 5,
    "10m" => 10,
    "30m" => 30,
    "1h" => 60,
    "6h" => 360,
    "12h" => 720,
    "24h" => 1440,
    "1d" => 1440,
    "7d" => 10080,
    "30d" => 43200
);

$fomo = array("Low", "Medium", "High");

if($collectionSource != "MOCK" || $eventSource != "MOCK"){
    require_once('db/connect.php');
}

$elements = array();
$axieElement = new DistributionElement([
    'id' => 1,
    'name' => 'Axie infinity',
    'value' => 112,
    'format' => 'ETH_PRICE',
    'value_percent' => 1.1,
]);
array_push($elements, $axieElement);

/**
 * This is just a simple in-memory data holder for the sake of example.
 * Data layer for real app may use Doctrine or query the database directly (e.g. in CQRS style).
 */
class DataSource
{

    /** @var array<int, Collection> */
    private static array $collections = [];

    /** @var array<int, Event> */
    private static array $events = [];

    private static array $formats = [];

    private static array $distributions = [];

    public static function init(): void
    {
        global $elements;
        //TODO: setting null to one of the field raises error. That has to be fixed. 'null' containing fields here will be normally boolean null values from the database upcoming events.
        self::$events = [
            1 => new Event([
               'id' => 1,
               'url_event' => 'https://nftcalendar.io/event/embers/',
               'event_name' => 'Embers',
               'image_link' => 'https://nftcalendar.io/storage/uploads/events/2022/2/dpusazRUqM5jrjrQ0S3L2H19fbCQKxAMefj3INDr.gif',
               'start_date' => 'February 28, 2022',
               'end_date' => 'March 07, 2022',
               'website_link' => 'null',
               'twitter_link' => 'null',
               'discord_link'=> 'null',
               'marketplace' => 'opensea',
               'marketplace_link' => 'https://opensea.io/',
               'blockchain' => 'ethereum',
               'blockchain_link' => 'https://opensea.io/',
               'description' => 'Embers are a collection of 5,555 burning hot NFTs living in the core of the blockchain. Each individual Ember is carefully curated from over 150 traits, along with some incredibly rare 1/1s that have traits that can\'t be found from any other Ember.Our vision is to create an amazing project that will shed light, joy, love, and creativity! Fire Sale ( WL ) members may mint an Ember for 0.1 ETH.Public Sale will be a Dutch Auction that will decrease from a maximum of 0.3 ETH to a minimum of 0.15 ETH.Our Fire Sale mint is on March 26, 2022 7:00 PM UTCOur Public Sale mint is on March 27, 2022 7:00 PM UTCOur official links are:https://twitter.com/embersnfthttps://instagram.com/embersnfthttps://embersnft.comThere you could find our roadmap! We\'ve already completed a couple things, such as: * Free NFT Giveaways* Donation of $50,000 to the Red Cross* We are also allocating 25% of mint funds for the longevity of the project, as well as 50% of royalties.We\'re implementing a DAO system so that every holder can have a say in the project. We are also adding staking, so that our members can earn tokens just by holding onto their Ember!Additionally, we are going to be awarding our holders with collaborations with other projects, as well as giving them airdrops and other special benefits!'
               ]),
            2 => new Event([
                'id' => 2,
                'url_event' => 'https://nftcalendar.io/event/embers/',
                'event_name' => 'Mock 2',
                'image_link' => 'https://nftcalendar.io/storage/uploads/events/2022/2/dpusazRUqM5jrjrQ0S3L2H19fbCQKxAMefj3INDr.gif',
                'start_date' => 'February 28, 2022',
                'end_date' => 'March 07, 2022',                
                'website_link' => 'null',
                'twitter_link' => 'null',
                'discord_link'=> 'null',
                'marketplace' => 'opensea',
                'marketplace_link' => 'https://opensea.io/',
                'blockchain' => 'ethereum',
                'blockchain_link' => 'https://opensea.io/',
                'description' => 'Mocks are a collection of 5 burning hot NFTs living in the core of the blockchain. Each individual Ember is carefully curated from over 150 traits, along with some incredibly rare 1/1s that have traits that can\'t be found from any other Ember.Our vision is to create an amazing project that will shed light, joy, love, and creativity! Fire Sale ( WL ) members may mint an Ember for 0.1 ETH.Public Sale will be a Dutch Auction that will decrease from a maximum of 0.3 ETH to a minimum of 0.15 ETH.Our Fire Sale mint is on March 26, 2022 7:00 PM UTCOur Public Sale mint is on March 27, 2022 7:00 PM UTCOur official links are:https://twitter.com/embersnfthttps://instagram.com/embersnfthttps://embersnft.comThere you could find our roadmap! We\'ve already completed a couple things, such as: * Free NFT Giveaways* Donation of $50,000 to the Red Cross* We are also allocating 25% of mint funds for the longevity of the project, as well as 50% of royalties.We\'re implementing a DAO system so that every holder can have a say in the project. We are also adding staking, so that our members can earn tokens just by holding onto their Ember!Additionally, we are going to be awarding our holders with collaborations with other projects, as well as giving them airdrops and other special benefits!'
                ])
            ];
        
        self::$formats = [
            "en" => new Format([
                'date' => 'dd/mm/yyyy',
                'datetime' => 'dd/mm/yyyy HH:MM',
                'price' => '0,000,000.00 $',
                'eth_price' => 'E 0,000,000.00',
                'percentage' => '00.00 %',
                ])
            ];
        
        self::$distributions = [
            "minting_volume" => new Distribution([
                'name' => 'Top 10 volume distribution, 24h',
                'volume' => 100,
                'elements' => $elements
                /*
                'distribution_names' => array(
                    'Azie Infinity',
                    'Genesis Shapez',
                    'Morphs Official',
                    'Smfers',
                    'Houspets',
                    'Not your Bio',
                    'Stoned Pixel Human',
                    'Ninja Mfers Offiial',
                    'Noundles Game',
                    'Genesis Mana (for loot)'
                ),
                'distribution_values' => array(
                    40,
                    29,
                    8.93,
                    7.24,
                    6.14,
                    5.92,
                    5.12,
                    4.88,
                    3.21,
                    2.83
                ),
                'distribution_percent' => array(
                    40,
                    29,
                    8.93,
                    7.24,
                    6.14,
                    5.92,
                    5.12,
                    4.88,
                    3.21,
                    2.83
                )*/
                ])
            ];

        self::$collections = [
            //Empty collection to initialize the context
            0 => new Collection([
                'id' => 0,
                'name' => 'rarespotStuff',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'Only for Rarespotters',
                'short_description' => '',
                'volume' => 0,
                'contract' => 'rare_contract',
                'transfers' => 0,
                'minters' => 0,
                'market_cap' => 0,
                'floor_price' => 0,
                'avg_price' => 0,
                'owners' => 0,
                'circulating_supply' => 0,
                'volume_change' => 0,
                'market_cap_eth' => 0,
                'floor_price_change' => 0,
                'avg_price_change' => 0
            ]),
            1 => new Collection([
                'id' => 1,
                'name' => 'CryptoStuff',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'descr',
                'short_description' => 'descr',
                'volume' => 23.121,
                'contract' => 'test_contract',
                'transfers' => 23,
                'minters' => 23,
                'market_cap' => 23,
                'floor_price' => 23,
                'avg_price' => 23,
                'owners' => 23,
                'circulating_supply' => 23,
                'volume_change' => 23,
                'market_cap_eth' => 23.12,
                'floor_price_change' => 2.3,
                'avg_price_change' => -1.12
            ]),
            2 => new Collection([
                'id' => 2,
                'name' => '3D Avatars By Psychdre',
                'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                'description' => 'Witness an awesome collection that shows viewers what dynamic graphics and pure creativity looks like. They are all created with 3D graphic rendering and dynamic effects in each unique NFT digital art piece. The manifested collection is titled as 3D Avatars By Psychdre and is available on Rarespot.io.',
                'short_description' => '2D avatars & characters turned into 3D animations by motion graphics artist Psychdre.  

                Supply for every piece is ONE-of-ONE.
                
                DM @Psychdre.eth to request your avatar modeled! ',
                'volume' => 1,
                'contract' => 'test_contract',
                'transfers' => 23,
                'minters' => 23,
                'market_cap' => 23,
                'floor_price' => 23,
                'avg_price' => 23,
                'owners' => 23,
                'circulating_supply' => 23,
                'volume_change' => 23,
                'market_cap_eth' => 23.12,
                'floor_price_change' => 2.3,
                'avg_price_change' => -1.12
            ])
        ];

        for($i=3;$i<31;$i++){
            array_push(self::$collections, new Collection([
                    'id' => $i,
                    'name' => $i.'-D Avatars By Psychdre',
                    'img' => 'https://ipfs.io/ipfs/QmQqzMTavQgT4f4T5v6PWBp7XNKtoPmC9jvn12WPT3gkSE',
                    'description' => 'Available on Rarespot.io.',
                    'short_description' => 'modeled! ',
                    'volume' => 1,
                    'contract' => 'test_contract',
                    'transfers' => 23,
                    'minters' => 23,
                    'market_cap' => 23,
                    'floor_price' => 23,
                    'avg_price' => 23,
                    'owners' => 23,
                    'circulating_supply' => 23,
                    'volume_change' => 23,
                    'market_cap_eth' => 23.12,
                    'floor_price_change' => 2.3,
                    'avg_price_change' => -1.12
                ])
            );
        }
    }
   
   
    public static function findEvent(int $id): ?Event
    {
        global $eventSource, $pdo, $slogger, $class;

        if($eventSource != "MOCK"){
            if($pdo != null){
                $query = "
                    SELECT id, url_event, event_name, image_link, verified, start_date, end_date, website_link, 
                    twitter_link, discord_link, marketplace, marketplace_link, blockchain, blockchain_link, 
                    description, created_at, updated_at, deleted_at
                    FROM upcoming_events 
                    WHERE id = ".$id." ";
                $slogger->debug($class ,"Quering: ".$query);
                $stmt = $pdo->query($query);
                $res = $stmt->fetch();

                if($res != null) {
                    $slogger->debug($class ,"Fetching event ".$id." data: ".$slogger->arrayToString($res));
                    return self::populateEvent($res);
                }
            } else {
                $slogger->warn($class ,"No PDO");
                return null;
            }
        }
        return self::$events[$id] ?? null;
    }

    public static function findFormat(string $lang): ?Format
    {
        global $eventSource, $pdo, $slogger, $class;
        return self::$formats[$lang] ?? null;
    }


    public static function findEvents(int $limit): array
    {
        global $eventSource, $pdo, $slogger, $class, $event;
        $i = 1;
        $resArray = array();
        if($eventSource != "MOCK" || $eventSource != "RND"){
            if($pdo != null){
                $query = "
                    SELECT id, url_event, event_name, image_link, verified, start_date, end_date, website_link, 
                    twitter_link, discord_link, marketplace, marketplace_link, blockchain, blockchain_link, 
                    description, created_at, updated_at, deleted_at
                    FROM upcoming_events
                    WHERE TO_DATE(end_date, 'Month DD, YYYY') >= NOW()
                    ORDER BY TO_DATE(start_date, 'Month DD, YYYY') ;
                ";
                $slogger->debug($class ,"Quering: ".$query);
                foreach ($pdo->query($query) as $row) {
                    $slogger->debug($class ,"Fetching event ".$i." data: ".$slogger->arrayToString($row));
                    array_push($resArray, self::populateEvent($row));
                    $i++;
                }
                $slogger->debug($class ,"Total results: ".$i);
                return $resArray;
            } else {
                $slogger->warn($class ,"No PDO");
                return null;
            }
        }

        return array_slice(array_values(self::$events), 0, $limit);
    }
    
    public static function findCollection(int $id): ?Collection
    {   
        global $collectionSource, $pdo, $slogger, $class;

        if($collectionSource != "MOCK" && $id > 0){
            $query = "SELECT tokenname, tokenid FROM token_handler WHERE id = ".$id." ";
            $slogger->debug($class , "Quering: ".$query);
            $stmt = $pdo->query($query);
            $res = $stmt->fetch();

            //TODO: da capire perchè fa una prima chiamata a findCollection sempre con id=1
            //TODO: gestire il not found, ora da errore
            if($res != null) {
                $slogger->debug($class , "Returning token: ".$res["tokenname"]);
                return populateCollection($res);
            }

            return new Collection(array());
        }

        return self::$collections[$id] ?? null;
    }

    /**
     * @return array<int, Collection>
     */
    public static function findCollections(int $limit = 30, string $period = "7d"): array
    {
        global $collectionSource, $pdo, $slogger, $class, $mockType, $period_values;

        $collections = [];

        //match period with accepted values
        $period_minutes = 0;
        if(array_key_exists($period, $period_values)){
            $period_minutes = $period_values[$period];
        } else {
            $period_minutes = $period_values["7d"];
        }

        if($collectionSource == "PRD"){
            $currentDate = date("Y-m-d");
            $currentHour = date("h");
            $currentMinute = date("i");
            $currentTimestamp = time();

            //TODO add all the collection data info (img, etc.)
            $query = "SELECT id, tokenname, tokenid FROM token_handler LIMIT $limit";
            $slogger->debug($class , "Quering: ".$query);

            $i = 0;
            $calc = array();
            foreach ($pdo->query($query) as $row) {
                $slogger->debug($class , "Returning token: ".$row["tokenname"]);
                array_push($collections, self::populateCollection($row));
                $i++;
            }
            return $collections;
        }

        if($mockType == "RND"){
            global $rnd_colors, $rnd_img, $rnd_names, $fomo;
            for($i=1;$i<=$limit;$i++){
                $name = $rnd_colors[array_rand($rnd_colors,1)].' '.$rnd_names[array_rand($rnd_names,1)];
                $base_volume = mt_rand(0,300);
                $date_rnd= rand(1262055681,1262055681);
                array_push($collections, new Collection([
                        'id' => $i,
                        'name' => $name,
                        'img' => $rnd_img[array_rand($rnd_img,1)],
                        'description' => $name.' is now available on Rarespot.io.',
                        'short_description' => 'Collection '.$name.' launched.',
                        'volume' => mt_rand(0,300),
                        'contract' => '0x287850ee043155d8E4Aa6656478f1fB98f52D822',
                        'transfers' => mt_rand(0,100),
                        'minters' => mt_rand(0,100),
                        'market_cap' => mt_rand(0,100000),
                        'floor_price' => mt_rand(0,50)/100,
                        'avg_price' => mt_rand(0,50)/50,
                        'owners' => mt_rand(0,5000),
                        'circulating_supply' => mt_rand(0,20000),
                        'volume_change' => mt_rand(-100,100),
                        'market_cap_eth' => mt_rand(0,10000),
                        'floor_price_change' => mt_rand(-50,50)/100,
                        'avg_price_change' => mt_rand(-50,50)/100,
                        'volume_chart' => array(
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30),
                                $base_volume+=mt_rand(-30,+30)
                        ),
                        'first_mint' => date("Y-m-d",$date_rnd),
                        'fomo' => $fomo[mt_rand(0,2)],
                        'mints' => mt_rand(0,500),
                        'mints_change' => mt_rand(-50,50)/100,
                        'mint_volume' => mt_rand(00,500),
                        'mint_volume_change' => mt_rand(-100,200)/100,
                        'minters_change' => mt_rand(-50,50)/100,
                        'mint_whales' => mt_rand(0,100),
                        'mint_whales_change' => mt_rand(-50,50)/100,
                        'mint_cost' => mt_rand(0,50)/100,
                    ])
                );
            }

            return $collections;
        }

        return array_slice(array_values(self::$collections), 0, $limit);
    }


    public static function getCollectionAnalysis(string $contractAddress): array 
    {
        global $collectionSource, $pdo, $slogger, $class;
        $calc = self::initCalculatedValue();

        if($collectionSource == "PRD"){
            $q = "
                SELECT 
                    SUM(total_transfers) as transfers, 
                    AVG(avg_value) as average, 
                    SUM(tot_value) as total_value,
                    SUM(minters) as minters
                FROM minute_aggregate m
                WHERE contractaddress = '$contractAddress'
                LIMIT 1
            ";
            $s = $pdo->query($q);
            $tr = $s->fetch();
            $slogger->debug($class , "test transfers: ".$tr["transfers"]);
            $calc["minters"] = 1;
            $calc["transfers"] = $tr["transfers"] > 0 ? $tr["transfers"] : 1;
            $calc["volume"] = 1;
            $calc["market_cap"] = 1;
            $calc["floor_price"] = 1;
            $calc["avg_price"] = 1;
            $calc["owners"] = 1;
            $calc["circulating_supply"] = 1;
            $calc["volume_change"] = 1;
            $calc["market_cap_eth"] = 1;
            $calc["floor_price_change"] = 1;
            $calc["avg_price_change"] = 1;
            $calc["volume_chart"] = array();
            //...
        } 
        return $calc;
    }

    public static function populateCollection($row){
        $calc = self::getCollectionAnalysis($row["tokenid"]);
        $result = new Collection([
            'id' => intval($row["id"]), 
            'name' => $row["tokenname"],
            'contract' => $row["tokenid"],
            'volume' => intval($calc["volume"]),
            'transfers' => intval($calc["transfers"]),
            'minters' => intval($calc["minters"]),
            'market_cap' => intval($calc["market_cap"]),
            'floor_price' => intval($calc["floor_price"]),
            'avg_price' => intval($calc["avg_price"]),
            'owners' => intval($calc["owners"]),
            'circulating_supply' => intval($calc["circulating_supply"]),
            'volume_change' => intval($calc["volume_change"]),
            'market_cap_eth' => intval($calc["market_cap_eth"]),
            'floor_price_change' => intval($calc["floor_price_change"]),
            'avg_price_change' => intval($calc["avg_price_change"])
        ]);
        return $result;
    }

    public static function populateEvent($row){
        return new Event([
            'id' => intval($row["id"]),
            'url_event' => isset($row["url_event"]) ? $row["url_event"] : "",
            'event_name' => isset($row["event_name"]) ? $row["event_name"] : "",
            'image_link' => isset($row["image_link"]) ? $row["image_link"] : "",
            'start_date' => isset($row["start_date"]) ? $row["start_date"] : "",
            'end_date' => isset($row["end_date"]) ? $row["end_date"] : "",
            'website_link' => isset($row["website_link"]) ? $row["website_link"] : "",
            'twitter_link' => isset($row["twitter_link"]) ? $row["twitter_link"] : "",
            'discord_link'=> isset($row["discord_link"]) ? $row["discord_link"] : "",
            'marketplace' => isset($row["marketplace"]) ? $row["marketplace"] : "",
            'marketplace_link' => isset($row["marketplace_link"]) ? $row["marketplace_link"] : "",
            'blockchain' => isset($row["blockchain"]) ? $row["blockchain"] : "",
            'blockchain_link' => isset($row["blockchain_link"]) ? $row["blockchain_link"] : "",
            'description' => isset($row["description"]) ? $row["description"] : ""
        ]);
    }

    public static function initCalculatedValue(){
        $calc = array();    
        $calc["minters"] = 0;
        $calc["transfers"] = 0;
        $calc["volume"] = 0;
        $calc["market_cap"] = 0;
        $calc["floor_price"] = 0;
        $calc["avg_price"] = 0;
        $calc["owners"] = 0;
        $calc["circulating_supply"] = 0;
        $calc["volume_change"] = 0;
        $calc["market_cap_eth"] = 0;
        $calc["floor_price_change"] = 0;
        $calc["avg_price_change"] = 0;
        return $calc;
    }


    public static function findDistribution(string $id = "minting_volume"): ?Distribution
    {   
        global $collectionSource, $pdo, $slogger, $class, $elements;

        $slogger->debug($class ,"Finding distribution for id: ".$id);

        if($collectionSource == "MOCK"){
            $slogger->debug($class ,"Returning mock distribution for id: ".$id);
            return self::$distributions[$id] ?? null;
        }

        if($collectionSource == "RND"){
            global $rnd_colors, $rnd_img, $rnd_names, $fomo;
            $slogger->debug($class ,"Returning RND distribution for id: ".$id);
            
            if($id == "minting_volume"){

                $dist = new Distribution([
                    'name' => 'Top 10 volume distribution, 24h',
                    'volume' => 100,
                    'elements' => array([ new DistributionElement([
                        'id' => 1,
                        'name' => "1",
                        'value' => 1,
                        'format' => "1",
                        'value_percent' => 1,
                    ])
                    ])
                ]);
                
                $slogger->debug($class ,"Returning RND distribution: ".json_encode($dist));
                return $dist;

                /*$distribution_names = array();
                $distribution_values = array();
                $distribution_percent = array();
                $total_volume = 0;
    
                for($i=0;$i<=10;$i++){
                    $name = $rnd_colors[array_rand($rnd_colors,1)].' '.$rnd_names[array_rand($rnd_names,1)];
                    $base_volume = mt_rand(0,300);
                    array_push($distribution_names, $name);
                    array_push($distribution_values, number_format($base_volume, 2) );
                    $total_volume += $base_volume;
                }
    
                foreach($distribution_values as $val){
                    array_push($distribution_percent, number_format($val/$total_volume*100,2) );
                }
    
                return new Distribution([
                    'name' => 'Top 10 volume distribution, 24h',
                    'volume' => $total_volume,
                    'distribution_names' => $distribution_names,
                    'distribution_values' => $distribution_values,
                    'distribution_percent' => $distribution_percent
                ]);*/
            }
            
        }

        if($collectionSource == "PRD"){
            return null;
        }

        return self::$distributions[$id] ?? null;
    }

}