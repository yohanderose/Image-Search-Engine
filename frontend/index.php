<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" , initial-scale=1.0">
	<title>Image Search Engine</title>

	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.7.16.min.js"></script>

</head>

<body>
	<style>
		#container {

			text-align: justify;
			-ms-text-justify: distribute-all-lines;
			text-justify: distribute-all-lines;
		}

		.box {
			width: 150px;
			height: 125px;
			background: #ccc;
			border-radius: 10px;
			vertical-align: top;
			display: inline-block;
			*display: inline;
			margin: 10px;
			zoom: 1
		}

		.stretch {
			width: 100%;
			display: inline-block;
			font-size: 0;
			line-height: 0
		}

		/* Add a black background color to the top navigation bar */
		.topnav {
			overflow: hidden;
			background-color: #e9e9e9;
			padding: 3px;
		}

		/* Style the links inside the navigation bar */
		.topnav a {
			float: left;
			display: block;
			color: black;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
			font-size: 17px;
		}

		/* Change the color of links on hover */
		.topnav a:hover {
			background-color: #ddd;
			color: black;
		}

		/* Style the "active" element to highlight the current page */
		.topnav a.active {
			background-color: #2196F3;
			color: white;
		}

		/* Style the search box inside the navigation bar */
		.topnav input[type=text] {
			width: 80%;
			padding: 6px;
			border: none;
			border-radius: 6px;
			margin-top: 8px;
			margin-right: 16px;
			font-size: 17px;
		}

		/* When the screen is less than 600px wide, stack the links and the search field vertically instead of horizontally */
		@media screen and (max-width: 600px) {

			.topnav a,
			.topnav input[type=text] {
				float: none;
				display: block;
				text-align: left;
				width: 100%;
				margin: 0;
				padding: 14px;
			}

			.topnav input[type=text] {
				border: 1px solid #ccc;
			}
		}
	</style>

	<button onclick="return login();">Sign In</button>
	<button onclick="return logout();">Sign Out</button>


	<form id="search" method='post'>

		<div class="topnav">
			<input type="text" name="query" id="query" placeholder="Search..">
			<input id="search-submit" type="button" value="Go">

		</div>

	</form>

	<form action="upload.php" method="post" enctype="multipart/form-data">
		Select image to upload:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Upload Image" name="submit">
	</form>


	<div id="container">


		<span class="stretch"></span>
	</div>

	<script>
		var grid = $('#container');

		function login() {
			window.location = "https://imgdetectntestdemo.auth.us-east-1.amazoncognito.com/login?client_id=dtqh7o342i2pfe2iad62e811v&response_type=token&scope=email+openid+profile&redirect_uri=https://ec2-3-235-253-98.compute-1.amazonaws.com/";
		}

		function logout() {
			window.location = "logout.php"
			//window.location = "https://ec2-3-235-253-98.compute-1.amazonaws.com/logout?client_id=dtqh7o342i2pfe2iad62e811v&redirect_uri=https://ec2-3-235-253-98.compute-1.amazonaws.com/";
		}

		fetchImages = async (url_) => {
			let url = decodeURIComponent(url_);
			let imgName = url.split("?")[0].split("/").pop()
			console.log(url)
			console.log(imgName)

			let html = `
               <div class="box">
				<button class="edit-button">Edit</button>
				<button class="delete-button">Delete</button>
                  <img id="${imgName}" style="width:inherit; height:inherit;" src="${url}">
              </div> 
			  `
			grid.prepend(html)
		}

		document.addEventListener("click", boxListener);

		function boxListener(event) {
			var element = event.target;
			if (element.classList.contains("edit-button")) {
				let imgName = element.nextElementSibling.nextElementSibling.id;
				let imgUrl = encodeURIComponent(element.nextElementSibling.nextElementSibling.src);
				console.log(imgName);
				window.location = `editPage.php?imgName=${imgName}&imgUrl=${imgUrl}`;
			}

			if (element.classList.contains("delete-button")) {
				let imgName = element.nextElementSibling.id;
				let imgUrl = element.nextElementSibling.src;
				console.log(imgName);
				$.ajax({
					type: 'POST',
					data: {
						imgUrl: imgUrl,
						imgName: imgName,
					},
					url: 'delete.php',
					dataType: 'json',

					success: function(result) {
						console.log('success');
					},

					error: function() {
						console.log('error');
					}
				});
			}
		}


		$('#search-submit').on('click', () => {
			var queryString = $('#query').val();
			grid.html('');

			$.ajax({
				type: 'POST',
				data: {
					query: queryString,
				},
				url: 'query.php',
				dataType: 'json',

				success: function(result) {
					// call the function that handles the response/results
					result.urls.forEach(url => {
						fetchImages(url);
					})
				},

				error: function() {
					console.log('error');
				}
			});

		})
	</script>

</body>

</html>
