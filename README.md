# Visitor Analytics & Sensor Management API

A robust Laravel-based backend API designed to power an admin dashboard for tracking visitor analytics and managing locations and sensors. This project demonstrates clean API design, efficient data modeling, and best practices in Laravel application development.

## Architecture & Design Patterns

### Repository Pattern Implementation
The project implements the Repository Pattern to abstract the data layer and provide a more flexible and maintainable architecture:

- **Benefits**:
  - Separation of concerns between data access and business logic
  - Centralized data access logic
  - Easier unit testing through dependency injection
  - Consistent data access patterns across the application
  - Simplified maintenance and code reuse

- **Implementation**:
  ```
  app/
  ├── Repositories/
  │   ├── BaseRepository.php      # Abstract base repository
  │   ├── LocationRepository.php  # Location-specific queries
  │   ├── SensorRepository.php    # Sensor-specific queries
  │   └── VisitorRepository.php   # Visitor-specific queries
  ```

### Service Layer
The service layer acts as an intermediary between controllers and repositories:

- **Responsibilities**:
  - Business logic implementation
  - Cache management
  - Data transformation
  - Error handling
  - Access control

- **Implementation**:
  ```
  app/
  ├── Services/
  │   ├── LocationService.php    # Location business logic
  │   ├── SensorService.php      # Sensor business logic
  │   ├── VisitorService.php     # Visitor business logic
  │   └── SummaryService.php     # Analytics summary logic
  ```

## Technical Stack

- **Framework**: Laravel
- **Database**: MySQL
- **Cache**: Redis
- **Web Server**: Nginx
- **Containerization**: Docker
- **Testing**: Pest
- **API Documentation**: Postman

## Project Structure

```
sensor-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── LocationController.php
│   │   │   ├── SensorController.php
│   │   │   ├── VisitorController.php
│   │   │   └── SummaryController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Location.php
│   │   ├── Sensor.php
│   │   └── Visitor.php
│   ├── Repositories/
│   │   ├── BaseRepository.php
│   │   ├── LocationRepository.php
│   │   ├── SensorRepository.php
│   │   └── VisitorRepository.php
│   └── Services/
│       ├── LocationService.php
│       ├── SensorService.php
│       ├── VisitorService.php
│       └── SummaryService.php
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── docker/
├── routes/
│   └── api.php
└── tests/
    ├── Unit/
    └── Feature/
```

## Prerequisites

- PHP 8.3.14 or higher
- Composer
- Docker and Docker Compose
- MySQL 8.0 or higher
- Redis 6.0 or higher

## Installation

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd sensor-api
   ```

3. Configure your environment variables in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=sensor_api
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   REDIS_HOST=redis
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   CACHE_TTL=600
   CACHE_COUNTER_TTL=86400
   CACHE_COUNTER_THRESHOLD=10
   ```

4. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

## Testing

Run the test suite:
```bash
docker-compose exec app php artisan test
```

### Testing Strategy

The project implements a comprehensive testing strategy using PHPUnit and Laravel's testing features:

#### 1. Feature Tests

##### Location Controller Tests (`tests/Feature/Controller/LocationControllerTest.php`)
- **List Locations Test**
  - Scenario: Fetch all locations
  - Validates: 
    - 200 OK response
    - Correct JSON structure
    - Location data format
    - Pagination metadata

- **Create Location Test**
  - Scenario: Create new location
  - Validates:
    - 201 Created response
    - Location name uniqueness
    - Required fields (name)
    - String length constraints (max 50 chars)
    - Database persistence

##### Sensor Controller Tests (`tests/Feature/Controller/SensorControllerTest.php`)
- **List Sensors Test**
  - Scenario: Fetch all sensors
  - Validates:
    - 200 OK response
    - Sensor data structure
    - Location relationship
    - Status field presence

- **Create Sensor Test**
  - Scenario: Create new sensor
  - Validates:
    - 201 Created response
    - Required fields (name, status, location_id)
    - Status enum values (active, inactive, maintenance)
    - Location existence
    - Database persistence

##### Visitor Controller Tests (`tests/Feature/Controller/VisitorControllerTest.php`)
- **List Visitors Test**
  - Scenario: Fetch visitor records
  - Validates:
    - 200 OK response
    - Date-based filtering
    - Count field presence
    - Sensor relationship
    - Location relationship

- **Create Visitor Test**
  - Scenario: Create visitor record
  - Validates:
    - 201 Created response
    - Required fields (sensor_id, date, count)
    - Date format (Y-m-d)
    - Sensor existence
    - Count validation (min: 0)
    - Unique date per sensor
    - Database persistence

##### Summary Controller Tests (`tests/Feature/Controller/SummaryControllerTest.php`)
- **Get Summary Test**
  - Scenario: Fetch summary statistics
  - Validates:
    - 200 OK response
    - Visitor count aggregation
    - Sensor status counts
    - Cache behavior
    - Data freshness

## API Documentation

The API documentation and Postman collection are available in two places:

1. Online Documentation:
   [Postman Collection](https://documenter.getpostman.com/view/43461066/2sB2x6nCMf)

2. Local Collection:
   The Postman collection file is also available in the project root under `postman/API.postman_collection.json`

You can import either version into Postman to test all available endpoints.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.