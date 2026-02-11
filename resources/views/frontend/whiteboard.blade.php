<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agora Whiteboard with Video/Audio</title>
    <script src="https://sdk.netless.link/white-web-sdk/2.15.16.js"></script>
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        #whiteboard {
            width: 100%;
            height: 100vh;
            background: #f0f0f0;
        }
        #video-container {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            display: flex;
            gap: 10px;
        }
        #video-container div {
            width: 200px;
            height: 150px;
            background: black;
        }
    </style>
</head>
<body>
    <div id="whiteboard"></div>
    <div id="video-container">
        <div id="local-video"></div>
        <div id="remote-video"></div>
    </div>

    <script>
        const APP_ID = @json($appId);           // Netless appIdentifier
        const ROOM_UUID = @json($roomUUID);     // Netless UUID
        const ROOM_TOKEN = @json($roomToken);   // Netless Token
        const REGION = @json($region);          // Netless Region

        const AGORA_APP_ID = @json($rtcAppId);  // Agora App ID
        const RTC_TOKEN = @json($rtcToken);     // Agora Token

        // -------------------- WHITEBOARD --------------------
        const whiteWebSdk = new WhiteWebSdk({
            appIdentifier: "zdXBcGyKEfCkNc9kyA0nVw/DSiwfLCJ_KTZIw",
            region: REGION,
        });

        whiteWebSdk.joinRoom({
            uuid: ROOM_UUID,
            roomToken: ROOM_TOKEN,
            uid: String(Math.floor(Math.random() * 1000000)),
            isWritable: true
        }).then(room => {
            room.bindHtmlElement(document.getElementById("whiteboard"));
            console.log("Whiteboard joined");
        }).catch(err => {
            console.error("Whiteboard join failed:", err);
        });

        // -------------------- AGORA AUDIO/VIDEO --------------------
        const rtcClient = AgoraRTC.createClient({ mode: 'rtc', codec: 'vp8' });

        async function initRTC() {
            try {
                // Ask for permissions explicitly
                await navigator.mediaDevices.getUserMedia({ video: true, audio: true });

                rtcClient.init(AGORA_APP_ID, () => {
                    console.log("AgoraRTC client initialized");

                    rtcClient.join(RTC_TOKEN, ROOM_UUID, null, (uid) => {
                        console.log("User " + uid + " joined channel");

                        const localStream = AgoraRTC.createStream({
                            streamID: uid,
                            audio: true,
                            video: true,
                            screen: false
                        });

                        localStream.init(() => {
                            localStream.play('local-video');
                            rtcClient.publish(localStream, (err) => {
                                console.error("Publish local stream error:", err);
                            });
                        }, (err) => {
                            console.error("Local stream init failed", err);
                        });

                        rtcClient.on('stream-added', function (evt) {
                            const stream = evt.stream;
                            rtcClient.subscribe(stream, (err) => {
                                console.error("Subscribe stream failed", err);
                            });
                        });

                        rtcClient.on('stream-subscribed', function (evt) {
                            const remoteStream = evt.stream;
                            remoteStream.play('remote-video');
                        });

                    }, (err) => {
                        console.error("Join channel failed", err);
                    });

                }, (err) => {
                    console.error("AgoraRTC client init failed", err);
                });
            } catch (err) {
                alert("Please allow camera and microphone access to join the session.");
                console.error("Permission error:", err);
            }
        }

        initRTC();
    </script>
</body>
</html>
