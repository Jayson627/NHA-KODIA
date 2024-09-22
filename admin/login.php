<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>nha-kodia.com</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: black;
      color: limegreen;
      font-family: 'Courier New', Courier, monospace;
      overflow: hidden;
    }
    .hacked-message {
      font-size: 3rem;
      text-align: center;
      position: relative;
      animation: glitch 2s infinite;
    }

    .hacked-message::before,
    .hacked-message::after {
      content: "pagka huyang man sang inyo system idol!";
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      color: limegreen;
      background: black;
      overflow: hidden;
    }

    .hacked-message::before {
      left: 2px;
      text-shadow: -2px 0 red;
      clip: rect(24px, 550px, 90px, 0);
      animation: glitchTop 2s infinite linear alternate-reverse;
    }

    .hacked-message::after {
      left: -2px;
      text-shadow: -2px 0 blue;
      clip: rect(85px, 550px, 140px, 0);
      animation: glitchBottom 2s infinite linear alternate-reverse;
    }

    @keyframes glitch {
      0% {
        transform: rotate(0deg);
      }
      20% {
        transform: rotate(2deg);
      }
      40% {
        transform: rotate(-2deg);
      }
      60% {
        transform: rotate(1deg);
      }
      80% {
        transform: rotate(-1deg);
      }
      100% {
        transform: rotate(0deg);
      }
    }

    @keyframes glitchTop {
      0% {
        clip: rect(24px, 550px, 90px, 0);
      }
      50% {
        clip: rect(0px, 550px, 50px, 0);
      }
      100% {
        clip: rect(24px, 550px, 90px, 0);
      }
    }

    @keyframes glitchBottom {
      0% {
        clip: rect(85px, 550px, 140px, 0);
      }
      50% {
        clip: rect(60px, 550px, 120px, 0);
      }
      100% {
        clip: rect(85px, 550px, 140px, 0);
      }
    }
  </style>
</head>
<body>
  <div class="hacked-message">pagka huyang man sang inyo system idol</div>
</body>
</html>
