<html>
  <head>
    <title>New document</title>
  </head>
  <body>
  <?php
  $headers = 'From: webmaster@example.com' . "\r\n" .
   'Reply-To: webmaster@example.com' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

      mail('mike@antiwar.com', 'Blha', 'wefwef', 'From: webmaster@example.com');
  ?>
  </body>
</html>
