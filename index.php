<?php

$sampleArray = [];
if (isset($_COOKIE['activities'])) {
    $sampleArray = json_decode($_COOKIE['activities'], true);
} else {
    echo "NO COOKIES DETECTED!";
}

$title = $description = '';
$tittleERR = $descriptionERR = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['title'])) {
        $tittleERR = "Invalid Title Input";
    } else {
        $title = filter_input(INPUT_POST, 'title', 
        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (empty($_POST['description'])) {
        $descriptionERR = "Invalid Description Input";
    } else {
        $description = filter_input(INPUT_POST, 'description', 
        FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (empty($tittleERR) && empty($descriptionERR)) {
        $tempArray = array("title" => $title, "description" => $description);
        if (isset($_COOKIE['activities'])) {
            $sampleArray = json_decode($_COOKIE['activities'], true);
            var_dump($sampleArray);
        }
        array_push($sampleArray, $tempArray);
        setcookie('activities', json_encode($sampleArray), time()+3600);
        header('Location: /SimplePHPTo-DoList/index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Website</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .error { color: red; }

    header, form, main {
        display: flex; 
        align-items: center; 
        justify-content: center;
        flex-direction: column;
    }

    header { 
        height: 30vh;
        background-color: black; }
        header h1 { font-size: 50px; color: white; }
        header h3 { margin-top: 0;  color: white; }

    .insrtDtls {
        height: max-content;
        padding: 10px 0;
        background-color: lightgray;
    }
        .insrtDtls input[type=text] { width: 230px; height: 30px; padding: 0 5px; }
        .insrtDtls textarea { min-width: 500px; min-height: 100px; padding: 0 5px; overflow:auto; resize: none; }

    .bttn, button {
        width: 100px;
        height: 40px;
        margin-top: 20px;
    }
        button { margin-top: 0; margin-bottom: 10px; 
        border: 1px solid red; background-color: crimson;
        color: white; }

        .bttn:hover, button:hover {
            box-shadow: 1px 1px 44px 0px rgba(73,58,58,0.75);
            cursor: pointer;
        }

    main {
        width: 100%;
        height: fit-content;
        min-height: 50vh;
        padding: 20px 0;
        gap: 20px;
        background-color: dimgrey;
    }

    .card {
        width: 600px;
        min-height: 200px;
        padding: 20px;
        text-align: center;
        background-color: aliceblue;
        border: none;
    }

    .desc-hldr { height: fit-content; padding: 10px 5px; border: 1px solid green }
</style>
<body>
    <header>
        <h1>To-Do List Website</h1>
        <h3>A place where you can list your To-Do activities for the day!</h3>
        <h3>by aAsiong</h3>
    </header>

    <section>
        <form class="insrtDtls" method="POST" 
        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <span class="error"><?php echo $tittleERR; ?></span>
            <input type="text" placeholder="Enter Activity Title" id="title" name="title">
            <span style="margin-bottom: 5px;">Activity Title</span>

            <span class="error"><?php echo $descriptionERR; ?></span>
            <textarea placeholder="Enter Description Here" id="description" name="description"></textarea>
            <span>Activity Description</span>
            <input type="submit" name="submit" value="Post" class="bttn"> 
        </form>
    </section>

    <main>
        <?php $no = 0;
        foreach($sampleArray as $activity): ?>
        <div class="card"
        id = "div<?php echo $no; ?>">
            <button type = "Submit"
                onclick = "deleteBttn(<?php echo $no; ?>)"
                id = "btnclick<?php echo $no; ?>">Clear</button>
            <div class="desc-hldr">
                <?php echo $activity['title']; ?>
            </div>
            <?php echo $activity['description']; ?>
        </div>
        <?php $no++ ?>
        <?php endforeach; ?>
    </main>
</body>
<script>
    function deleteBttn(id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            // this.readyState pertains to if the response is ready
            // this.status pertains to HTTP's good state
            // JQUERY ajax is much easier
            if (this.readyState == 4 && this.status == 200) {
                var getElem = document.getElementById('div' + id);
                getElem.remove();
            }
        }
        xmlhttp.open("GET", "process.php?q=" + id, true);
        xmlhttp.send();
    }
</script>
</html>