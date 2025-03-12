<?php

test("can list tags",function(){

    $response = $this->get("api/v1/tags");
    $response->assertStatus(200);
    $response->assertJsonStructure([
        
            "*" => [
                'tag name' , 
            ],
       
    ]);

});

test("can add tag", function(){
    $tag = [
        "tag name" => "saly"
    ];

    $response = $this->post("api/v1/tags",$tag);
    $response->assertStatus(201);
    $tag = $response->json('data');

    $this->assertDatabaseHas('tags',['name' => $tag['name']]);

});

test("can add a tag and delete it", function(){
    $tag = [
        "tag name" => "yara"
    ];

    $response = $this->post("api/v1/tags",$tag);
    $response->assertStatus(201);
    $tag = $response->json('data');

    $this->assertDatabaseHas('tags',['name' => $tag['name']]);

    $respone = $this->delete("api/v1/tags");

});