<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;

class NewsControllerTest extends TestCase
{
    public function testFetchNewsData()
    {
        Article::factory()->count(5)->create();

        $response = $this->post('/get-newsData');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'link',
                    'points',
                    'date_created',
                ]
            ],
        ]);
    }

    // public function testDeleteArticle()
    // {
    //     $article = Article::factory()->create();

    //     $response = $this->delete("/delete-news/{$article->id}");

    //     $response->assertStatus(200);

    //     $response->assertJson([
    //         'status' => true,
    //         'message' => 'News item deleted successfully'
    //     ]);

    //     $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    // }
}