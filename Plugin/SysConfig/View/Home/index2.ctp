<style>
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    nav {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999;
        width: 100%;
        background-color: #fe735e;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: space-around;
        margin-top: 0rem;
        color: #fff;
    }

    nav .logo {
        font-size: 1.rem;
        width: 30%;
    }

    nav .logo img {
        padding-top: 50px;
        width: 110px;
    }

    .nav-links {
        list-style-type: none;
        display: flex;
    }

    .nav-links li a {
        color: inherit;
        margin-left: 2rem;
        cursor: pointer;
        text-decoration: none;
    }

    .contents {
        min-height: 100vh;
        background-color: #ffffff;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        width: 80%;
        padding-top: 1rem;
        margin: 0 auto;
    }

    .content-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        max-width: 50%;
    }

    .btn {
        padding: 18px 34px;
        font-size: 18px;
        font-weight: 700;
        display: inline-block;
        margin-right: 24px;
        margin-bottom: 24px;
        color: #fff;
        background-color: #ec6964;
        border-color: #ec6964;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        line-height: 1.5;
        border-radius: 0.25;
    }

    a {
        color: #007bff;
        text-decoration: none;
        background-color: transparent;
    }

    .content-wrapper img {
        width: 110%;
        margin: 0 auto;
        /* padding-left: 60px; */
    }

    /* responsive */

    @media screen and (max-width:768px) {
        nav {
            flex-direction: initial;
            margin: 0rem;
        }

        nav .logo {
            margin-top: 2rem;
            text-align: left;
            width: 100%;
        }

        nav ul {
            padding-inline-start: 0;
            margin: 3rem;
        }

        h1 {
            margin-top: 0px;
        }

        .content-wrapper {
            max-width: 100% !important;
            margin-top: auto;
        }

        img {
            width: 60%;
            margin: 0 auto;
            padding: 2rem 0 2rem 0;
        }
    }


    @media screen and (min-width: 100px) {
        .contents {
            padding-top: 6rem;
        }
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>C U T I</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
</head>
<body>
    <nav>
        <div class="logo">
            <img src="http://localhost/edaran/img/icon_cuti.png" alt="" srcset="">
        </div>
        <ul class="nav-links">
            <li><a href="#"><i class="fa fa-inbox"></i>Notifications</a></li>
        </ul>
    </nav>

    <main class="contents">
        <div class="row">
            <div class="content-wrapper">
                <h1>Sistem Aplikasi Cuti Online</h1>
                <div class="content-wrapper">
                    <img src="http://localhost/edaran/img/icon_cuti2.png" alt="" srcset="">
                </div>
                <p>Saatnya ajukan Cuti tanpa ribet, cukup lewat Gadget Anda</p>
                <a href="proses/pengajuan_cutis" class="btn">AJUKAN CUTI SEKARANG</a>
            </div>

        </div>
    </main>
</body>
</html>