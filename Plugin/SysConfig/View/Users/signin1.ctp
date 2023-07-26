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
.panel-success {
    border-color: green;
}

.panel-success>.panel-heading {
    color: white;
    background: green;
    border-color: green;
 }

 #particles-js{
      /* background: #556B2F; */
      height: 100vh;
    }
    body {
	  background-image: url('http://192.168.100.106/edaran/img/bg.jpg');
	  background-color: #cccccc;
	  /* height: 200px; */
	 background-position: center;
	background-repeat: no-repeat;
	background-size: 100% 100%;
	position: relative;
  	background-attachment: fixed;
      /* width: 100%;
      height: 100vh;
      font: normal 16px Arial, Helvetica, sans-serif;
      color: #333;
      margin: 0;
      padding: 0;*/
      box-sizing: border-box;
    }
</style>

<!-- <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js" charset="utf-8"></script>
<script>
 	particlesJS.load('particles-js','particle.json', function() {
        console.log('particle.json loaded...');
      })
</script> -->

<?php $this->Session->flash('auth'); echo $this->Form->create('UserAccount', array('url' => '/sys_config/users/signin', 'class'=>'login'));?>

<div id="particles-js">
	<div class="container">
		<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
			<div class="panel panel-success" >
				<div class="panel-heading">
					<div class="panel-title">EDARAN LOGIN</div>
				</div>

				<div style="padding-top:20px" class="panel-body" >

					<div class="form-horizontal" >

						<div style="margin-bottom: 15px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
								<?php echo $this->Form->input('username',array('label'=>'','class'=>'form-control','placeholder'=>'username', 'autofocus'));?>
						</div>

						<div style="margin-bottom: 15px" class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
								<?php echo $this->Form->input('password1', array('type'=>'password','label'=>'','class'=>'form-control','placeholder'=>'password'));?>
						</div>
						<!-- </form> -->

						<div style="margin-top:10px" class="form-group">
							<!-- Button -->

							<div class="col-sm-3 controls">
							  <input type="submit" class="btn btn-success" value="Sign in" />

							</div>
							<div class="col-sm-9 controls">
								<span style="font-size:20px; margin-left: -10px"> <img src="http://192.168.100.106/edaran/img/banner-login.png" alt="" height="10%" width="100%">

							</div>
						</div>


						<div class="form-group">
							<div class="col-md-12 control">
								<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
								</div>
							</div>
						</div>

					</div>

				</div>
			</div>

		<!-- </div> -->
	</div>

</div>
</div>
<!-- </form> -->
