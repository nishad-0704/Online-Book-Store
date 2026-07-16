(function () {
    const storageKey = 'bookstore_users';
    const sessionKey = 'bookstore_logged_in_user';

    function getUsers() {
        try {
            return JSON.parse(localStorage.getItem(storageKey) || '[]');
        } catch (error) {
            return [];
        }
    }

    function saveUsers(users) {
        localStorage.setItem(storageKey, JSON.stringify(users));
    }

    function showMessage(element, message, isError) {
        if (!element) return;
        element.textContent = message;
        element.style.color = isError ? 'red' : 'green';
        element.style.fontWeight = 'bold';
        element.style.margin = '12px 0';
        element.style.textAlign = 'center';
    }

    window.handleRegister = function (event) {
        event.preventDefault();

        const form = event.target;
        const username = (form.username.value || '').trim();
        const email = (form.email.value || '').trim();
        const password = (form.password.value || '').trim();
        const messageBox = document.getElementById('message');

        if (!username || !email || !password) {
            showMessage(messageBox, 'All fields are required.', true);
            return;
        }

        if (password.length < 6) {
            showMessage(messageBox, 'Password must be at least 6 characters long.', true);
            return;
        }

        const users = getUsers();
        const exists = users.some((user) => user.username.toLowerCase() === username.toLowerCase());

        if (exists) {
            showMessage(messageBox, 'Username already exists. Please choose another one.', true);
            return;
        }

        users.push({ username, email, password });
        saveUsers(users);
        showMessage(messageBox, 'Registration successful! Redirecting to login...', false);

        setTimeout(function () {
            window.location.href = 'login.html';
        }, 1000);
    };

    window.handleLogin = function (event) {
        event.preventDefault();

        const form = event.target;
        const username = (form.username.value || '').trim();
        const password = (form.password.value || '').trim();
        const messageBox = document.getElementById('message');

        if (!username || !password) {
            showMessage(messageBox, 'Please enter both username and password.', true);
            return;
        }

        const users = getUsers();
        const user = users.find((entry) => entry.username.toLowerCase() === username.toLowerCase());

        if (!user) {
            showMessage(messageBox, 'Username not found. Please register first.', true);
            return;
        }

        if (user.password !== password) {
            showMessage(messageBox, 'Incorrect password.', true);
            return;
        }

        localStorage.setItem(sessionKey, JSON.stringify({ username: user.username, email: user.email }));
        showMessage(messageBox, 'Login successful! Redirecting to home...', false);

        setTimeout(function () {
            window.location.href = 'index.html';
        }, 1000);
    };
})();
