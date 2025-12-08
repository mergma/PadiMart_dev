<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Software - Welcome</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            margin-bottom: 15px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #45a049;
        }

        .btn.register {
            background: #2196F3;
        }

        .btn.register:hover {
            background: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bimer's Basement Software</h1>
        <a href="./login.php" class="btn">Login</a>
        <a href="./register.php" class="btn register">Sign Up</a>
    </div>
</body>
</html>
