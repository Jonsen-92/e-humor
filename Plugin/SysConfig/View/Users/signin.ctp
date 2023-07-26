<?php
echo $this->Html->script('sweetalert2.min');
echo $this->Html->css('particle');
echo $this->Html->css('sweetalert2.min');


if (isset($notValid)) {
	echo "<script type='text/javascript'>
					Swal.fire({
						title: 'username or password is not valid!',
						confirmButtonColor: '#d33',
						preConfirm: () => {
							document.getElementById('UserAccountUsername').value = null
							document.getElementById('UserAccountUsername').focus();
							document.getElementById('UserAccountPassword1').value = null
						}
					});
				</script>";
}

?>

<style>
    body {
		margin:0;
		padding:0;
		font-family: sans-serif;
		background-image: radial-gradient(circle at 50% -20.71%, #edf3ff 0, #b5c9f2 50%, #79a1e2 100%);
	}

	.panel-heading {
		background-image: radial-gradient(circle at 9.44% -7.92%, #fcffff 0, #edffff 12.5%, #dcfffd 25%, #c9faf7 37.5%, #b5f2f2 50%, #a0e9ee 62.5%, #8be1ec 75%, #78d9eb 87.5%, #65d2eb 100%);
		margin: 0 0 30px;
	}

	img {
		width: 110px;
		margin-left: 40px;
	}

	h3 {
		color : white
	}
    .login-box {
		position: absolute;
		top: 25%;
		left: 50%;
		width: 400px;
		padding: 40px;
		transform: translate(-50%, -50%);
		background: rgba(0,0,0,.5);
		box-sizing: border-box;
		box-shadow: 0 15px 25px rgba(0,0,0,.6);
		border-radius: 10px;
  	}

    .login-box h2 {
		margin: 0 0 10px;
		padding: 0;
		color: #fff;
		text-align: center;
	}

	.login-box h3 {
		margin: 0 0 5px;
		padding: 0;
		color: #2F4F4F;
		font-weight: bold;
		text-align: center;
	}
	
	.login-box .user-box {
		position: relative;
	}

    .login-box .user-box input {
		width: 100%;
		padding: 10px 0;
		font-size: 16px;
		color: #fff;
		margin-bottom: 30px;
		border: none;
		border-bottom: 1px solid #fff;
		outline: none;
		background: transparent;
	}

	.login-box .user-box label {
		position: absolute;
		top:0;
		left: 0;
		padding: 10px 0;
		font-size: 16px;
		color: #fff;
		pointer-events: none;
		transition: .5s;
	}

    .login-box .user-box input:focus ~ label,
	.login-box .user-box input:valid ~ label {
		top: -20px;
		left: 0;
		color: #03e9f4;
		font-size: 12px;
	}

    .btn {
		position: relative;
		display: inline-block;
		padding: 10px 20px;
		color: #03e9f4;
        font-weight: bold;
		font-size: 16px;
		text-decoration: none;
		text-transform: uppercase;
		overflow: hidden;
		transition: .5s;
		margin-top: 40px;
		letter-spacing: 4px
	}

	.btn:hover {
		background: #03e9f4;
		color: #fff;
		border-radius: 5px;
		box-shadow: 0 0 5px #03e9f4,
					0 0 25px #03e9f4,
					0 0 50px #03e9f4,
					0 0 100px #03e9f4;
  	}


</style>

<?php $this->Session->flash('auth'); echo $this->Form->create('UserAccount', array('url' => '/sys_config/users/signin', 'class'=>'login'));?>

	<div class="container">
			<div class="login-box">
                <h2>E-Humor </h2>
					<h3>(Elektronik Human Resources)</h3>
						<div class="panel-heading">
							<h3>PENGADILAN NEGERI</h3>
							<h3>PEMATANGSIANTAR</h3>
						</div>
						<div class="user-box">
                                <input type="text" name="data[UserAccount][username]" required="" autofocus>
                                <label>Username</label>
						</div>

						<div class="user-box">
                                <input type="password" name="data[UserAccount][password1]" required="" autofocus>
                                <label>Password</label>
						</div>

						<input type="submit" value="Sign in" class="btn">
						<img src="/img/logo-pn-pms.png" alt=""  width="100%">

			</div>
</div>
