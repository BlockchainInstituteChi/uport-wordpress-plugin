var preCounter = 0;

function callVerifyEndpoint ( access_token ) {

  var data = {
    'action': 'verify_disclosure_response',
    'disclosureResponse' : access_token
  };
  
  if ( 1 != preCounter ) {
    // console.log('calling', data)
    
    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
    jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
      preCounter = 1;
      // response = JSON.parse(response);
      // console.log('Called server and got response : ', response);
      if ( true === response.success ) {
        window.location = response.redirect;
      } else {
        alert ( 'Invalid jwt received. Please contact your administrator.')
      }

    });
  }

}

function setCredentials ( ) {
  
  var data = {
    'action': 'generate_disclosure_request'     // We pass php values differently!
  };
  // console.log('calling', data)
  // We can also pass the url value separately from ajaxurl for front end AJAX implementations
  jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
    
    response = JSON.parse(response);
    // console.log('Got this from the server: ', response);
    displayQRCodeDiv("https://id.uport.me/me?requestToken=" + response.jwt);
    pollForResult('access_token', response.topic, function(result) {
      // console.log('pollForResult returned ', result)
      if ( typeof( result.message.access_token ) != 'undefined'  ) {
        // console.log('valid message found; preCounter is ', preCounter);
        callVerifyEndpoint(result.message.access_token);
      }
    }, null);
  }).fail(function() {
    alert( "Failed to generate Uport request. Are you sure you're not already logged in?" );
  })

}

var pollingInterval = 2000;

function pollForResult(topicName, url, cb, cancelled) {
  // console.log( 'pollForResult called with ', topicName, url )
  var interval = setInterval(function () {
    jQuery.get(url, {
      json: true,
      method: 'GET'
    }, function (err, res, body) {
      if (err) return cb(err);
    });
  }, pollingInterval);
}

window.addEventListener('DOMContentLoaded', (event) => {
    // console.log('this function ran, yes')
    var uportButton 			= document.createElement('input');
		uportButton.className 	= "button button-primary button-large uportButton";
		uportButton.value		= "uPort Login";
		uportButton.type 		= "button";
		uportButton.style 		= "margin-right: 0.25em;";
		uportButton.id 			= "loginWithUportButton";

	document.getElementsByClassName('submit')[0].appendChild(uportButton);

	document.getElementById('loginWithUportButton').addEventListener('click', startUportLoginSequence);

});

function startUportLoginSequence() {
	// console.log('this function also ran, yes')
	// console.log('button clicked');
	setCredentials()
}

function displayQRCodeDiv (address) {

	var overlayDiv 				= document.createElement('div')
		overlayDiv.className 	= 'uport-backdrop'
		overlayDiv.id 			= "canvasBackdrop"

	var foreGroundDiv 			= document.createElement('div')
		foreGroundDiv.className = 'loginWindow'

	var title 					= document.createElement('div')
		title.className 		= "title"

	var titleImage 				= document.createElement('img')
		titleImage.src 			= "https://cdn-images-1.medium.com/max/200/1*oeYDrEAgm1TKr8o4Lvyjlg@2x.png"

	var titleHint				= document.createElement('span')
		titleHint.innerHTML		= "Scan the QR Code with your Uport App to Login"

	var closeButton				= document.createElement('span')
		closeButton.innerHTML 	= "✕"
		closeButton.id 			= "cancel-uport-login"

	var canvas 					= document.createElement('canvas')
		canvas.id 				= "uport-login-canvas"



	title.appendChild(titleImage)
	title.appendChild(titleHint)

	// foreGroundDiv.appendChild(closeButton)
	foreGroundDiv.appendChild(title)
	foreGroundDiv.appendChild(canvas)

	overlayDiv.appendChild(closeButton)
	overlayDiv.appendChild(foreGroundDiv)

	document.body.appendChild(overlayDiv)

  document.getElementById('canvasBackdrop').addEventListener('click', cancelUportLogin)
	document.getElementById('cancel-uport-login').addEventListener('click', cancelUportLogin)

	var canvas = document.getElementById('uport-login-canvas')

	QRCode.toCanvas(canvas, address, function (error) {
	  if (error) return console.error(error)

	})
}

function cancelUportLogin () {
	// console.log('cancelUportLogin clicked')
	document.getElementById('canvasBackdrop').remove()

}

