# University Project Exhibition API

This RESTful API is designed to manage university project exhibitions, allowing students to showcase their work, collaborate with others, and register their projects for events.

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL/MariaDB
- Docker & Docker Compose (optional)

### Installation

1. Clone the repository:
   ```
   git clone <repository_url>
   cd university-project-exhibition/backend
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Set up environment variables:
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure the database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=database
   DB_PORT=3306
   DB_DATABASE=your_database_name_here
   DB_USERNAME=your_database_username_here
   DB_PASSWORD=your_database_password_here
   ```

5. Run migrations:
   ```
   php artisan migrate
   ```

6. Start the server:
   ```
   php artisan serve
   ```

### Using Docker

Simply run:
```
docker-compose up -d
```

## API Documentation

### Authentication

All authenticated endpoints require a Bearer token which can be obtained through the login endpoint.

#### Register
```
POST /api/register
```
Request body:
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password",
  "student_id": 1
}
```

#### Login
```
POST /api/login
```
Request body:
```json
{
  "email": "john@example.com",
  "password": "password"
}
```
Response:
```json
{
  "user": {
    "user_id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "student_id": 1
  },
  "token": "your_auth_token"
}
```

#### Logout
```
POST /api/logout
```
Headers:
```
Authorization: Bearer your_auth_token
```

### Students API

#### List all students
```
GET /api/students
```

#### Get a specific student
```
GET /api/students/{id}
```

#### Create a student
```
POST /api/students
```
Request body:
```json
{
  "uni_id": "UNI123456",
  "name": "Jane Doe",
  "email": "jane@example.com",
  "image": "path/to/image.jpg", 
  "major": "Computer Science",
  "batch": "2025"
}
```

#### Update a student
```
PUT /api/students/{id}
```

#### Delete a student
```
DELETE /api/students/{id}
```

#### Import Students in Bulk
```
POST /api/students/bulk
```
Request body:
```json
{
  "students": [
    {
      "uni_id": "UNI123456",
      "name": "Jane Doe",
      "email": "jane@example.com",
      "major": "Computer Science",
      "batch": "2025"
    },
    {
      "uni_id": "UNI123457",
      "name": "John Smith",
      "email": "john@example.com",
      "major": "Data Science",
      "batch": "2025"
    }
  ]
}
```

### Projects API

#### List all projects
```
GET /api/projects
```

#### Get a specific project
```
GET /api/projects/{id}
```

#### Create a project
```
POST /api/projects
```
Request body:
```json
{
  "user_id": 1,
  "project_name": "AI Chatbot",
  "project_detail": "An AI-powered chatbot for customer service",
  "project_date": "2025-08-15",
  "project_link": "https://github.com/example/project",
  "project_image": "path/to/image.jpg"
}
```

#### Update a project
```
PUT /api/projects/{id}
```

#### Delete a project
```
DELETE /api/projects/{id}
```

### Collaborators API

#### List all collaborators
```
GET /api/collaborators
```

#### Get a specific collaborator
```
GET /api/collaborators/{id}
```

#### Create a collaborator
```
POST /api/collaborators
```

#### Update a collaborator
```
PUT /api/collaborators/{id}
```

#### Delete a collaborator
```
DELETE /api/collaborators/{id}
```

### Collaborator-Project Relationship API

#### Add a collaborator to a project
```
POST /api/collaborator-project
```
Request body:
```json
{
  "project_id": 1,
  "collaborator_id": 2
}
```

#### Remove a collaborator from a project
```
DELETE /api/collaborator-project/{project_id}/{collaborator_id}
```

#### Get all collaborators for a project
```
GET /api/project/{project_id}/collaborators
```

#### Get all projects for a collaborator
```
GET /api/collaborator/{collaborator_id}/projects
```

### Data Models

#### Student
- `student_id`: int (Primary Key)
- `uni_id`: string
- `name`: string
- `email`: string
- `image`: string (nullable)
- `major`: string
- `batch`: string

#### User
- `user_id`: int (Primary Key)
- `name`: string
- `email`: string
- `student_id`: int (Foreign Key to Students)
- `password`: string (hashed)

#### Project
- `project_id`: int (Primary Key)
- `user_id`: int (Foreign Key to Users)
- `project_name`: string
- `project_detail`: text
- `project_date`: date
- `project_link`: string
- `project_image`: string
- `popularity`: integer
- `liked`: boolean

#### Collaborator
- `collaborator_id`: int (Primary Key)
- Various fields related to collaborators

## License

This project is licensed under the MIT License.
