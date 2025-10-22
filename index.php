<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #831339 0%, #a81746 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 3rem;
            color: #831339;
            margin-bottom: 10px;
            font-weight: bold;
        }

        h1 {
            color: #831339;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #831339 0%, #a81746 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(131, 19, 57, 0.3);
            background: linear-gradient(135deg, #a81746 0%, #831339 100%);
        }

        .description {
            color: #666;
            margin-bottom: 20px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .logo {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🎓</div>
        <h1>Event Management System</h1>
        <p class="description">Welcome to the comprehensive event management platform for educational institutions.</p>
        
        <div class="buttons">
            <a href="Login/login.html" class="btn">Login to System</a>
            <a href="Register/signup.html" class="btn">Register New Account</a>
        </div>
    </div>
</body>
</html>