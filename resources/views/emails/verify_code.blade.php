<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification Code</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
      background: #fff;
      color: #111;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }
    .container {
      max-width: 420px;
      margin: 0 auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      overflow: hidden;
      padding: 32px 24px;
    }
    .brand {
      font-size: 1rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #111;
      margin-bottom: 18px;
      text-align: center;
    }
    .code {
      font-size: 2.2rem;
      font-weight: 700;
      letter-spacing: 0.2em;
      background: #111;
      color: #fff;
      padding: 12px 0;
      border-radius: 8px;
      text-align: center;
      margin: 18px 0 24px 0;
    }
    .footer {
      color: #444;
      font-size: 0.98rem;
      text-align: center;
      margin-top: 32px;
    }
    @media only screen and (max-width: 600px) {
      .container {
        padding: 16px 8px;
        border-radius: 0;
        box-shadow: none;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="brand">Electronics Mart</div>
    <p style="text-align:center;">Your email verification code is:</p>
    <div class="code">{{ $code }}</div>
    <p style="text-align:center;">This code will expire soon. Please enter it to complete your registration.</p>
    <div class="footer">Thank you,<br>Electronics Mart Team</div>
  </div>
</body>
</html>
