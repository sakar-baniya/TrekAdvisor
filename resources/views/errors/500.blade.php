<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mountain Storm: 500 Server Error - TrekAdvisor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --color-primary: #2C3E50;
            --color-primary-light: #3f5973;
            --color-error: #e74c3c;
            --color-text-primary: #343A40;
            --color-text-secondary: #6c757d;
            --radius-md: 14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FA;
            color: var(--color-text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .error-content {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            background: white;
            border-radius: var(--radius-md);
            box-shadow: 0 12px 36px rgba(44, 62, 80, 0.1);
        }

        .error-icon {
            font-size: 4rem;
            color: var(--color-error);
            margin-bottom: 24px;
            animation: shake 2s infinite ease-in-out;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 16px;
            color: var(--color-primary);
        }

        .error-description {
            color: var(--color-text-secondary);
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .btn-solid {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .btn-solid.primary {
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
            color: white;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            box-sizing: border-box;
        }

        .btn-solid:hover, .btn-outline:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.15);
        }

        @keyframes shake {
            0%, 100% {transform: rotate(0deg);}
            25% {transform: rotate(5deg);}
            75% {transform: rotate(-5deg);}
        }
    </style>
</head>
<body>
    <div class="error-content">
        <div class="error-icon">
            <i class="fas fa-cloud-showers-heavy"></i>
        </div>
        <h1 class="error-title">A Storm is Blocking the Path</h1>
        <p class="error-description">
            {{ $exception->getMessage() ?: "Something went wrong deep in our infrastructure. We've alerted our mountain guides." }}
        </p>
        <div class="error-actions" style="display: flex; gap: 16px; justify-content: center;">
            <a href="/" class="btn-solid primary">
                <i class="fas fa-home"></i> Back to Camp
            </a>
            <a href="/contact" class="btn-outline">
                <i class="fas fa-envelope"></i> Report Path Issue
            </a>
        </div>
    </div>
</body>
</html>
