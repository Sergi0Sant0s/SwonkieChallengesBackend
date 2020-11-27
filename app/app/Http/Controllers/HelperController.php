<?php 

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Youtuber;
use App\Models\Entry;


class HelperController extends BaseController{

    public function __construct(){
        
    }

    #Get url with id based on youtuber link
    public function getRssUrl($url){
            $rssUrl = "";
            $file = $url;

            //Read HTML File
            $rss = new \DOMDocument();
            $rss->loadHTMLFile($file, LIBXML_NOWARNING | LIBXML_NOERROR);
            $x = new \DOMXPath($rss);

            //XPath RSS        
            foreach($x->query("//link[@title='RSS']")  as $node)
                return $node->getAttribute("href");
    }

    #Get RSS file
    public function getRssFile($url){

        //XML Parse
        $xml_doc = new \DOMDocument();
        $xml_doc->preserveWhiteSpace = false;
        $xml_doc->load($url);

        return $xml_doc;
    }

    #Get data from xml
    public function getDataFromXml($rssXml){
        $entrys = array();

        $data = array("youtuber"=>
                array(  "id" => $rssXml->getElementsByTagName('feed')->item(0)->childNodes->item(2)->nodeValue,
                        "name" => $rssXml->getElementsByTagName('name')->item(0)->nodeValue,
                        "url" => $rssXml->getElementsByTagName('uri')->item(1)->nodeValue,
                        "create_at" => $rssXml->getElementsByTagName('feed')->item(0)->childNodes->item(6)->nodeValue));
        
        foreach($rssXml->getElementsByTagName('entry') as $entry){
            array_push($entrys,
            array(
             "id"=> $entry->childNodes->item(1)->nodeValue,
             "link" => $entry->childNodes->item(4)->getAttribute('href'),
             "title" => $entry->childNodes->item(8)->childNodes->item(0)->nodeValue,
             "thumbnail" => $entry->childNodes->item(8)->childNodes->item(2)->getAttribute('url'),
             "description" => $entry->childNodes->item(8)->childNodes->item(3)->nodeValue,
             "views" => $entry->childNodes->item(8)->childNodes->item(4)->childNodes->item(1)->getAttribute('views'),
             "published_at" => $entry->childNodes->item(6)->nodeValue,
             "updated_at" => $entry->childNodes->item(7)->nodeValue
            ));
        }
        $data["entrys"] = $entrys;

        return $data;
    }

    #Send data to database
    public function setData($data){
        $youtuber = $data["youtuber"];
        $entrys = $data["entrys"];

        try{     
            //Checks if video exists
            $youtuberExists = Youtuber::where('id',$youtuber["id"])->first();
            if($youtuberExists == NULL){
                //set the youtuber
                $yt = Youtuber::create([
                    'id' => $youtuber["id"],
                    'name' => $youtuber["name"],
                    'url' => $youtuber["url"],
                    'create_at' =>  new \DateTime($youtuber["create_at"],new \DateTimeZone('Europe/Berlin'))
                ]);
                $yt->save();
            }
            else{
                $youtuberExists->name = $youtuber["name"];
                $youtuberExists->save();
            }            

            //set the entrys
            foreach($entrys as $entry){
                //Checks if video exists
                $videoExists = Entry::where('id',$entry["id"])->first();

                if($videoExists == NULL){
                    $result = Entry::create([
                        'id' => $entry["id"],
                        'youtuber_id' => $youtuber["id"],
                        'title' => $entry["title"],
                        'description' => $entry["description"],
                        'thumbnail' => $entry["thumbnail"],
                        'views' => $entry["views"],
                        'link' => $entry["link"],
                        'published_at' => new \DateTime($entry["published_at"],new \DateTimeZone('Europe/Berlin')),
                        'updated_at' => new \DateTime($entry["updated_at"],new \DateTimeZone('Europe/Berlin'))
                    ]);
                    $result->save();
                }
                else{
                    $videoExists->title = $entry["title"];
                    $videoExists->description = $entry["description"];
                    $videoExists->thumbnail = $entry["thumbnail"];
                    $videoExists->views = $entry["views"];
                    $videoExists->link = $entry["link"];
                    $videoExists->published_at = new \DateTime($entry["published_at"],new \DateTimeZone('Europe/Berlin'));
                    $videoExists->updated_at = new \DateTime($entry["updated_at"],new \DateTimeZone('Europe/Berlin'));
                    $videoExists->save();
                }
            }

            //Result
            return response($data, 201);
        }catch(Exception $e){
            return response($e->getMessage(), 500);
        }
    }
}
?>
