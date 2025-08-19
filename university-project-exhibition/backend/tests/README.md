# University Project Exhibition - Unit Testing Guide

This document provides information on how to run and write tests for the University Project Exhibition application.

## Overview

The application uses PHPUnit for testing within the Laravel framework. Tests are organized into two main categories:

1. **Unit Tests**: Testing individual components in isolation
2. **Feature Tests**: Testing complete HTTP requests and application behavior

## Directory Structure

```
/tests
├── Feature/
│   └── Controllers/
│       ├── AuthControllerTest.php
│       ├── ProjectControllerTest.php
│       └── StudentControllerTest.php
├── Unit/
│   └── Models/
│       ├── CollaboratorTest.php
│       ├── ProjectTest.php
│       └── StudentTest.php
├── CreatesApplication.php
├── README.md
└── TestCase.php
```

Note: The default Laravel example tests have been removed.

## Running Tests

### Run All Tests

```bash
php artisan test
```

### Run Specific Test File

```bash
php artisan test --filter=StudentTest
```

### Run Specific Test Method

```bash
php artisan test --filter=StudentTest::a_student_has_one_user_relationship
```

### Run Tests With Coverage Report

```bash
php artisan test --coverage
```

## Writing Tests

### Naming Conventions

- Test files should end with `Test.php`
- Test methods should begin with `test_` or use the `/** @test */` annotation
- Test method names should clearly describe what they're testing

### Example Test Structure

```php
/** @test */
public function a_student_has_one_user_relationship()
{
    // Arrange (set up test data)
    $student = Student::factory()->create();
    $user = User::factory()->create(['student_id' => $student->student_id]);
    
    // Act (perform the action)
    $relationship = $student->users;
    
    // Assert (verify the results)
    $this->assertInstanceOf(User::class, $relationship);
    $this->assertEquals($user->user_id, $relationship->user_id);
}
```

## Test Types

### Model Tests

Test the relationships, attributes, and methods of your models:

- Verify fillable attributes
- Test model relationships
- Test custom model methods

### Controller Tests

Test the API endpoints and their responses:

- Authentication and authorization
- CRUD operations
- Response structure and status codes
- Validation rules

## Test Data

The tests use Laravel's factory system to generate test data. Custom factory definitions can be found in the `/database/factories` directory.

## Database Testing

Tests use the `RefreshDatabase` trait which:

- Resets the database after each test
- Runs migrations before each test
- Ensures tests are isolated

## Mocking

For external services, use Laravel's mocking capabilities:

```php
// Example: Mocking a service
$mockService = $this->mock(ExampleService::class);
$mockService->shouldReceive('someMethod')->andReturn('mocked result');
```

## Continuous Integration

Tests run automatically on CI/CD pipelines when code is pushed to the repository.

## Best Practices

1. Tests should be independent of each other
2. Use descriptive test method names
3. Follow the AAA pattern: Arrange, Act, Assert
4. Test both happy paths and error cases
5. Keep tests focused and simple

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/10.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
