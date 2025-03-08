<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Contact Us - Online Shopping System</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
		}
		h1 {
			font-size: 36px;
			color: #333333;
			margin-top: 50px;
			text-align: center;
		}
		form {
			max-width: 500px;
			margin: 0 auto;
			background-color: #ffffff;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			border-radius: 5px;
			margin-top: 50px;
		}
		label {
			display: block;
			font-size: 20px;
			margin-bottom: 10px;
			color: #333333;
		}
		input[type=text], textarea {
			width: 100%;
			padding: 12px;
			border: 1px solid #ccc;
			border-radius: 4px;
			box-sizing: border-box;
			margin-bottom: 20px;
			font-size: 16px;
		}
		input[type=submit] {
			background-color: #4CAF50;
			color: #ffffff;
			padding: 12px 20px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			float: right;
		}
		input[type=submit]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
	<h1>Contact Us</h1>
	<form>
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" placeholder="Your name.." required>

		<label for="email">Email:</label>
		<input type="text" id="email" name="email" placeholder="Your email.." required>

		<label for="message">Message:</label>
		<textarea id="message" name="message" placeholder="Write something.." required></textarea>

		<input type="submit" value="Submit">
	</form>
</body>
</html>
