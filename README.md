# Back-End Competence Assessment Test - Expat.com

This mini-project involves creating a web application for managing articles with categories. Below is an overview of what's included:

## Structure
- **Article**: Represents an article with fields `id`, `title`, and `content`.
- **Category**: Represents a category with fields `id`, `name`, and `description`.
- **Article_has_category**: Links articles to categories via their IDs.

## Functionality
1. **Migration Script**: Creates the necessary database tables and inserts initial data for categories (`emploi` and `immobilier`).
2. **Article Creation Form**: Provides a form to create articles with optional category selection. AJAX for form submission and validation.
3. **Article List Pages**:
    - By Category: Displays a list of articles belonging to a specific category.
    - All Articles: Displays a list of all articles.

## Technologies Used
- HTML / CSS
- JavaScript / jQuery (AJAX only)
- PHP 7.4
- Composer
- MySQL

## How to Run
1. Start a XAMPP web server for the correct PHP version (used: 7.4.33)
2. Clone the repository.
3. Execute the migration script to create database tables and initial data.
4. Run the PHP application using a local server.
