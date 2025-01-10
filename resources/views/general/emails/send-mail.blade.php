<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $title }}</title>
	<style>
        /* Reset some default styles */
        body, p, h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        /* Media queries for responsiveness */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            h1 {
                font-size: 20px;
            }
        }
	</style>
</head>
<body>
<div class="container">
	<h1>{{ $title }}</h1>
	<p>{{ $message }}</p>
</div>
</body>
</html>
