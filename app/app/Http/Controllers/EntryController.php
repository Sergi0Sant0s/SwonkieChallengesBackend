<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\HelperController as Helper;
use App\Models\Entry;
use Symfony\Component\HttpFoundation\Response;

class EntryController extends BaseController{

    protected $helper;

    public function __construct(Helper $help){
        $this->helper = $help;
    }
    
    #Download all videos
    public function downloadVideos(Request $request){

        #Checks if url exists
        if(isset($request->all()["url"]))
        {
            //Get rss url from html
            $rssUrl = $this->helper->getRssUrl($request->all()["url"]);
            //Get rss xml
            $rssXml = $this->helper->getRssFile($rssUrl);
            //Get data from xml
            $result = $this->helper->getDataFromXml($rssXml);

            //Send data to database
            return $this->helper->setData($result);
        }
        else{
            return response("Not found", 404);
        }
    }

    #Get all video
    public function getAllVideos(){
        return response(Entry::orderBy('published_at','DESC')->get(),200);
    }

    #Delete an video
    public function deleteVideos($id){
        $video = Entry::where('id',$id)->first();

        //Checks if video exists in database
        if($video == NULL)
            return response("Not Found",404);

        //Delete video from database
        Entry::destroy($id);

        return response("OK",200);
    }

    #Update an video
    public function editVideo($id,Request $request){
        $newTitle = $request->input('title');
        $newDescription = $request->input('description');
        $video = Entry::where('id',$id)->first();

        //Checks if exists title and description
        if(!isset($newTitle) || !isset($newDescription))
            return response("Not Acceptable",406);

        //Checks if video exists in database
        if($video == NULL)
            return response("Not Found",404);

        //Checks if title has text
        if($newTitle != '')
            $video->title = $newTitle;

        //Checks if description has text
        if($newDescription != '')
            $video->description = $newDescription;

        //update new data
        $video->save();

        return response("OK",200);
    }
}

?>
