<?php

$host = getenv('PGHOST');
$db   = getenv('PGDATABASE');
$user = getenv('PGUSER');
$pass = getenv('PGPASSWORD');
$port = getenv('PGPORT');

$projects = [];
$error = null;

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->query(
        "SELECT id, title, description, technologies, github_url
         FROM projects
         ORDER BY id DESC"
    );

    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Database connection failed.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DevOps Portfolio</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            line-height: 1.6;
        }

        header {
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            background: #181818;
        }

        .hero {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 15px;
        }

        .hero h1 span {
            color: #aaa;
        }

        .hero p {
            color: #bbb;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: 1px solid #777;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #fff;
            color: #111;
        }

        section {
            max-width: 1100px;
            margin: auto;
            padding: 70px 20px;
        }

        section h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            border-left: 4px solid #777;
            padding-left: 12px;
        }

        .about {
            color: #bbb;
            max-width: 800px;
        }

        .projects {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .project {
            background: #1b1b1b;
            border: 1px solid #333;
            border-radius: 10px;
            padding: 25px;
            transition: transform 0.3s, border-color 0.3s;
        }

        .project:hover {
            transform: translateY(-5px);
            border-color: #777;
        }

        .project h3 {
            margin-bottom: 12px;
        }

        .project p {
            color: #aaa;
            margin-bottom: 15px;
        }

        .tech {
            color: #ccc;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .project a {
            color: #fff;
            text-decoration: none;
        }

        .error {
            background: #2a1a1a;
            border: 1px solid #633;
            padding: 15px;
            border-radius: 6px;
            color: #e8b0b0;
        }

        footer {
            text-align: center;
            padding: 30px 20px;
            background: #0d0d0d;
            color: #777;
        }
    </style>
</head>

<body>

<header>
    <div class="hero">
        <h1>DevOps <span>Portfolio</span></h1>

        <p>
            Linux • Docker • Docker Compose • Apache • PostgreSQL • Ansible
        </p>

        <a href="#projects" class="btn">
            View Projects
        </a>
    </div>
</header>


<section id="about">

    <h2>About Me</h2>

    <div class="about">
        <p>
            I am a developer interested in DevOps, Linux, automation,
            containerization and backend technologies. This portfolio
            demonstrates a PHP application running with Apache and
            PostgreSQL through Docker Compose.
        </p>
    </div>

</section>


<section id="projects">

    <h2>Projects</h2>

    <?php if ($error): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php elseif (empty($projects)): ?>

        <p>No projects found.</p>

    <?php else: ?>

        <div class="projects">

            <?php foreach ($projects as $project): ?>

                <div class="project">

                    <h3>
                        <?= htmlspecialchars($project['title']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($project['description']) ?>
                    </p>

                    <div class="tech">
                        <?= htmlspecialchars($project['technologies']) ?>
                    </div>

                    <a
                        href="<?= htmlspecialchars($project['github_url']) ?>"
                        target="_blank"
                    >
                        View on GitHub →
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>


<footer>

    <p>
        © <?= date('Y') ?> DevOps Portfolio
    </p>

</footer>

</body>

</html>