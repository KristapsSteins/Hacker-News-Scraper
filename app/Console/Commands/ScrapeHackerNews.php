<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ScrapeHackerNews extends Command
{
    protected $signature = 'scrape:hackernews {numArticles=0}';
    protected $description = 'Scrape data from Hacker News and save to database';

    private $client;
    private $counter = 0;
    private $newRecords = 0;
    private $updatedRecords = 0;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    public function handle()
    {
        $numArticles = $this->argument('numArticles');
        
        if ($numArticles < 1 || $numArticles > 500) {
            $this->error('Add a argument between 1 and 500 as an argument for how many articles you want to scrape.');
            return;
        }

        try {
            $allStories = $this->getAllStories();

            foreach ($allStories as $storyId) {
                if ($this->counter == $numArticles) {
                    break;
                }
                $this->processStory($storyId);
            }

            $this->displaySummary();
        } catch (RequestException $e) {
            $this->error('An error occurred while fetching data: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('An unexpected error occurred: ' . $e->getMessage());
        }
    }

    private function getAllStories()
    {
        $response = $this->client->request('GET', 'https://hacker-news.firebaseio.com/v0/topstories.json?print=pretty');
        return json_decode($response->getBody());
    }

    private function processStory($storyId)
    {
        $story = $this->getStory($storyId);
        $this->updateOrCreateArticle($story);
        $this->counter++;

        if ($this->counter % 10 == 0) {
            $this->info("Processed story number: {$this->counter}");
        }
    }

    private function getStory($storyId)
    {
        $response = $this->client->request('GET', "https://hacker-news.firebaseio.com/v0/item/{$storyId}.json?print=pretty");
        return json_decode($response->getBody());
    }

    private function updateOrCreateArticle($story)
    {
        $article = Article::updateOrCreate(
            ['id' => $story->id],
            [
                'title' => $story->title ?? '',
                'link' => $story->url ?? '',
                'points' => $story->score ?? 0,
                'date_created' => isset($story->time) ? date('Y-m-d H:i:s', $story->time) : null,
            ]
        );

        if ($article->wasRecentlyCreated) {
            $this->newRecords++;
        } else {
            $this->updatedRecords++;
        }
    }

    private function displaySummary()
    {
        $this->info("New records: {$this->newRecords}");
        $this->info("Updated records: {$this->updatedRecords}");
    }
}