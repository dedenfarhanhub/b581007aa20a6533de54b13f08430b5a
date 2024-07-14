# Levart Email API

## Overview
This project implements a REST API for sending emails with authentication and message storage in a PostgreSQL database. The API utilizes PHP, Composer for package management, Docker for containerization, OAuth2 for authentication, and a message queue for email distribution.

## Features
- Send emails via a REST API endpoint.
- Authentication using OAuth2.
- Store sent emails in a PostgreSQL database.
- Utilize a message queue for email distribution.
- PSR standard compliance and code linting with phpcs.
- Docker and docker-compose for containerization.
- Good documentation for running the API.

## System Design
The system design includes the following components:
- **API Endpoint**: Exposes a POST endpoint `/email/send-email` for sending emails.
- **Authentication**: OAuth2 is used for authentication to access the API.
- **Message Queue**: Utilizes a message queue system for email distribution.
- **PostgreSQL Database**: Stores all sent messages/emails for tracking and auditing.
- **Code Structure**: Follows PSR standards and includes linting with phpcs.
- **Dockerization**: Uses Docker and docker-compose for containerization.

## Running the API
To run the API, follow these steps:
1. Clone the repository.
2. Set up your OAuth2 credentials and configure the [.env.docker](file:///var/www/html/levart-email-service/.env.docker#1%2C1-1%2C1) file.
3. Run `docker-compose up --build` to build and start the containers.
4. Access the API at `http://localhost:8200`.
5. Access the Swagger documentation at `http://localhost:8200/swagger.html#` for API documentation.

## Additional Setup Instructions
- **Copy `env.example`**: Before running the API, make a copy of `env.example` and rename it to `.env.docker`. Update the necessary environment variables in this file.
- **Setup with Makefile**: This project includes a Makefile that provides shortcuts for common tasks. Use `make <target>` to simplify setup and deployment processes.

## API Documentation
The API documentation is available in the `public/openapi.yaml` file. It describes the endpoints, request/response formats, and authentication requirements.

## Repository
The code for this project can be found in the [GitHub repository](https://github.com/levartech/fbfc3debc376e761e0e8ee09c61695b0).

Feel free to explore the code, system design, and documentation to understand the implementation of the Levart Email API.