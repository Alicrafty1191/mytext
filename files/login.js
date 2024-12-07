const login_box = document.getElementById('loginBox');
const register_box = document.getElementById('registerBox');

const logbtn = document.getElementById('logbtn');
const regbtn = document.getElementById('regbtn');

logbtn.addEventListener('click', function(){
    register_box.setAttribute('hidden', '');
    login_box.removeAttribute('hidden');
});

regbtn.addEventListener('click', function(){
    login_box.setAttribute('hidden', '');
    register_box.removeAttribute('hidden');
});

