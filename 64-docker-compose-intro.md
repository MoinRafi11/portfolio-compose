# Docker Compose Introduction – Multi-Container Portfolio

## Overview

This project demonstrates how to run a PHP portfolio application and a PostgreSQL database using **Docker Compose**.

Instead of manually creating and connecting individual containers, Docker Compose allows multiple services to be defined and managed from a single `docker-compose.yml` file.

### Technologies Used

- Docker
- Docker Compose
- PHP 8.2
- Apache
- PostgreSQL 15
- Linux
- YAML
- Git & GitHub

---

## Project Architecture

The application consists of two containers:

- `portfolio-web` — PHP + Apache application
- `portfolio-db` — PostgreSQL database

```text
                    Browser
                       |
                       | HTTP :8084
                       v
              +-------------------+
              |   portfolio-web   |
              | PHP + Apache      |
              +-------------------+
                       |
                       | PostgreSQL
                       | Docker Network
                       v
              +-------------------+
              |   portfolio-db    |
              | PostgreSQL 15     |
              +-------------------+
                       |
                       v
                  pgdata volume
```

The browser accesses the application through port `8084` on the Ubuntu VM.

---

## Project Structure

The project contains the following files and directories:

```text
portfolio-compose/
├── db/
│   └── init.sql
├── docker-compose.yml
├── Dockerfile
├── README.md
└── src/
    └── index.php
```

### Important Files

| File / Directory | Purpose |
|---|---|
| `docker-compose.yml` | Defines and connects the application services |
| `Dockerfile` | Builds the PHP + Apache application image |
| `src/index.php` | PHP portfolio application |
| `db/init.sql` | Creates and populates the PostgreSQL table |
| `README.md` | Project documentation |

![Project Structure](64-docker-compose-intro/01-project-structure.png)

---

## Docker Compose Configuration

The `docker-compose.yml` file defines two services:

- `portfolio-web`
- `portfolio-db`

The services communicate through a dedicated Docker bridge network:

```text
portfolio-network
```

The PostgreSQL data is stored in a named Docker volume:

```text
pgdata
```

This allows database data to persist even if the database container is recreated.

![Docker Compose Configuration](64-docker-compose-intro/02-docker-compose-file.png)

---

## Web Service

The web service is defined as:

```yaml
portfolio-web:
```

It builds its image using the project's `Dockerfile`:

```yaml
build:
  context: .
  dockerfile: Dockerfile
```

The container is explicitly named:

```yaml
container_name: portfolio-compose-web
```

The application is exposed on port `8084`:

```yaml
ports:
  - "8084:80"
```

This means:

```text
VM port 8084 → Container port 80
```

Apache listens on port `80` inside the container.

---

## Database Service

The database service uses the official PostgreSQL Alpine image:

```yaml
image: postgres:15-alpine
```

The container is named:

```yaml
container_name: portfolio-compose-db
```

PostgreSQL is configured using environment variables from the `.env` file.

The initialization script is mounted into:

```text
/docker-entrypoint-initdb.d/init.sql
```

PostgreSQL executes initialization scripts from this directory when the database is initialized.

---

## Environment Variables

Database configuration is stored in a `.env` file.

Example:

```env
PGDATABASE=portfolio
PGUSER=portfolio_user
PGPASSWORD=change_this_password
PGPORT=5432
```

The `.env` file is excluded from Git using `.gitignore`.

A safe template is provided through:

```text
.env.example
```

> Never commit real passwords or other sensitive credentials to GitHub.

---

## Building the Project

From the project directory:

```bash
cd ~/Personal-Repo/portfolio-compose
```

Build the Docker image using:

```bash
sudo docker compose build
```

The build completed successfully and produced the web application image:

```text
portfolio-compose-portfolio-web
```

![Docker Compose Build](64-docker-compose-intro/03-docker-compose-build.png)

---

## Starting the Services

The complete application can be built and started using:

```bash
sudo docker compose up -d --build
```

The `-d` option runs the containers in detached mode.

Docker Compose starts:

```text
portfolio-compose-web
portfolio-compose-db
```

The web service waits for PostgreSQL to become healthy before starting.

This is controlled by:

```yaml
depends_on:
  portfolio-db:
    condition: service_healthy
```

---

## Checking Running Containers

To check the status of the services:

```bash
sudo docker compose ps
```

The running services are:

```text
portfolio-compose-db
portfolio-compose-web
```

The database container is shown as:

```text
Up (healthy)
```

The web container exposes:

```text
0.0.0.0:8084 -> 80/tcp
```

![Docker Compose Services](64-docker-compose-intro/04-docker-compose-ps.png)

---

## Accessing the Portfolio

The portfolio can be accessed from a browser using the VM's IP address:

```text
http://<VM-IP>:8084
```

Example:

```text
http://192.168.1.16:8084
```

The application displays the DevOps portfolio homepage.

![Portfolio Homepage](64-docker-compose-intro/05-portfolio-homepage.png)

---

## Database-Backed Projects

The portfolio retrieves project information from PostgreSQL.

The `projects` table contains:

- Project title
- Description
- Technologies
- GitHub URL

The PHP application connects to PostgreSQL using PDO and retrieves the project records.

The application uses the following query:

```sql
SELECT id, title, description, technologies, github_url
FROM projects
ORDER BY id DESC;
```

The database-backed project cards are then displayed dynamically on the portfolio.

![Portfolio Projects](64-docker-compose-intro/06-portfolio-projects.png)

---

## How the Application Works

The complete request flow is:

```text
1. User opens the portfolio in a browser
                  |
                  v
2. Request reaches VM port 8084
                  |
                  v
3. Docker forwards port 8084 → port 80
                  |
                  v
4. Apache serves the PHP application
                  |
                  v
5. PHP connects to PostgreSQL
                  |
                  v
6. PostgreSQL returns project data
                  |
                  v
7. PHP renders the projects
                  |
                  v
8. Portfolio is displayed in the browser
```

---

## Useful Docker Compose Commands

### Build the application

```bash
sudo docker compose build
```

### Start the services

```bash
sudo docker compose up -d
```

### Build and start

```bash
sudo docker compose up -d --build
```

### Check service status

```bash
sudo docker compose ps
```

### View logs

```bash
sudo docker compose logs
```

### View web service logs

```bash
sudo docker compose logs portfolio-web
```

### View database logs

```bash
sudo docker compose logs portfolio-db
```

### Stop the services

```bash
sudo docker compose down
```

---

## Docker Compose Concepts Demonstrated

This project demonstrates the following Docker Compose concepts:

- Defining multiple services
- Building a custom Docker image
- Using official Docker images
- Container naming
- Port mapping
- Environment variables
- `.env` configuration
- Docker volumes
- Persistent PostgreSQL storage
- Docker bridge networks
- Service dependencies
- Health checks
- Container restart policies
- PHP and PostgreSQL communication
- Multi-container application deployment

---

## Result

The final application successfully runs as a multi-container Docker Compose project.

```text
              portfolio-web
                    |
                    | Docker Network
                    |
              portfolio-db
                    |
                    v
                 pgdata
```

The PHP/Apache application communicates with PostgreSQL through the Docker network, while PostgreSQL data is stored in the persistent `pgdata` volume.

The portfolio is accessible through:

```text
http://<VM-IP>:8084
```

This project provides a practical introduction to deploying and managing a multi-container application using Docker Compose.
