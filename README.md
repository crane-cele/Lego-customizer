# Lego Collection Customizor

Welcome to the Lego Collection Customizer project! This application is designed to help Lego enthusiasts manage their Lego collections effectively. It features a backend built with Laravel providing a robust API and a frontend powered by React for a dynamic user interface.

## Project Overview

The Lego Collection Management application allows users to explore, customize, and manage Lego sets, parts, and themes. Leveraging the Rebrickable API, users can fetch detailed information about Lego items and organize their collections efficiently through an intuitive web interface.

## Features

- Fetch Lego Data: Retrieve comprehensive details about Lego sets, parts, and themes from Rebrickable.
- Customization: Customize parts within specific Lego sets to suit personal preferences.
- Exploration: Explore various Lego themes and categories to discover new sets and parts.
- User-Friendly Interface: Provides a seamless and intuitive frontend for easy navigation and interaction.

## Tech Stack

### Backend

- PHP: Server-side scripting language.
- Laravel: PHP framework for building robust web applications.
- MySQL: Relational database management system.

### Frontend

- JavaScript: Programming language for web development.
- React: JavaScript library for building user interfaces.
- Axios: Promise-based HTTP client for making API requests.

## Installation

### Prerequisites

Ensure you have the following installed on your machine:

- PHP >= 8.3
- Composer: PHP dependency manager.
- Node.js: JavaScript runtime.
- npm or yarn: Package managers for Node.js.
- MySQL: Database server.

### Backend Setup

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd <repository-directory>/backend
   ```

2. Install dependencies:

   ```bash
   composer install
   ```

3. Configure environment variables:

   ```bash
   cp .env.example .env
   ```

   Update `.env` with your database credentials and other settings.

4. Generate application key:

   ```bash
   php artisan key:generate
   ```

5. Run database migrations:

   ```bash
   php artisan migrate
   ```

6. Start the Laravel development server:

   ```bash
   php artisan serve
   ```

   The backend server will start at `http://localhost:8000`.

### Frontend Setup

1. Navigate to the frontend directory:

   ```bash
   cd <repository-directory>/frontend
   ```

2. Install dependencies:

   ```bash
   npm install
   ```

3. Start the development server:

   ```bash
   npm start
   ```

   The frontend application will be accessible at `http://localhost:3000`.

## Usage

### Accessing the Application

- Backend API: Use `http://localhost:8000` to interact with the backend API.
- Frontend Application: Navigate to `http://localhost:3000` to access the frontend interface.

### API Endpoints

- GET `/api/lego/sets`: Retrieve a list of Lego sets.
- GET `/api/lego/sets/{set_num}`: Retrieve details about a specific Lego set.
- GET `/api/lego/sets/{set_num}/parts`: Retrieve parts of a specific Lego set.
- GET `/api/lego/parts`: Retrieve a list of Lego parts.
- GET `/api/lego/themes`: Retrieve a list of Lego themes.
- POST `/api/lego/sets/{set_num}/parts`: Customize parts in a specific Lego set.

## Frontend Structure

The frontend application is structured as follows:

- Components: Reusable UI components.
- Pages: Different pages for routing and rendering views.
- Services: API service files for handling HTTP requests.

## Contributing

Contributions to the Lego Collection Management project are welcome! Follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Submit a pull request.
# Lego-customizer
