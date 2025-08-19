<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string|null $isbn
 * @property string|null $published_date
 * @property string|null $description
 * @property string|null $cover_image
 * @property string $language
 * @property int|null $pages
 * @property string|null $publisher
 * @property \App\Models\Genre|null $genre
 * @property int|null $genre_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cover_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookInstance> $instances
 * @property-read int|null $instances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $loans
 * @property-read int|null $loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookSummary> $summaries
 * @property-read int|null $summaries_count
 * @method static \Database\Factories\BookFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublishedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Book whereUpdatedAt($value)
 */
	class Book extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $book_id
 * @property int $owner_id
 * @property string|null $condition_notes
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $city
 * @property string|null $address
 * @property string|null $lat
 * @property string|null $lng
 * @property-read \App\Models\BookLoan|null $activeLoan
 * @property-read \App\Models\Book $book
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $loans
 * @property-read int|null $loans_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookRequest> $requests
 * @property-read int|null $requests_count
 * @method static \Database\Factories\BookInstanceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereConditionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookInstance whereUpdatedAt($value)
 */
	class BookInstance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $book_request_id
 * @property int $book_id
 * @property int $book_instance_id
 * @property int|null $borrower_id
 * @property int $owner_id
 * @property \Illuminate\Support\Carbon|null $delivered_date
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $return_date
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\BookInstance $bookInstance
 * @property-read \App\Models\BookRequest $bookRequest
 * @property-read \App\Models\User|null $borrower
 * @property-read mixed $days_overdue
 * @property-read mixed $days_until_due
 * @property-read mixed $is_active
 * @property-read mixed $is_overdue
 * @property-read \App\Models\User $owner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan byStatus($status)
 * @method static \Database\Factories\BookLoanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan forBorrower($borrowerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan forOwner($ownerId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan overdue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereBookInstanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereBookRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereBorrowerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereDeliveredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookLoan withoutTrashed()
 */
	class BookLoan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $book_id
 * @property int|null $book_instance_id
 * @property int|null $user_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $address
 * @property string|null $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\BookInstance|null $bookInstance
 * @property-read \App\Models\BookLoan|null $loan
 * @property-read \App\Models\User|null $owner
 * @property-read \App\Models\User|null $requester
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\BookRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereBookInstanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookRequest whereUserId($value)
 */
	class BookRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $book_id
 * @property int $user_id
 * @property string $summary
 * @property int|null $rating Optional rating 1-5
 * @property string|null $meta For future extensibility, e.g. tags, likes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Book $book
 * @property-read \App\Models\User $writer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BookSummary whereUserId($value)
 */
	class BookSummary extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Book> $books
 * @property-read int|null $books_count
 * @property-read string $display_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre orderByName()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Genre whereUpdatedAt($value)
 */
	class Genre extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $book_id
 * @property string $body
 * @property string $visibility
 * @property-read int|null $reactions_count
 * @property-read int|null $comments_count
 * @property int $likes_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostComment> $allComments
 * @property-read int|null $all_comments_count
 * @property-read \App\Models\Book|null $book
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostComment> $comments
 * @property-read mixed $time_ago
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostReaction> $reactions
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PostFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCommentsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereReactionsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTrashed()
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property int|null $parent_id
 * @property string $content
 * @property bool $is_edited
 * @property \Illuminate\Support\Carbon|null $edited_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $time_ago
 * @property-read PostComment|null $parent
 * @property-read \App\Models\Post $post
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostReaction> $reactions
 * @property-read int|null $reactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PostComment> $replies
 * @property-read int|null $replies_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment replies()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment topLevel()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereIsEdited($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostComment withoutTrashed()
 */
	class PostComment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $reactable_type
 * @property int $reactable_id
 * @property string $reaction_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $display_name
 * @property-read mixed $emoji
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $reactable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction byUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction ofType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereReactableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereReactableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereReactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostReaction whereUserId($value)
 */
	class PostReaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property int $reviews_count
 * @property string|null $avg_rating Average rating with 2 decimal places
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $address
 * @property string|null $city
 * @property string|null $lat
 * @property string|null $lng
 * @property string|null $state
 * @property string|null $postal_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $borrowedLoans
 * @property-read int|null $borrowed_loans_count
 * @property-read string $star_rating
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserReview> $givenReviews
 * @property-read int|null $given_reviews_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BookLoan> $ownedLoans
 * @property-read int|null $owned_loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostComment> $postComments
 * @property-read int|null $post_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostReaction> $postReactions
 * @property-read int|null $post_reactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserReview> $receivedReviews
 * @property-read int|null $received_reviews_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvgRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereReviewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $email
 * @property string|null $subject
 * @property string $message
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserQuery whereUserId($value)
 */
	class UserQuery extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $reviewed_user_id
 * @property int $reviewer_user_id
 * @property int $rating Rating from 1 to 5
 * @property string|null $review
 * @property string|null $transaction_type book_request, book_loan, general, etc.
 * @property int|null $transaction_id ID of related transaction (book_request_id, book_loan_id, etc.)
 * @property bool $is_public
 * @property \Illuminate\Support\Carbon $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $star_rating
 * @property-read string $summary
 * @property-read \App\Models\User $reviewedUser
 * @property-read \App\Models\User $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview byRating($rating)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview byTransactionType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview byUser($userId)
 * @method static \Database\Factories\UserReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview public()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereReviewedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereReviewerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserReview whereUpdatedAt($value)
 */
	class UserReview extends \Eloquent {}
}

