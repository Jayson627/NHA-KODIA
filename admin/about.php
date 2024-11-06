<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Kodia NHA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('houses.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin: 0;
        }
        h2 {
            font-size: 3em; /* Increase heading size */
            border-right: 2px solid #fff; /* Cursor effect */
            white-space: nowrap; /* Prevent line breaks */
            overflow: hidden; /* Cut off content */
            width: 0; /* Start with width of 0 */
            animation: typing 4s steps(40, end) forwards, blink-caret 0.75s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #fff; }
        }
        .home-button {
            position: fixed; /* Fix the button in place */
            top: 20px; /* Adjust distance from the top */
            right: 20px; /* Adjust distance from the right */
            background: none;
            border: none; /* Remove the default border */
            color: #fff;
            font-size: 1.5em;
            cursor: pointer;
            text-decoration: underline; /* Underline effect */
            z-index: 1000; /* Ensure it stays on top */
        }
        p {
            font-size: 1.5em; /* Increase paragraph size */
            line-height: 1.6; /* Adjust line height for better readability */
            opacity: 0;
            animation: fadeIn 1s forwards 4.5s; /* Delay to fade in after typing */
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <button class="home-button" onclick="location.href='login.php'">Home</button>
    <h2>About Kodia NHA</h2>
    <p id="description"></p>

    <script>
        const description = `The National Housing Authority (NHA) of Kodia is located in Barangay Kodia, Madridejos, Cebu. The housing consists of 750 units, 27 blocks, and 58 lots. Based on history, the Kodia NHA was constructed the year after Typhoon Yolanda and was turned over to the barangay in May 2021. All barangays in Madridejos, except for Barangay Tugas and Kangwayan, were provided with housing units. Each barangay was allocated 50 units for those residents who needed to evacuate during typhoons. The process of allocation involved barangay officials distributing forms to the recipients to fill out the necessary information.
        
        Every barangay received 50 units, while Barangay Kodia received 100 units because the housing was built in their areas. Based on our survey, the barangays with the most residents living in the housing are Barangay Mancilang and Barangay Poblacion, as they are closest to the sea and most prone to typhoons. According to our survey, there are over 80 units that are not occupied but have owners. There is a possibility that the housing units may be reclaimed if they are not occupied for over a year.`;

        // Split description into sentences and add a line break after each
        const sentences = description.split('. ').map(sentence => sentence + '.').join('');

        let i = 0;
        const speed = 50; // Typing speed in milliseconds

        function type() {
            if (i < sentences.length) {
                document.getElementById("description").innerHTML += sentences.charAt(i);
                i++;
                setTimeout(type, speed);
            }
        }

        // Start typing effect after heading animation
        setTimeout(type, 4500); // Delay to match the end of the title animation
    </script>

</body>
</html>
