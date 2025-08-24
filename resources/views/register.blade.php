<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }
    </style>
</head>

<body class="font-sans min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-semibold text-gray-900 text-center mb-6">Create Account</h1>

        <form id="registration-form" action="/api/register" method="POST">
            <input type="hidden" name="_token" value="csrf-token-placeholder">

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="flex gap-2">
                    <input type="email" id="email" name="email" required
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" id="send-otp-btn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 disabled:opacity-50">
                        Send OTP
                    </button>
                </div>
            </div>

            <div class="mb-4 hidden" id="otp-section">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">Enter OTP</label>
                <div class="flex gap-2">
                    <input type="text" id="otp" name="otp" maxlength="6"
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" id="verify-otp-btn"
                        class="px-4 py-2 bg-green-600 text-white rounded-md font-semibold hover:bg-green-700 disabled:opacity-50">
                        Verify OTP
                    </button>
                </div>
                <p id="otp-status" class="mt-2 text-sm"></p>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                    Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <div class="flex justify-center">
                    <div id="image-preview-box"
                        class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-full flex items-center justify-center cursor-pointer overflow-hidden bg-gray-50 hover:border-blue-500 hover:bg-blue-50 transition-colors">
                        <img id="image-preview-tag" src="" alt="Image preview"
                            class="w-full h-full object-cover hidden">
                        <span id="image-preview-text"
                            class="text-gray-500 text-sm text-center hover:text-blue-500 transition-colors">
                            📷 Open Camera
                        </span>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" class="hidden">
                </div>
            </div>

            <input type="hidden" id="location" name="location">

            <button type="submit" id="submit-btn"
                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Register
            </button>
        </form>

        <div id="success-message" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-md hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Registration Successful!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Your account has been created successfully. You can now download your profile PDF.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div id="camera-modal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden animate-fade-in">
        <div
            class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-xl shadow-2xl w-11/12 max-w-lg text-center animate-slide-in">
            <button
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-full p-2 transition-colors"
                id="close-modal">&times;</button>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Take Profile Picture</h2>
            <div
                class="w-72 h-72 border-2 border-gray-300 rounded-full mx-auto overflow-hidden bg-gray-50 flex items-center justify-center">
                <video id="camera-video" autoplay playsinline class="w-full h-full object-cover hidden"></video>
                <div id="camera-placeholder" class="text-gray-500 text-lg text-center">
                    <div class="text-2xl mb-2">📷</div>
                    <div>Initializing camera...</div>
                </div>
            </div>
            <div class="flex justify-center gap-4 flex-wrap mt-4">
                <button type="button" id="start-camera-btn"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">Start
                    Camera</button>
                <button type="button" id="capture-btn"
                    class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors hidden">Capture
                    Photo</button>
                <button type="button" id="retake-btn"
                    class="px-6 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors hidden">Retake</button>
                <button type="button" id="use-photo-btn"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors hidden">Use
                    This Photo</button>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const imagePreviewBox = document.getElementById('image-preview-box');
        const imagePreviewTag = document.getElementById('image-preview-tag');
        const imagePreviewText = document.getElementById('image-preview-text');
        const imageInput = document.getElementById('image');
        const form = document.getElementById('registration-form');
        const submitBtn = document.getElementById('submit-btn');
        const successMessage = document.getElementById('success-message');

        const modal = document.getElementById('camera-modal');
        const closeModal = document.getElementById('close-modal');
        const video = document.getElementById('camera-video');
        const cameraPlaceholder = document.getElementById('camera-placeholder');
        const startCameraBtn = document.getElementById('start-camera-btn');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const usePhotoBtn = document.getElementById('use-photo-btn');

        const sendOtpBtn = document.getElementById('send-otp-btn');
        const otpSection = document.getElementById('otp-section');
        const verifyOtpBtn = document.getElementById('verify-otp-btn');
        const otpInput = document.getElementById('otp');
        const otpStatus = document.getElementById('otp-status');

        const locationInput = document.getElementById('location');

        let stream = null;
        let capturedImageData = null;
        let otpVerified = false;
        let userEmail = null;
        let registrationSuccessful = false;

        // Send OTP
        sendOtpBtn.addEventListener('click', async () => {
            const email = document.getElementById('email').value;
            if (!email) return alert("Please enter email first!");

            sendOtpBtn.disabled = true;
            sendOtpBtn.textContent = "Sending...";
            try {
                const res = await fetch("/api/send-otp", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value },
                    body: JSON.stringify({ email })
                });
                const data = await res.json();
                if (res.ok) {
                    otpSection.classList.remove('hidden');
                    otpStatus.textContent = "OTP sent to your email ✅";
                    otpStatus.className = "mt-2 text-sm text-green-600";
                } else {
                    otpStatus.textContent = data.message || "Failed to send OTP ❌";
                    otpStatus.className = "mt-2 text-sm text-red-600";
                }
            } catch { otpStatus.textContent = "Error sending OTP ❌"; otpStatus.className = "mt-2 text-sm text-red-600"; }
            finally { sendOtpBtn.disabled = false; sendOtpBtn.textContent = "Send OTP"; }
        });

        // Verify OTP
        verifyOtpBtn.addEventListener('click', async () => {
            const email = document.getElementById('email').value;
            const otp = otpInput.value;
            if (!otp) return alert("Enter OTP first!");

            verifyOtpBtn.disabled = true;
            verifyOtpBtn.textContent = "Verifying...";
            try {
                const res = await fetch("/api/verify-otp", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value },
                    body: JSON.stringify({ email, otp })
                });
                const data = await res.json();
                if (res.ok) { otpVerified = true; otpStatus.textContent = "OTP verified successfully ✅"; otpStatus.className = "mt-2 text-sm text-green-600"; verifyOtpBtn.classList.add("hidden"); }
                else { otpVerified = false; otpStatus.textContent = data.message || "Invalid OTP ❌"; otpStatus.className = "mt-2 text-sm text-red-600"; }
            } catch { otpVerified = false; otpStatus.textContent = "Error verifying OTP ❌"; otpStatus.className = "mt-2 text-sm text-red-600"; }
            finally { verifyOtpBtn.disabled = false; verifyOtpBtn.textContent = "Verify OTP"; }
        });

        // Camera modal
        imagePreviewBox.addEventListener('click', () => { if (!registrationSuccessful) { modal.classList.remove('hidden'); resetModalState(); } });
        closeModal.addEventListener('click', closeModalHandler);
        modal.addEventListener('click', e => { if (e.target === modal) closeModalHandler(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModalHandler(); });

        function closeModalHandler() { modal.classList.add('hidden'); stopCamera(); resetModalState(); }
        function resetModalState() { video.classList.add('hidden'); cameraPlaceholder.classList.remove('hidden'); cameraPlaceholder.innerHTML = '<div class="text-2xl mb-2">📷</div><div>Click "Start Camera" to begin</div>'; startCameraBtn.classList.remove('hidden'); captureBtn.classList.add('hidden'); retakeBtn.classList.add('hidden'); usePhotoBtn.classList.add('hidden'); }
        function stopCamera() { if (stream) { stream.getTracks().forEach(track => track.stop()); stream = null; } }

        startCameraBtn.addEventListener('click', async () => {
            console.log("📸 Start Camera button clicked");

            try {
                // Request camera access
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: { ideal: 640 },
                        height: { ideal: 640 }
                    }
                });

                console.log("✅ Stream obtained:", stream);

                // Assign stream to video element
                video.srcObject = stream;

                // Wait for metadata to load before enabling capture
                video.onloadedmetadata = () => {
                    console.log("🎥 Video metadata loaded, ready to play");
                    video.play().catch(err => console.error("Failed to play video:", err));
                };

                // Show video, hide placeholder and start button
                video.classList.remove('hidden');
                cameraPlaceholder.classList.add('hidden');
                startCameraBtn.classList.add('hidden');
                captureBtn.classList.remove('hidden');

                console.log("📹 Camera is now live!");
            } catch (err) {
                console.error("🚫 Camera access failed:", err);

                // Handle specific errors
                if (err.name === "NotAllowedError") {
                    cameraPlaceholder.innerHTML = '<div class="text-2xl mb-2">🔒</div><div>Camera access denied by user</div>';
                    alert("Camera access was denied. Please allow camera access to continue.");
                } else if (err.name === "NotFoundError") {
                    cameraPlaceholder.innerHTML = '<div class="text-2xl mb-2">🚫</div><div>No camera found</div>';
                    alert("No camera found on this device.");
                } else if (err.name === "NotReadableError") {
                    cameraPlaceholder.innerHTML = '<div class="text-2xl mb-2">⚠️</div><div>Camera is in use by another app</div>';
                    alert("Camera is being used by another application.");
                } else {
                    cameraPlaceholder.innerHTML = `<div class="text-2xl mb-2">❌</div><div>Camera error: ${err.message}</div>`;
                    alert("Could not access camera: " + err.message);
                }
            }
        });

        captureBtn.addEventListener('click', () => {
            if (!video.srcObject || video.videoWidth === 0 || video.videoHeight === 0) {
                alert("Video is not ready yet. Please wait for the camera to fully load.");
                return;
            }

            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            capturedImageData = canvas.toDataURL('image/jpeg', 0.8);
            console.log("🖼️ Image captured:", capturedImageData.substring(0, 100) + "...");

            // Update UI
            cameraPlaceholder.innerHTML = `<img src="${capturedImageData}" class="w-full h-full object-cover rounded-full" />`;
            captureBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            usePhotoBtn.classList.remove('hidden');

            stopCamera();
        });

        retakeBtn.addEventListener('click', async () => {
            capturedImageData = null;
            resetModalState();
            startCameraBtn.click();
        });
        usePhotoBtn.addEventListener('click', () => {
            if (!capturedImageData) return;

            // Update preview
            imagePreviewTag.src = capturedImageData;
            imagePreviewTag.classList.remove('hidden');
            imagePreviewText.classList.add('hidden');

            // Convert dataURL to File and assign to input
            fetch(capturedImageData)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], 'profile_picture.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    imageInput.files = dt.files;  // Now input has the file
                })
                .catch(err => console.error('Failed to set image file:', err));

            closeModalHandler();
        });


        // Get full address from geolocation
        async function fetchLocation() {
            if (!navigator.geolocation) return alert('Geolocation not supported');
            navigator.geolocation.getCurrentPosition(async pos => {
                const { latitude, longitude } = pos.coords;
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json`);
                    const data = await res.json();
                    locationInput.value = data.display_name || `${latitude}, ${longitude}`;
                } catch { locationInput.value = `${latitude}, ${longitude}`; }
            }, err => { console.warn(err); locationInput.value = ''; });
        }
        fetchLocation();

        // Form submission
        form.addEventListener('submit', async e => {
            e.preventDefault();

            // If already registered, download PDF instead
            if (registrationSuccessful) {
                await downloadPdf();
                return; // prevent the default register action
            }

            if (!otpVerified) return alert("Please verify your OTP before registering!");
            if (form.password.value !== form.password_confirmation.value) return alert("Passwords do not match!");
            console.log(capturedImageData);

            const payload = {
                name: form.name.value,
                email: form.email.value,
                password: form.password.value,
                password_confirmation: form.password_confirmation.value,
                image: capturedImageData,
                location: locationInput.value
            };

            submitBtn.disabled = true;
            submitBtn.textContent = 'Registering...';

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok) {
                    registrationSuccessful = true;
                    userEmail = form.email.value;

                    successMessage.classList.remove('hidden');

                    // Change submit button to download PDF
                    submitBtn.textContent = 'Download PDF';
                    submitBtn.className = 'w-full bg-green-600 text-white py-3 px-4 rounded-md font-semibold hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed';
                    submitBtn.disabled = false;

                    // Disable other inputs
                    form.querySelectorAll('input:not([type="hidden"]), button:not(#submit-btn)').forEach(f => f.disabled = true);
                    imagePreviewBox.style.cursor = 'default';
                } else {
                    alert(data.message || 'Registration failed');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Register';
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Register';
            }
        });


        // Function to download PDF
        async function downloadPdf() {
            if (!userEmail) return alert('User email not found');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Downloading PDF...';

            try {
                const res = await fetch(`/api/download-pdf?email=${encodeURIComponent(userEmail)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (res.ok) {
                    const blob = await res.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'profile.pdf';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                } else {
                    const data = await res.json().catch(() => ({ message: 'PDF download failed' }));
                    alert('PDF download failed: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while downloading the PDF');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Download PDF';
            }
        }
    </script>
</body>

</html>