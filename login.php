<?php
include 'component/header.php';
include 'component/navbar.php';
?>
    <!-- Login/Register Page -->
    <main id="login-page" class="page-section active min-h-[93vh]">
        <div class="hero min-h-screen bg-base-300">
            <div class="hero-content flex-col lg:flex-row-reverse">
                <div class="text-center lg:text-left lg:ml-10">
                    <h1 class="text-5xl font-bold">Welcome to FashionHub!</h1>
                    <p class="py-6">Your destination for trendy clothing and accessories. Sign in to access your account or register to join our fashion community.</p>
                </div>
                <div class="card flex-shrink-0 w-full max-w-sm shadow-2xl bg-base-100">
                    <div class="tabs w-full">
                        <a class="tab tab-lifted flex-1 tab-active" id="login-tab" onclick="switchAuthTab('login')">Login</a>
                        <a class="tab tab-lifted flex-1" id="register-tab" onclick="switchAuthTab('register')">Register</a>
                    </div>
                    <div class="card-body">
                        <!-- Login Form -->
                        <form id="loginForm">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input name="email" type="email" placeholder="email" class="input input-bordered" />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Password</span>
                                </label>
                                <input name="password" type="password" placeholder="password" class="input input-bordered" />
                                <label class="label">
                                    <a href="#" class="label-text-alt link link-hover">Forgot password?</a>
                                </label>
                            </div>
                            <div class="form-control mt-6">
                                <button class="btn btn-primary" type="submit">Login</button>
                            </div>
                            <div class="divider">OR</div>
                            <div class="form-control">
                                <button class="btn btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                    </svg>
                                    Login with Google
                                </button>
                            </div>
                            <p id="loginMessage"></p>
                        </form>
                        
                        <!-- Register Form -->
                        <form id="registerForm" style="display: none;">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Full Name</span>
                                </label>
                                <input type="text" name="full_name" placeholder="Full Name" class="input input-bordered" required>

                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" name="email" placeholder="Email" class="input input-bordered" required>

                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Phone Number</span>
                                </label>
                                <input type="text" name="phone_number" placeholder="Phone Number" class="input input-bordered" required>

                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Date of Birth</span>
                                </label>
                                <input type="date" name="date_of_birth" placeholder="Date of Birth" class="input input-bordered" required>

                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Password</span>
                                </label>
                                <input type="password" name="password" placeholder="Password" class="input input-bordered" required>

                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Confirm Password</span>
                                </label>
                                <input type="password" name="confirm_password" placeholder="Confirm Password" class="input input-bordered" required>

                            </div>
                            <div class="form-control mt-6">
                                <button class="btn btn-primary" type="submit">Register</button>
                            </div>
                            <div class="divider">OR</div>
                            <div class="form-control">
                                <button class="btn btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                    </svg>
                                    Register with Google
                                </button>
                            </div>
                            <p id="registerMessage"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include 'component/footer.php'; ?>

<script>      

// Auth tab switching
function switchAuthTab(tab) {
    if (tab === 'login') {
        document.getElementById('loginForm').style.display = 'block';
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('login-tab').classList.add('tab-active');
        document.getElementById('register-tab').classList.remove('tab-active');
    } else {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
        document.getElementById('login-tab').classList.remove('tab-active');
        document.getElementById('register-tab').classList.add('tab-active');
    }
}
const registerForm = document.getElementById('registerForm');
registerForm.onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());
            data.action = 'register';

            const res = await fetch('api/auth.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });

            const result = await res.json();
            document.getElementById('registerMessage').textContent = result.message;
            showNotification(result.message, result.success == true ? 'success': 'error');
            if (result.success) {
                window.location.href = 'login.php';

            }
        };

const loginForm = document.getElementById('loginForm');
loginForm.onsubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData(loginForm);
    const data = Object.fromEntries(formData.entries());
    data.action = 'login';

    const res = await fetch('api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    });

    const result = await res.json();
    document.getElementById('loginMessage').textContent = result.message;
    showNotification(result.message, result.success == true ? 'success': 'error');

    if (result.success) {
        window.location.href = 'index.php'; 
    }
};

</script>

</body>
</html>
