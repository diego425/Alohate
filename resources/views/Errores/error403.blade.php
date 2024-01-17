<!DOCTYPE html>
<html lang=&quot;es&quot;>
<style>
    @import url("https://fonts.googleapis.com/css?family=Comfortaa");

    p {
        font-size: 2rem;
    }

    a {
        font-size: 2rem;
        color: #2aa7cc;
        text-decoration: none;
    }

    a:hover {
        font-size: 3rem;
        color: white;
    }

    * {
        box-sizing: border-box;
    }

    body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
    }

    body {
        background-color: #a74006;
        font-family: sans-serif;
    }

    .container {
        z-index: 1;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        padding: 10px;
        min-width: 300px;
    }

    .container div {
        display: inline-block;
    }

    .container .lock {
        opacity: 1;
    }

    .container h1 {
        font-family: "Comfortaa", cursive;
        font-size: 10rem;
        text-align: center;
        color: #eee;
        font-weight: 100;
        margin: 0;
    }

    .container p {
        color: #fff;
    }

    .lock {
        transition: 0.5s ease;
        position: relative;
        overflow: hidden;
        opacity: 0;
    }

    .lock.generated {
        transform: scale(0.5);
        position: absolute;
        -webkit-animation: 2s move linear;
        animation: 2s move linear;
        -webkit-animation-fill-mode: forwards;
        animation-fill-mode: forwards;
    }

    .lock ::after {
        content: "";
        background: #a74006;
        opacity: 0.3;
        display: block;
        position: absolute;
        height: 100%;
        width: 50%;
        top: 0;
        left: 0;
    }

    .lock .bottom {
        background: #D68910;
        height: 5rem;
        width: 6rem;
        display: block;
        position: relative;
        margin: 0 auto;
    }

    .lock .top {
        height: 5rem;
        width: 4rem;
        border-radius: 50%;
        border: 10px solid #fff;
        display: block;
        position: relative;
        top: 30px;
        margin: 0 auto;
    }

    .lock .top::after {
        padding: 10px;
        border-radius: 50%;
    }

    @-webkit-keyframes move {
        to {
            top: 100%;
        }
    }

    @keyframes move {
        to {
            top: 100%;
        }
    }

    @media (max-width: 420px) {
        .container {
            transform: translate(-50%, -50%) scale(0.8);
        }

        .lock.generated {
            transform: scale(0.3);
        }
    }
</style>

<div class="container">
    <h1>4<div class="lock">
            <div class="top"></div>
            <div class="bottom"></div>
        </div>3</h1>
    <p>Accesso denegado</p>
    <a href="{{route('home')}}">
        Ir a inicio
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
        </svg>
    </a>
</div>

<script>
    const interval = 500;

    function generateLocks() {
        const lock = document.createElement('div'),
            position = generatePosition();
        lock.innerHTML = '<div class="top"></div><div class="bottom"></div>';
        lock.style.top = position[0];
        lock.style.left = position[1];
        lock.classList = 'lock' // generated';
        document.body.appendChild(lock);
        setTimeout(() => {
            lock.style.opacity = '1';
            lock.classList.add('generated');
        }, 100);
        setTimeout(() => {
            lock.parentElement.removeChild(lock);
        }, 2000);
    }

    function generatePosition() {
        const x = Math.round((Math.random() * 100) - 10) + '%';
        const y = Math.round(Math.random() * 100) + '%';
        return [x, y];
    }
    setInterval(generateLocks, interval);
    generateLocks();
</script>

</html>