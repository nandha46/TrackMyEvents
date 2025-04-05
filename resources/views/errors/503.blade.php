<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .maintenance-message {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .maintenance-message h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .maintenance-message p {
            color: #666;
            margin-bottom: 20px;
        }

        .maintenance-message button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="maintenance-message">
        <h1>We'll be right back!</h1>
        <p>We're currently performing some maintenance. Please check back in a few minutes.</p>
        <button onclick="location.reload()">Refresh</button>
    </div>
</body>
</html>