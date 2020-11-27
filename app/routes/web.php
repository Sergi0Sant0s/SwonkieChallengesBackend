<?php

/* API RESTFUL*/
$router->post("/videos", "EntryController@downloadVideos");
$router->get("/videos","EntryController@getAllVideos");
$router->delete("/video/{id}", "EntryController@deleteVideos");
$router->put("/video/{id}", "EntryController@editVideo");

/* DEFAULT PAGE */
$router->get(
    '/',
    function () use ($router) {
        return "Hello from swk dev team";
    }
);
