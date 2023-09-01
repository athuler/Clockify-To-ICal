<!doctype html>
<html lang="en" data-bs-theme="dark">
	<head>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-EKRDW8LTLV"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-EKRDW8LTLV');
		</script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Clockify to iCal</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
		
	</head>
	<body class="container text-center">
		<h1 class="mb-3 mt-3">Clockify to iCal</h1>
		<h2 class="mb-3">See your tracked time in your calendar</h2>
		<form id="input_form">
			<div class="row mb-3 col-sm-6 offset-sm-3">
				<label for="api_key" class="col-sm-auto col-form-label">Enter your Clockify API Key:</label>
				<div class="col-sm-auto">
					<input type="text" name="api_key" title="Clockify API Key" id="api_key" class="form-control" placeholder="API Key"/>
				</div>
			</div>
			<div class="row mb-3 col-sm-6 offset-sm-3">
				<label for="num_items" class="col-sm-auto col-form-label">How many items would you like displayed?</label>
				<div class="col">
					<input type="number" name="num_items" min="1" max="1000" value="500" id="num_items" class="form-control"/>
				</div>
			</div>
		</form>
		
		
		<div id="reveal" style="display: none; transition: opacity 1s;">
			<br/>
			
			<div class="row col-sm-6 offset-sm-3">
				<hr/>
				<br/>
				<label for="ical_link" class="col-sm-auto col-form-label">Add this link to your calendar application of choice</label><br/>
				<div class="input-group mb-3 col-sm-auto">
					<input name="ical_link" type="text" class="form-control" id="input_to_copy" readonly>
					<button class="btn btn-primary" type="button" id="button-addon2" onclick="copyLink()">
						<i class="bi bi-clipboard"></i>
					</button>
				</div>
			</div>
		</div>
		
		<br/><br/>
		
		<a href="https://donate.stripe.com/00g2969475Pd4iQ5kk" target="__blank"><button class="btn btn-primary mb-3">Support this project</button></a>
		
		<p>Bugs? Feature suggestions? <a href="mailto:hi@clockifytoical.com">Contact us</a>!</p>
		
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>
		
		<script>
			// Get the input elements
			var apiKeyInput = document.getElementById("api_key");
			var numItemsInput = document.getElementById("num_items");
			var inputForm = document.getElementById("input_form");
			var revealDiv = document.getElementById("reveal");
			var inputToCopyInput = document.getElementById("input_to_copy");
			
			var newText = "";

			// Event listener for the keyup event on the apiKeyInput element
			inputForm.addEventListener("keyup", function() {
				// Check if there is any text entered in the input element
				if (apiKeyInput.value.length > 0) {
					// Fade in the revealDiv element
					revealDiv.style.display = "block";
					revealDiv.classList.add("fade-in");

					// Set the new text
					newText = "https://clockifytoical.com/view?key=" + apiKeyInput.value + "&num_items=" + numItemsInput.value;
					inputToCopyInput.value = newText;
				} else {
					// Fade out the revealDiv element
					revealDiv.classList.remove("fade-in");
				}
			});
			function copyLink() {
				navigator.clipboard.writeText(newText);
			}
		</script>
	</body>
</html>