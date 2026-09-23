--Create the project table
CREATE TABLE IF NOT EXISTS projects (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technologies VARCHAR(200),
    github_url VARCHAR(200)
);

--Insert sample project data
INSERT INTO projects (title, description, technologies, github_url)
VALUES 
(
    'Docker Compose Portfolio',
    'A portfolio website deployed using Docker Compose.',
    'Docker, Docker Compose, PHP, Apache, PostgreSQL',
    'https://github.com/MoinRafi11'
),
(
    'Automated Portfolio',
    'A dynamic portfolio automated using DevOps tools.',
    'Linux, Apache, PostgreSQL, Ansible',
    'https://github.com/MoinRafi11'
);