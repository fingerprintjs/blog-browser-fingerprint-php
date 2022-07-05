<?php
$db = new SQLite3("data.db");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $ip_stmt = $db->prepare("SELECT * FROM users WHERE visitorId = ?");
    $ip_stmt->bindValue(1, $_POST['visitorId'], SQLITE3_TEXT);
    $res = $ip_stmt->execute();

    if(($res->fetchArray())[0]) {
        die("Looks like you are already registered. Please log in");
    }
    
    if(empty($_POST['email'])) {
        die("Email is required");
    } else if(empty($_POST['password'])) {
        die("Password is required");
    } else {
       $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
       $stmt -> bindValue(1, $_POST["email"], SQLITE3_TEXT);
       $res = $stmt->execute();

       if(($res->fetchArray())[0]) {
           die("Email already exists");
       } else {
            $insert_stmt = $db->prepare("INSERT INTO users(email, password, visitorId) VALUES(?, ?, ?)");
            $insert_stmt -> bindValue(1, $_POST["email"], SQLITE3_TEXT);
            $insert_stmt -> bindValue(2, password_hash($_POST["password"], PASSWORD_BCRYPT), SQLITE3_TEXT);
            $insert_stmt -> bindValue(3, $_POST['visitorId'], SQLITE3_TEXT);
            $res = $insert_stmt->execute();

            if($res) {
                header('Location: dashboard.html');
            } else {
                die("An error occurred");
            }
       }


    }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script>
        function initFingerprintJS() {
            FingerprintJS.load({apiKey: 'DKXhBPTVv3uTGFMuh8ul'})
            .then(fp => fp.get())
            .then(result => {
                document.getElementById('visitorId').value = result.visitorId
            });
                
        }
    </script>

    <script
    async
    src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs-pro@3/dist/fp.min.js"
    onload="initFingerprintJS()"
    ></script>

    
</head>
<body>
    <div class="flex h-screen bg-blue-700">
        <div class="max-w-lg m-auto bg-blue-100 rounded p-5">   
            <h2 class="text-xl">Sign Up</h2>
            <p class="text-sm">Please fill this form to create an account.</p>
            <form class="p-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label class="block mb-2 text-blue-500">Email</label>
                    <input 
                    class="w-full p-2 mb-6 text-blue-700 border-b-2 border-blue-500 outline-none focus:bg-gray-300" 
                    type="text" name="email">

                </div>    
                <div class="form-group">
                    <label class="block mb-2 text-blue-500">Password</label>
                    <input class="w-full p-2 mb-6 text-blue-700 border-b-2 border-blue-500 outline-none focus:bg-gray-300" type="password" name="password">
                </div>
              <input name="visitorId" id="visitorId" value="" hidden>
                <div class="form-group">
                    <input class="w-full bg-blue-700 hover:bg-pink-700 text-white font-bold py-2 px-4 mb-6 rounded" type="submit" value="Submit">
            
                </div>
                
            </form>
            <footer>
                <a class="text-blue-700 hover:text-pink-700 text-sm float-left" href="#">Log In</a>
            </footer> 
        </div>
        
    </div>    
</body>
</html>