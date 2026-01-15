window.addEventListener('DOMContentLoaded', function(event) {
	console.log('DOM fully loaded and parsed');
	websdkready();
});

function websdkready() {

	ZoomMtg.preLoadWasm();
  	ZoomMtg.prepareJssdk();
joinNow();
	// click join meeting button
	document
		.getElementById("join_meeting")
		.addEventListener("click", function (e) {
			e.preventDefault();

			

		});

}


function joinNow(){
	const meetConfig = {
				apiKey: '5YLCuTlOTh-3dBhFi6ysRg',
				signatureEndpoint: 'get-zoom-signature.php',
				meetingNumber: MEETING_NUMBER.replace(/ /g, ''),
				leaveUrl: 'url-goes-here',
				userName: USERNAME,
				userEmail: USEREMAIL,
				passWord: MEETING_PASSWORD,
				role: MEETING_ROLE // 1 for host; 0 for attendee
			};

			//console.log({meetConfig});

			if (!meetConfig.meetingNumber || !meetConfig.userName) {
				alert("Meeting number or username is empty");
				return false;
			}

			fetch( meetConfig.signatureEndpoint, {
				method: 'POST',
				body: JSON.stringify({ meetingData: meetConfig })
			})		 
			.then(result => result.text())
			.then(response => {
				
				ZoomMtg.init({
					leaveUrl: meetConfig.leaveUrl,
					isSupportAV: true,
					success: function (res) {
						ZoomMtg.join({
								signature: response,
								meetingNumber: meetConfig.meetingNumber,
								userName: meetConfig.userName,
								apiKey: meetConfig.apiKey,
								//userEmail: 'user@gmail.com',
								passWord: meetConfig.passWord,
								success: function(res){
									console.log('join meeting success');
																	
									//var joinUrl = "meeting.html?" + testTool.serialize(meetConfig);
									//window.open(joinUrl, "_blank");
								},
								error: function(res) {
									console.log(res);
								}
						})
					}
				})
				
			})	
}
