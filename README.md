# Payment Gateway Traffic Splitter

## Introduction

This project provides a solution for routing transactions between multiple payment gateways with different traffic distribution requirements.  The solution is written in PHP 8.2 and uses the Symfony framework. You can find the OpenAPI documentation at `/api/doc`. To get started quickly, you can set up the application using Docker Compose.

## Getting Started

1. Clone this repository to your local machine:

   ```bash
   git clone git@github.com:klentak/PaymentTrafficSplit.git
   cd PaymentTrafficSplit
   ```

2. Start the application using Docker Compose:

   ```bash
   docker-compose up -d
   ```

3. Access the Docker container by running a shell inside it:

   ```bash
   docker exec -it paymenttrafficsplit-php-1 sh
   ```

5. Install project dependencies using Composer:

   ```bash
   composer install
   ```

   This will install the required PHP dependencies within the Docker container.

6. Seed the database using Doctrine Fixtures:

   ```bash
   php bin/console doctrine:fixtures:load
   ```

   This command will populate the database with initial data, including payment gateways and their weights.

7. Access the application and API documentation:

    - Application: [http://localhost](http://localhost)
    - OpenAPI Documentation: [http://localhost/api/doc](http://localhost/api/doc)

With the database seeded and dependencies installed, you can now use the `TrafficSplit` class to route payments among payment gateways based on the specified weights as described in the previous instructions.

Thank you for pointing out the correction, and I apologize for any confusion. If you have any further questions or need assistance, feel free to reach out.
