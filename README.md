# Book Loop

> A modern Laravel 12 + Livewire (Flux & Volt) application for discovering, sharing, loaning, reviewing, and discussing books – with AI-powered recommendations, social posts, genre exploration, pricing, and mobile-first UX.

---

## At a Glance

-   **Framework**: Laravel 12 (PHP 8.2)
-   **Realtime UI**: Livewire (Flux & Volt), Tailwind CSS 4, Vite 6
-   **Testing**: Pest 3 + PHPUnit
-   **Data**: Eloquent ORM (SQLite in-memory for tests; configurable DB engines)
-   **Key Domains**: Books, Copies (Instances), Requests, Loans, Summaries (Reviews of Books), User-to-User Reviews, Genres, Posts, AI Recommendations, Pricing
-   **Mobile-first**: Dedicated optimization layer (`MOBILE_OPTIMIZATION.md`)
-   **License**: MIT

Badges (add real ones later):

```
[PHP >=8.2] [Laravel 12] [Livewire] [Pest] [Vite] [Tailwind] [MIT]
```

---

## Table of Contents

1. Purpose & Vision
2. Core Features
3. Architecture Overview
4. Domain Model Summary
5. Data Flow & Lifecycle Examples
6. Routing Map
7. Frontend Build & Tooling
8. Installation & Setup
9. Environment Configuration
10. Database Migrations & Seeders
11. Working With Features (Loans, Pricing, Genres, AI, Posts, Reviews)
12. Testing Strategy
13. Performance & Optimization
14. Security & Data Integrity
15. Mobile-First Design Principles
16. Extensibility & Roadmap
17. Contributing
18. License

---

## 1. Purpose & Vision

Book Loop enables a community-driven ecosystem for physical (or virtual) book sharing, discovery, and social interaction. Users can:

-   List individual **Book Instances** they own (optionally with price or location).
-   Request, loan, and track the lifecycle of borrowing via a structured **Loan System**.
-   Write **Book Summaries** (with ratings) and **User Reviews** (peer reputation system).
-   Explore **Genres** and curated popular genre selection.
-   Engage socially via **Posts**, reactions, and threaded comments.
-   Receive **AI-powered book recommendations** personalized by preference and feedback.

---

## 2. Core Features

| Feature             | Summary                                                                   | Key Files / Docs                                          |
| ------------------- | ------------------------------------------------------------------------- | --------------------------------------------------------- |
| Book Catalog        | Store and categorize titles with slugged URLs and cached rating stats     | `app/Models/Book.php`                                     |
| Book Instances      | Per-user copy metadata: condition, optional price, geo/address            | `app/Models/BookInstance.php`, pricing migration          |
| Requests            | Structured flow for borrowing; captures requester info & messaging        | `app/Models/BookRequest.php`                              |
| Loans               | Full lifecycle with states (delivered → return_confirmed, disputes, lost) | `app/Models/BookLoan.php`, `BOOK_LOANS_IMPLEMENTATION.md` |
| Summaries           | User-written per-book summaries + rating sync to cached book stats        | `app/Models/BookSummary.php`                              |
| User Reviews        | Reputation system (public/private, transaction-linked)                    | `app/Models/UserReview.php`                               |
| Genres              | Slug-based navigation, homepage popular genres                            | `GENRE_SYSTEM_IMPLEMENTATION.md`, `app/Models/Genre.php`  |
| Pricing             | Optional decimal pricing on instances                                     | `PRICING_FEATURE_IMPLEMENTATION.md`                       |
| AI Recommendations  | Generated suggestions with feedback loop                                  | `app/Models/AiBookRecommendation.php`                     |
| Social Posts        | Post model + reactions, likes counters, nested comments                   | `app/Models/Post.php`                                     |
| Notifications       | (Infrastructure integrated, not fully documented here)                    | future section                                            |
| Mobile Optimization | Consistent touch-friendly UX                                              | `MOBILE_OPTIMIZATION.md`                                  |

Livewire Components (selection): `Books`, `Book`, `BookInstance`, `BookRequest`, `BookLoans`, `CreateBook`, `EditBookInstance`, `BookSummaries`, `WriteBookSummary`, `WriteUserReview`, `UserProfile`, `UserReviews`, `GenresList`, `GenreShow`, `PostsFeed`, `SinglePost`, `CreatePost`, `PostComments`, `PostReactions`, `AiBookRecommendation`, `HomePage`, `NotificationsPage`.

---

## 3. Architecture Overview

```
┌──────────────────────────────────────────────────────────┐
│ Presentation (Livewire Components + Blade Views)         │
│  • Reactive state & server-driven UI                     │
├──────────────────────────────────────────────────────────┤
│ Domain Models (Eloquent)                                 │
│  • Rich models with events (slugging, rating caches)     │
│  • Relationship-centric query design                     │
├──────────────────────────────────────────────────────────┤
│ Application Services (Loan, Request, Notification*)      │
│  • Encapsulate workflows / status transitions            │
├──────────────────────────────────────────────────────────┤
│ Persistence Layer (Migrations, Seeders, Factories)       │
│  • Explicit schema evolution & sample data               │
├──────────────────────────────────────────────────────────┤
│ Tooling & Ops (Composer scripts, Vite, Pest)             │
└──────────────────────────────────────────────────────────┘
```

Cross‑cutting concerns:

-   Event-driven statistic caching (books, users)
-   Slug generation (Books, Genres)
-   Soft deletes (Posts, BookLoans)
-   Mobile-first styling
-   Concurrent dev processes (serve, queue, logs, vite) via Composer `dev` script

---

## 4. Domain Model Summary

| Model                | Purpose                           | Notable Relationships / Behaviors                                                                      |
| -------------------- | --------------------------------- | ------------------------------------------------------------------------------------------------------ |
| Book                 | Canonical book metadata           | `hasMany BookInstance`, `hasMany BookSummary`, cached rating stats, slugging                           |
| BookInstance         | A user-owned copy                 | `belongsTo Book`, `belongsTo User(owner)`, `hasMany BookRequest`, `hasMany BookLoan`, optional pricing |
| BookRequest          | Borrow intent                     | Links to `Book`, `BookInstance`, `User (requester)`, `hasOne BookLoan`                                 |
| BookLoan             | Lifecycle of borrowing            | Status methods & scopes (`active`, `overdue`)                                                          |
| Genre                | Classification                    | `hasMany Book`, slug, active scope                                                                     |
| BookSummary          | User textual summary + rating     | Triggers rating stats recalculation on create/update/delete                                            |
| UserReview           | Peer reputation                   | Scopes for public, by rating, transaction linking                                                      |
| Post                 | Social sharing                    | Soft deletes, reaction/comment relations, counter maintenance                                          |
| AiBookRecommendation | AI suggestion artifact            | Feedback state helpers                                                                                 |
| User                 | Authentication + aggregated stats | Cached review metrics, loan grouping, star rating accessor                                             |

---

## 5. Data Flow & Lifecycle Examples

### Book Loan Lifecycle

1. Request accepted → Loan auto-created (`delivered` state) & instance marked borrowed.
2. Borrower confirms receipt → `received`.
3. Borrower marks progress → `reading` (optional).
4. Borrower returns → `returned` (await confirmation).
5. Owner confirms → `return_confirmed` (completed) OR denies → `return_denied` (potential dispute).
6. Edge states: `lost`, `disputed`.

### Rating Update

BookSummary create/update/delete → triggers `Book::updateRatingStats()` → updates `avg_rating` & `ratings_count` for fast read performance.

### Pricing Addition Flow

User adds/edits instance → optional `price` validated (`numeric|min:0`) → stored as nullable `decimal(8,2)` → displayed conditionally in cards/badges.

### Genre Discovery

`GenresList` with live search + pagination → select genre → `GenreShow` filtered & sorted book instances.

### AI Recommendation Feedback

User views recommended list → provides feedback (`saved`, `not_interested`, `already_read`) influencing future personalization (future service expansion).

---

## 6. Routing Map (Highlights)

Auth (guest): `/login`, `/register`, `/forgot-password`, `/reset-password/{token}`
Auth (verified): `/dashboard`, settings (`/settings/profile|password|appearance`)
Books: `/books` (browse), `/books/{book:slug}`, guest summary, write summary, edit instance, create, my-books, requests, loans
Copies: `/copies/{id}`, request a copy
Genres: `/genres`, `/genres/{genre:slug}`
Posts: `/book-posts`, `/book-posts/{post}`
AI: `/ai/book-recommendations`
Users: `/users/{userId}`, reviews, write-review flows
Misc: `/contact`, `/admin/user-queries`, notifications

Route model binding: slugs for books & genres; numeric IDs for others.

---

## 7. Frontend Build & Tooling

-   **Vite**: Hot module replacement & asset bundling (`vite.config.js`).
-   **Tailwind CSS 4**: Utility-first styling + custom mobile-first enhancements.
-   **Livewire Flux/Volt**: Modern reactive components without writing SPA JS.
-   **Composer `dev` script**: Concurrent processes (serve, queue listener, log tailing, Vite) via `npx concurrently`.

---

## 8. Installation & Setup

### Prerequisites

-   PHP >= 8.2, extensions: `pdo`, `mbstring`, `openssl`, `curl`, `json`, `fileinfo`
-   Composer >= 2.x
-   Node.js >= 20 (for Vite 6 & Tailwind 4)
-   SQLite (default dev/testing) or MySQL/PostgreSQL

### Quick Start (Local)

```bash
# Clone
git clone <repo-url> && cd book-loop

# PHP deps
composer install

# Node deps
npm install

# Environment
cp .env.example .env
php artisan key:generate

# (Optional) create SQLite file
touch database/database.sqlite

# Migrate & seed
php artisan migrate --graceful
php artisan db:seed

# Start full dev stack
composer run dev
```

Access: http://127.0.0.1:8000

### Common Artisan Tasks

```bash
php artisan migrate:fresh --seed
php artisan queue:listen
php artisan tinker
php artisan test
```

---

## 9. Environment Configuration

Minimal `.env` example (do not commit secrets):

```
APP_NAME="Book Loop"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=sqlite
# For other drivers
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_loop
DB_USERNAME=book_loop
DB_PASSWORD=secret

QUEUE_CONNECTION=database
SESSION_DRIVER=file
CACHE_STORE=file

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@bookloop.test"
MAIL_FROM_NAME="Book Loop"
```

Adjust DB\_\* for production; prefer `redis` for cache/queue at scale.

---

## 10. Database Migrations & Seeders

Chronological migrations cover: users, jobs, books, instances, requests, loans, summaries, queries, notifications, reviews, genres, posts, AI recommendations, preferences, slugs, rating caches, pricing.
Seeders available:

-   `DatabaseSeeder` (orchestrates)
-   `BookSeeder`, `BookInstanceSeeder`, `BookSummarySeeder`
-   `GenreSeeder`, `PopularGenresSeeder`
-   `PostSeeder`
-   `BookLoanSeeder`

Run specific:

```bash
php artisan db:seed --class=PopularGenresSeeder
php artisan db:seed --class=BookLoanSeeder
```

Factories exist for reproducible test data (see `database/factories`).

---

## 11. Working With Key Features

### Loans

-   Status helpers & scopes for dashboards (`BookLoan::active()`, `->is_overdue`).
-   Transition methods: `markAsReceived`, `markAsReading`, `markAsReturned`, `confirmReturn`, `denyReturn`, `markAsLost`, `markAsDisputed`.
-   Overdue detection via `due_date` comparisons.

### Pricing

-   Nullable `price` on `book_instances` (decimal 8,2).
-   Conditional UI rendering (no clutter for unpriced copies).

### Genres

-   Slug-based navigation with search, sort, counts.
-   Popular genres surfaced on homepage.

### Summaries (Book Reviews)

-   Rating triggers: event observers recalc cached stats for O(1) reads.
-   Breakdown retrieval via `Book::getRatingBreakdown()`.

### User Reviews (Peer Reputation)

-   Transaction-aware linking to loans or requests.
-   Cached aggregates on User (`avg_rating`, `reviews_count`).

### Posts & Reactions

-   Counter columns updated atomically via increment/decrement helpers.
-   Soft deletes preserve audit trail.

### AI Recommendations

-   Feedback flags (`saved`, `not_interested`, `already_read`) drive future personalization roadmap.

### Notifications & Queries

-   Scaffold present for contact/admin workflow & real-time updates.

---

## 12. Testing Strategy

-   **Framework**: Pest (Feature tests auto-using `RefreshDatabase`).
-   **Config**: In-memory SQLite for speed (`phpunit.xml`).
-   **Write Tests**: Place Feature tests in `tests/Feature`, Unit in `tests/Unit`.
-   **Expectations**: Extendable (example `toBeOne`).
-   **Command**: `php artisan test` or `composer test` (script also clears config cache).

Recommended patterns:

-   Use factories for creating books, instances, loans.
-   Assert model events (rating cache) by comparing pre/post values.
-   Test loan state transitions & permission gating.

---

## 13. Performance & Optimization

| Area            | Strategy                                                                    |
| --------------- | --------------------------------------------------------------------------- |
| Ratings         | Cached aggregates eliminate heavy joins on each page view                   |
| Slugs           | Unique slug generation avoids collisions / ensures stable URLs              |
| Overdue Queries | Scopes restrict computation to active loans                                 |
| Search Inputs   | Debounced Livewire search in genre & listing components                     |
| Mobile Payload  | Mobile-first CSS reduces initial render footprint                           |
| Concurrency     | Composer `dev` script parallelizes server, queue, Vite for faster iteration |

Future opportunities: caching genre lists, Redis-backed queues, HTTP response caching for home & genres, Horizon monitoring.

---

## 14. Security & Data Integrity

-   **Auth**: Email verification enforced (`MustVerifyEmail`).
-   **Authorization**: Route middleware `auth` protects settings, loan management, private actions.
-   **Validation**: Livewire component validation (e.g., numeric price, non-negative, required fields on requests).
-   **Soft Deletes**: Posts & Loans preserve historical integrity.
-   **Foreign Keys**: Enforced by migrations for requests, loans, reviews.
-   **Input Sanitization**: Relies on Laravel's mass assignment protection + explicit `fillable` arrays.

Hardening suggestions:

-   Add explicit Policies (e.g., `PostPolicy`) across domains.
-   Rate limiting for write-heavy endpoints.
-   Add audit logging for loan disputes & lost states.

---

## 15. Mobile-First Design Principles

Implemented (see `MOBILE_OPTIMIZATION.md`):

-   Touch targets ≥ 44px
-   Responsive grids (1–4 columns adaptively)
-   Typography scaling (14px base → 16px desktop)
-   Debounced searches & minimal horizontal scroll
-   Progressive enhancement via Tailwind breakpoints

---

## 16. Extensibility & Roadmap

Short-term:

-   Admin dashboards for disputes & content moderation.
-   Enhanced AI pipeline (personalized weighting of feedback).
-   Image optimization & cover CDN integration.
-   Notification preferences & digest emails.

Medium-term:

-   Loan reminders (scheduled tasks / queue jobs).
-   Recommendation explanation transparency & source weighting.
-   Public API (token-based) for book/genre retrieval.
-   WebSockets or Livewire + Echo for real-time updates.

Long-term:

-   Marketplace enhancements (dynamic pricing analytics, negotiation threads).
-   Elastic / Meilisearch integration for robust search.
-   Multi-tenancy or community group spaces.

---

## 17. Contributing

1. Fork & branch from `master`.
2. Run full test suite before PR: `php artisan test`.
3. Style: follow Laravel conventions; use Pint (`composer require --dev laravel/pint`) and run `./vendor/bin/pint`.
4. Add/update tests for any domain behavior changes.
5. Document feature additions in relevant implementation guide or README section.

Issue Templates & Actions: (See `.github/` for workflows – add CI for tests & static analysis as needed.)

---

## 18. License

MIT – See `composer.json` and attach a `LICENSE` file if distributing publicly.

---

## Quick Reference Cheat Sheet

| Task                 | Command                            |
| -------------------- | ---------------------------------- |
| Start Dev Stack      | `composer run dev`                 |
| Run Tests            | `php artisan test`                 |
| Fresh DB + Seed      | `php artisan migrate:fresh --seed` |
| Generate IDE Helpers | `php artisan ide-helper:generate`  |
| Tailwind/Vite Dev    | `npm run dev`                      |

---

## Edge Cases & Considerations

-   Loan overdue calculations use date boundaries; ensure timezone consistency (UTC recommended).
-   Slug collisions handled incrementally – high-volume imports may need batch slugging optimization.
-   Rating recalculation on high-frequency summary edits could be queued for scale.
-   Nullable pricing demands conditional UI – always check for truthy vs zero value.
-   Soft-deleted posts/loans should be excluded from public counts (ensure scopes in queries).

---

## Suggested Badges (Add Later)

-   GitHub Actions: CI (tests, lint)
-   Code Coverage
-   Laravel Pint status
-   Security scan (Dependabot / Enlightn)

---

## Final Notes

This README centralizes system knowledge from implementation guides:

-   `BOOK_LOANS_IMPLEMENTATION.md`
-   `GENRE_SYSTEM_IMPLEMENTATION.md`
-   `PRICING_FEATURE_IMPLEMENTATION.md`
-   `MOBILE_OPTIMIZATION.md`

For deep dives, consult those documents directly. Keep README concise yet exhaustive for onboarding.

Happy building! 🚀
