<?php
  session_start();

  if (isset($_POST['enter'])) {
    if ($_POST['username'] != "") {
      $_SESSION['name'] = $_POST['username'];

      $fp = fopen("chat.html", 'a');
      fwrite($fp, "<div><i><b>". $_SESSION['name'] ."</b> bergabung dengan chat.</i><br></div>");
      fclose($fp);
    }else{
      echo "<p>Nama Tidak Boleh Kosong</p>";
    }
  }

  if (isset($_POST['msg'])) {
    $text = $_POST['msg'];

    $fp = fopen("chat.html", 'a');
    fwrite($fp, "<div>(".date("H:i").") <b>".$_SESSION['name']."</b>: ".stripslashes(htmlspecialchars($text))."<br></div>");
    fclose($fp);
  }

  if (isset($_GET['logout'])) {
    $fp = fopen("chat.html", 'a');
    fwrite($fp, "<div><i><b>". $_SESSION['name'] ."</b> meninggalkan chat.</i><br></div>");
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
          <?php
            if (!isset($_SESSION['name'])) {
          ?>
            <div id="loginform">
              <p class="login"><b>Masukkan nama anda:</b></p>
              <form action="index.php" method="post">
                <input type="text" name="username" id="username" placeholder="Nama">
                <input type="submit" name="enter" id="enter" value="Masuk">
              </form>
            </div>
          <?php
            }else{
          ?>
          <div id="wrapper">
            <div id="menu">
              <p class="welcome">Hallo, <b><?php echo $_SESSION['name']; ?></b></p>
              <p class="logout"><a id="exit" href="#"><b>Keluar</b></a></p>
            </div>

            <div id="chatbox"></div>

            <form>
              <input type="text" id="msg" placeholder="Pesan">
              <input type="submit" id="sent" value="Kirim">
            </form>
          </div>
          <?php
            }
          ?>
  </body>
</html>

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
      var oldscrollHeight = $("#chatbox").attr("scrollHeight");
      $.get("chat.html", function(data) {
        $("#chatbox").html(data);
        var newscrollHeight = $("#chatbox").attr("scrollHeight");
        if(newscrollHeight > oldscrollHeight){
          $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
        }
      });
    }

    setInterval (loadChat, 1000);
</script>

<style>
  body {
      font:12px arial;
      color: #222;
      text-align:center;
  }

  form, p, span {
      margin:0;
      padding:0;
  }

  input {
    font:12px arial;
  }

  a {
      color:#D32F2F;
      text-decoration:none; }

  a:hover {
    text-decoration:underline;
  }

  #wrapper, #loginform {
      margin:0 auto;
      padding-bottom:25px;
      background:#75C9C8;
      width:504px;
      border:1px solid #97B2DB; }

  #loginform {
    padding-top:18px;
  }

  #loginform p {
    margin: 5px;
  }

  #chatbox {
      text-align:left;
      margin:0 auto;
      margin-bottom:25px;
      margin-top:5px;
      padding:10px;
      background:#F7F4EA;
      height:500px;
      width:430px;
      border:1px solid #97B2DB;
      overflow:auto; }

  #msg {
      width:385px;
      padding:2px;
      border:1px solid #97B2DB; }

  #sent { width: 60px; }

  #menu {
    padding:12.5px 25px 12.5px 25px;
  }

  .login{
    color:#fff;
  }

  .welcome {
    float:left;
    color:#fff;
  }

  .logout {
    float:right;
  }

</style>
