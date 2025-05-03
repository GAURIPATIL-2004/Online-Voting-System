<?php
session_start();

// Redirect to login if session data is not set
if(!isset($_SESSION['userdata'])){
    header("location: ../");
}

// Fetch user profile information from session
$userData = $_SESSION['userdata'];

// Fetch group details from session
$groupsdata = $_SESSION['groupsdata'];

// Determine user's voting status
if($_SESSION['userdata']['status'] == 0){
    $status = '<b style="color:red">Not Voted</b>';
} else {
    $status = '<b style="color:green">Voted</b>';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Voting System - Dashboard</title>
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #headerSection {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #343a40;
            color: white;
        }
        #headerSection button {
            padding: 5px 10px;
            font-size: 15px;
            background-color: #6c757d;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #backbtn {
            order: 0;
        }
        #logoutbtn {
            order: 2;
        }
        #mainSection {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 auto;
            max-width: 1200px;
            padding: 20px;
        }
        #profile, #group {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin-bottom: 20px;
        }
        #profile h2, #group h2 {
            text-align: center;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        #Result {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
        }
        #Result:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
        }
        .profile-photo {
            width: 150px;
            border-radius: 50%;
            border: 2px solid #007bff;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .group-photo {
            width: 150px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div id="headerSection">
        <button id="backbtn" onclick="window.location.href='../login.html'">Back</button>
        <h1>Online Voting System</h1>
        <button id="logoutbtn" onclick="window.location.href='logout.php'">Logout</button>
    </div>
    <div id="mainSection">
        <div id="profile">
            <h2>Profile</h2>
            <img src="../uploads/<?php echo $userData['photo']; ?>" alt="Profile Photo" class="profile-photo">
            <p><strong>Name:</strong> <?php echo $userData['name']; ?></p>
            <p><strong>Mobile:</strong> <?php echo $userData['mobile']; ?></p>
            <p><strong>Address:</strong> <?php echo $userData['address']; ?></p>
            <p><strong>Age:</strong> <?php echo $userData['age']; ?></p>
            <p><strong>Status:</strong> <?php echo $status; ?></p>
        </div>
        <div id="group">
            <h2>Groups</h2>
            <?php foreach($groupsdata as $group): ?>
            <img src="../uploads/<?php echo $group['photo']; ?>" alt="Group Photo" class="group-photo">
            <p><strong>Group Name:</strong> <?php echo $group['name']; ?></p>
            <?php if($_SESSION['userdata']['status'] == 0): ?>
                <form method="POST" action="../api/vote.php">
                    <input type="hidden" name="gid" value="<?php echo $group['id']; ?>">
                    <input type="submit" value="Vote" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%;">
                </form>
            <?php else: ?>
                <button class="disabled" style="width: 100%;">Vote Disabled</button>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button id="Result" onclick="window.location.href='results.php'">Results</button>
    </div>
</body>
</html>
