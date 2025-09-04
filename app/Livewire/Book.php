<?php

namespace App\Livewire;

use App\Models\Book as BookModel;
use Livewire\Component;

class Book extends Component
{
    public BookModel $book;

    public function mount(BookModel $book)
    {
        $this->book = $book->load(['genre']);
    }

    public function render()
    {
        $schemaMarkup = $this->getSchemaMarkup();
        return view('livewire.book', [
            'instances' => $this->book->instances()
                ->with(['owner'])
                ->orderByRaw("status = 'available' DESC")
                ->latest()
                ->get(),
        ])->layoutData([
            'title' => $this->book->title . ' by ' . $this->book->author . ' - Borrow & Reviews | Loop Your Book',
            'description' => "Discover '{$this->book->title}' by {$this->book->author}.",
            'schemaMarkup' => $schemaMarkup,
            'ogImage' => $this->book->cover_image
        ]);
    }

    public function getSchemaMarkup()
    {
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Book",
            "@id" => route('books.show', ['book' => $this->book->slug]),
            "name" => $this->book->title,
            "author" => [
                "@type" => "Person",
                "name" => $this->book->author
            ],
            "image" => [$this->book->cover_image],
            "description" => $this->book->description,
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => round($this->book->average_rating(), 1),
                "ratingCount" => $this->book->total_ratings(),
                "bestRating" => 5,
                "worstRating" => 1
            ],
            "url" => route('books.show', ['book' => $this->book->slug]),
            "review" => $this->getReviewsSchema()
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    private function getReviewsSchema()
    {
        return $this->book->summaries->map(function ($review) {
            return [
                "@type" => "Review",
                "author" => [
                    "@type" => "Person",
                    "name" => $review?->writer?->name
                ],
                "datePublished" => $review->created_at->format('Y-m-d'),
                "reviewBody" => $review->summary,
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => $review->rating,
                    "bestRating" => 5,
                    "worstRating" => 1
                ]
            ];
        })->toArray();
    }
}
