
<!DOCTYPE html>
<html>
<head>
    <title>My Redis View</title>
</head>
<body>
    <h1>Sample Data from Redis Cache</h1>
    <ul>
        <?php foreach ($data as $item): ?>
            <li>
                <strong>Name:</strong> <?php echo $item['name']; ?><br>
                <strong>Age:</strong> <?php echo $item['age']; ?><br>
                <strong>Email:</strong> <?php echo $item['email']; ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
