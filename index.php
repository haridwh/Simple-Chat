<?php
  session_start();

  if (isset($_POST['enter'])) {
    if ($_POST['username'] != "") {
      $_SESSION['name'] = $_POST['username'];

      $fp = fopen("log.html", 'a');
      fwrite($fp, "<div class='msgln'><i><b>". $_SESSION['name'] ."</b> bergabung dengan chat.</i><br></div>");
      fclose($fp);
    }else{
      echo "<p>Nama Tidak Boleh Kosong</p>";
    }
  }

  if (isset($_POST['msg'])) {
    $text = $_POST['msg'];

    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div'>(".date("g:i A").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
  }

  if (isset($_GET['logout'])) {
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgln'><i><b>". $_SESSION['name'] ."</b> meninggalkan chat.</i><br></div>");
    fclose($fp);

    session_destroy();
    header("Location: index.php");
  }
 ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Simple Chat</title>
  </head>

  <body>
        <div id="wrapper">
          <?php
            if (!isset($_SESSION['name'])) {
          ?>
            <form action="index.php" method="post">
              <input type="text" name="username" id="username" placeholder="Nama">
              <input type="submit" name="enter" id="enter" value="Masuk">
            </form>
          <?php
            }else{
          ?>
            <div id="menu">
              <p>Hallo, <b><?php echo $_SESSION['name']; ?></b></p>
              <p><a id="exit" href="#">Keluar</a></p>
            </div>

            <div id="chatbox"></div>

            <form>
              <input type="text" id="msg" placeholder="Pesan">
              <input type="submit" id="sent" value="Kirim">
            </form>
          <?php
            }
          ?>
        </div>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){

          $("#exit").click(function() {
            var exit = confirm("Are you sure you want to end the session?");
            if (exit == true) {
              window.location = 'index.php?logout=true';
            }
          });

          $("#sent").click(function() {
            var msg = $("#msg").val();
            $.post("index.php", {msg:msg});
            $("#msg").attr("value", "");
            return false;
          });
        });

        function loadChat() {
          var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
          $.get("log.html", function(data) {
            $("#chatbox").html(data);
            var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
      			if(newscrollHeight > oldscrollHeight){
      				$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
      			}
          });
        }

        setInterval (loadChat, 1000);
        </script>
  </body>

</html>
